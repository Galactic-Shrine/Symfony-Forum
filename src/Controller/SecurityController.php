<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Security\{AppAuthenticator, EmailVerifier};
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;  

#[Route(name: 'oauth_')]
class SecurityController extends AbstractController {

	private EmailVerifier $emailVerifier;

	private string $noReplyEmailService;

	use ResetPasswordControllerTrait;

	public function __construct(EmailVerifier $emailVerifier, private TranslatorInterface $translator, private ResetPasswordHelperInterface $resetPasswordHelper,
		private EntityManagerInterface $entityManager) {

		$this->emailVerifier = $emailVerifier;
		$this->noReplyEmailService = "no-reply@galactic-shrine.com";
	}

	#[Route(path: [ '/Login', '/login'], name: 'login')]
	public function appLogin(AuthenticationUtils $authenticationUtils): Response {
		// if ($this->getUser()) {
		//     return $this->redirectToRoute('target_path');
		// }

		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('OAuth/Login.twig', ['last_username' => $lastUsername, 'error' => $error]);
	}

	#[Route(path: [ '/Logout', '/logout'], name: 'logout')]
	public function logout(): void {

		//throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
	}

	// Register
	#[Route(['/Register', '/register'], name: 'register')]
	public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, 
		AppAuthenticator $authenticator): Response {

		$user = new User();
		$form = $this->createForm(RegistrationFormType::class, $user);
		$form->handleRequest($request);
		$EMessage = [
			'from' => $this->translator->trans('Service.Name.Verification', domain: 'Email'),
			'subject' => $this->translator->trans('Subject.ConfirmEmail', domain: 'Email')
		];

		if ($form->isSubmitted() && $form->isValid()) {

			// encode the plain password
			$user->setPassword(
				$userPasswordHasher->hashPassword($user,$form->get('plainPassword')->getData())
			);

			$this->entityManager->persist($user);
			$this->entityManager->flush();

			// generate a signed url and email it to the user
			$this->emailVerifier->sendEmailConfirmation('oauth_verify_email', $user,
				(new TemplatedEmail())
					->from(new Address($this->noReplyEmailService, $EMessage['from']))
					->to($user->getEmail())
					->subject($EMessage['subject'])
					->htmlTemplate('Email/ConfirmationEmail.twig')
			);
			
			return $userAuthenticator->authenticateUser( $user, $authenticator, $request);
		}

		return $this->render('OAuth/Register.twig', [
			'registrationForm' => $form->createView(),
		]);
	}

	#[Route(['/Verify/Email', '/verify/email'], name: 'verify_email')]
	public function verifyUserEmail(Request $request, UserRepository $userRepository): Response {

		//$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		$id = $request->query->get('id');
		if (null === $id) {

			return $this->redirectToRoute('oauth_register');
		}

		$user = $userRepository->find($id);
		if (null === $user) {

			return $this->redirectToRoute('oauth_register');
		}

		// validate email confirmation link, sets User::isVerified=true and persists
		try {

			$this->emailVerifier->handleEmailConfirmation($request, $user);
		}
		catch (VerifyEmailExceptionInterface $exception) {

			$this->addFlash(
				'verify_email_error', 
				$this->translator->trans($exception->getReason(), [], 'VerifyEmailBundle')
			);
			
			return $this->redirectToRoute('oauth_register');
		}

		// @TODO Change the redirect on success and handle or remove the flash message in your templates
		$this->addFlash('success', $this->translator->trans('Flash.EmailConfirmedOk', domain: 'OAuth'));

		return $this->redirectToRoute('oauth_register');
	}
	// End Register

	// Reset Password
	/**
	 * Confirmation page after a user has requested a password reset.
	 */
	#[Route(['/Check/Email', '/check/email'], name: 'check_email')]
	public function checkEmail(): Response {

		// Generate a fake token if the user does not exist or someone hit this page directly.
		// This prevents exposing whether or not a user was found with the given email address or not
		if (null === ($resetToken = $this->getTokenObjectFromSession())) {
			$resetToken = $this->resetPasswordHelper->generateFakeResetToken();
		}

		return $this->render('OAuth/CheckEmail.twig', [
			'resetToken' => $resetToken,
		]);
	}

	#[Route(['/Reset/Password', '/reset/password'], name: 'forgot_password_request')]
	public function request(Request $request, MailerInterface $mailer, TranslatorInterface $translator): Response {
		$form = $this->createForm(ResetPasswordRequestFormType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			return $this->processSendingPasswordResetEmail(
				$form->get('Email')->getData(),
				$mailer,
				$translator
			);
		}

		return $this->render('OAuth/ResetRequest.twig', [
			'requestForm' => $form->createView(),
		]);
	}

	#[Route(['/Reset/{token}', '/reset/{token}'], name: 'reset_password')]
	public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator, string $token = null): Response {

		if ($token) {
			// We store the token in session and remove it from the URL, to avoid the URL being
			// loaded in a browser and potentially leaking the token to 3rd party JavaScript.
			$this->storeTokenInSession($token);

			return $this->redirectToRoute('oauth_reset_password');
		}

		$token = $this->getTokenFromSession();

		if (null === $token) {
			throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
		}

		try {
			$user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
		} catch (ResetPasswordExceptionInterface $e) {
			$this->addFlash('reset_password_error', sprintf(
				'%s - %s',
				$translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, [], 'ResetPasswordBundle'),
				$translator->trans($e->getReason(), [], 'ResetPasswordBundle')
			));

			return $this->redirectToRoute('oauth_forgot_password_request');
		}

		// The token is valid; allow the user to change their password.
		$form = $this->createForm(ChangePasswordFormType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// A password reset token should be used only once, remove it.
			$this->resetPasswordHelper->removeResetRequest($token);

			// Encode(hash) the plain password, and set it.
			$encodedPassword = $passwordHasher->hashPassword(
				$user,
				$form->get('plainPassword')->getData()
			);

			$user->setPassword($encodedPassword);
			$this->entityManager->flush();

			// The session is cleaned up after the password has been changed.
			$this->cleanSessionAfterReset();

			return $this->redirectToRoute('oauth_login');
		}

		return $this->render('OAuth/ResetPassword.twig', [
			'resetForm' => $form->createView(),
		]);
	}

	private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer, TranslatorInterface $translator): RedirectResponse {

		$user = $this->entityManager->getRepository(User::class)->findOneBy([
			'Email' => $emailFormData,
		]);
		$EMessage = [
			'from' => $this->translator->trans('Service.Name.RessetPassword', domain: 'Email'),
			'subject' => $this->translator->trans('Subject.Request.PasswordReset', domain: 'Email')
		];

		// Do not reveal whether a user account was found or not.
		if (!$user) {
			return $this->redirectToRoute('oauth_check_email');
		}

		try {
			$resetToken = $this->resetPasswordHelper->generateResetToken($user);
		} catch (ResetPasswordExceptionInterface $e) {
			// If you want to tell the user why a reset email was not sent, uncomment
			// the lines below and change the redirect to 'app_forgot_password_request'.
			// Caution: This may reveal if a user is registered or not.
			//
			$this->addFlash('reset_password_error', sprintf(
				'%s - %s', 
				$translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'), 
				$translator->trans($e->getReason(), [], 'ResetPasswordBundle')
			));

			return $this->redirectToRoute('oauth_forgot_password_request');
		}

		$email = (new TemplatedEmail())
			->from(new Address($this->noReplyEmailService, $EMessage['from']))
			->to($user->getEmail())
			->subject($EMessage['subject'])
			->htmlTemplate('Email/ResetPassword.twig')
			->context([
				'resetToken' => $resetToken,
			])
		;

		$mailer->send($email);

		// Store the token object in session for retrieval in check-email route.
		$this->setTokenObjectInSession($resetToken);

		return $this->redirectToRoute('oauth_check_email');
	}
	// End Reset Password
}

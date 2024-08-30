<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

/**
 * Service pour la vérification des e-mails et la gestion des confirmations d'e-mail.
 * 
 * Cette classe fournit des méthodes pour envoyer des e-mails de confirmation et
 * gérer la vérification des e-mails des utilisateurs. Elle utilise le service de
 * vérification des e-mails de SymfonyCasts et le service de messagerie Symfony.
 */
class EmailVerifier {

    /**
     * Le service d'aide à la vérification des e-mails.
     * 
     * @var VerifyEmailHelperInterface
     */
    private VerifyEmailHelperInterface $verifyEmailHelper;

    /**
     * Le service de messagerie pour envoyer les e-mails.
     * 
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * Le gestionnaire d'entités Doctrine pour interagir avec la base de données.
     * 
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * Constructeur pour injecter les services nécessaires.
     * 
     * @param VerifyEmailHelperInterface $verifyEmailHelper Le service d'aide à la vérification des e-mails
     * @param MailerInterface $mailer Le service de messagerie
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités Doctrine
     */
    public function __construct(VerifyEmailHelperInterface $verifyEmailHelper, MailerInterface $mailer, EntityManagerInterface $entityManager) {

        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    /**
     * Envoie un e-mail de confirmation pour la vérification de l'adresse e-mail.
     * 
     * Cette méthode génère une signature pour l'URL de vérification, ajoute les données
     * de contexte nécessaires à l'e-mail, puis envoie l'e-mail de confirmation.
     * 
     * @param string $verifyEmailRouteName Le nom de la route pour la vérification de l'e-mail
     * @param UserInterface $user L'utilisateur pour lequel envoyer l'e-mail de confirmation
     * @param TemplatedEmail $email L'e-mail de confirmation à envoyer
     * 
     * @return void
     */
    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email): void {
        
        // Générer les composants de la signature pour l'URL de vérification
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            routeName: $verifyEmailRouteName,
            userId: $user->getId(),
            userEmail: $user->getEmail(),
            extraParams: ['id' => $user->getId()]
        );

        // Ajouter les données de contexte à l'e-mail
        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        // Mettre à jour le contexte de l'e-mail
        $email->context(context: $context);

        // Envoyer l'e-mail
        $this->mailer->send(message: $email);
    }

    /**
     * Gère la confirmation de l'adresse e-mail de l'utilisateur.
     * 
     * Cette méthode valide la confirmation de l'e-mail à partir de l'URL de demande,
     * marque l'utilisateur comme vérifié, et sauvegarde les changements dans la base de données.
     * 
     * @param Request $request La requête contenant l'URL de confirmation
     * @param UserInterface $user L'utilisateur dont l'adresse e-mail doit être confirmée
     * 
     * @return void
     * 
     * @throws VerifyEmailExceptionInterface Si la vérification de l'e-mail échoue
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void {

        // Valider la confirmation de l'e-mail
        $this->verifyEmailHelper->validateEmailConfirmation(
            signedUrl: $request->getUri(),
            userId: $user->getId(),
            userEmail: $user->getEmail()
        );

        // Marquer l'utilisateur comme vérifié
        $user->setIsVerified(IsVerified: true);

        // Persister les modifications et les sauvegarder dans la base de données
        $this->entityManager->persist(object: $user);
        $this->entityManager->flush();
    }
}

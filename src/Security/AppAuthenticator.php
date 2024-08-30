<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

/**
 * Authentificateur personnalisé pour la connexion des utilisateurs.
 * 
 * Cette classe gère l'authentification des utilisateurs via un formulaire de connexion,
 * en utilisant des badges pour la validation des informations et la gestion des tokens CSRF.
 */
class AppAuthenticator extends AbstractLoginFormAuthenticator {

    use TargetPathTrait;

    /**
     * Route utilisée pour le formulaire de connexion.
     * 
     * @var string
     */
    public const LOGIN_ROUTE = 'oauth_login';

    /**
     * Route utilisée pour les connexions AJAX.
     * 
     * @var string
     */
    private const AJAX_LOGIN_ROUTE = 'oauth_login_ajax';

    /**
     * Constructeur de l'authentificateur.
     * 
     * @param UrlGeneratorInterface $urlGenerator Le générateur d'URL utilisé pour rediriger après la connexion.
     * @param UserRepository $userRepository Le dépôt des utilisateurs utilisé pour récupérer les informations sur les utilisateurs.
     */
    public function __construct(
        private UrlGeneratorInterface $urlGenerator, 
        private UserRepository $userRepository
    ) {}

    /**
     * Vérifie si la demande est supportée par cet authentificateur.
     * 
     * @param Request $request La requête HTTP contenant les informations de connexion.
     * 
     * @return bool Retourne vrai si la requête est une demande POST pour les routes de connexion, sinon faux.
     */
    public function supports(Request $request): bool {

        return in_array(needle: $request->attributes->get('_route'), haystack: [self::LOGIN_ROUTE, self::AJAX_LOGIN_ROUTE])
            && $request->isMethod(method: 'POST');
    }

    /**
     * Authentifie l'utilisateur en créant un objet Passport.
     * 
     * @param Request $request La requête HTTP contenant les informations de connexion.
     * 
     * @return Passport L'objet Passport contenant les informations nécessaires pour l'authentification.
     */
    public function authenticate(Request $request): Passport {

        // Récupérer l'email du formulaire de connexion
        $email = $request->request->get(key: 'Email', default: '');
        $request->getSession()->set(name: SecurityRequestAttributes::LAST_USERNAME, value: $email);

        return new Passport(
            // Créer un UserBadge avec l'email et une fonction pour retrouver l'utilisateur
            userBadge: new UserBadge(userIdentifier: $email, userLoader: fn(string $identifier) => $this->userRepository->findUserByEmailOrUsername(
                    EmailOrUsername: $identifier
                )
            ),
            // Créer un PasswordCredentials avec le mot de passe du formulaire
            credentials: new PasswordCredentials(password: $request->request->get(key: 'Password', default: '')),
            // Ajouter des badges pour le CSRF et le RememberMe
            badges: [
                new CsrfTokenBadge(
                    csrfTokenId: 'authenticate',
                    csrfToken: $request->request->get(key: '_csrf_token')
                ),
                new RememberMeBadge(),
            ]
        );
    }

    /**
     * Gère la redirection après une connexion réussie.
     * 
     * @param Request $request La requête HTTP contenant les informations de connexion.
     * @param TokenInterface $token Le token d'authentification de l'utilisateur.
     * @param string $firewallName Le nom du pare-feu utilisé pour la connexion.
     * 
     * @return Response|null La réponse à envoyer après la connexion réussie, soit une réponse JSON, soit une redirection.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {

        // Si la requête est AJAX, retourner une réponse JSON
        if ($request->isXmlHttpRequest()) {

            return new JsonResponse(data: ['message' => 'Connexion réussie'], status: 200);
        }

        // Sinon, rediriger vers la page cible ou vers la page d'accueil
        $targetPath = $this->getTargetPath(session: $request->getSession(), firewallName: $firewallName);
        if ($targetPath) {

            return new RedirectResponse(url: $targetPath);
        }

        return new RedirectResponse(url: $this->urlGenerator->generate(name: self::LOGIN_ROUTE));
    }

    /**
     * Obtient l'URL de connexion pour les redirections.
     * 
     * @param Request $request La requête HTTP utilisée pour obtenir l'URL de connexion.
     * 
     * @return string L'URL de connexion.
     */
    protected function getLoginUrl(Request $request): string {

        return $this->urlGenerator->generate(name: self::LOGIN_ROUTE);
    }
}

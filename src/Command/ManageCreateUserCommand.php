<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Command;

use App\Entity\User;
use App\Enum\AvatarType;
use App\Enum\UserStatus;
use App\Enum\AvatarStyle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create a new user with specified roles (admin, moderator, or editor).'
)]
/**
 * Commande Symfony pour créer un nouvel utilisateur avec un rôle spécifique.
 * 
 * Cette commande permet de créer un nouvel utilisateur avec les rôles spécifiés 
 * (administrateur, modérateur ou éditeur) en fonction de la sélection de l'utilisateur. 
 * Définit le statut de présence sur hors ligne, définit l'image de profil par défaut comme 
 * une image téléchargeable avec un style carré sans fichier associé, marque l'utilisateur 
 * comme vérifié et activé, et définit la date de création sur la date et l'heure actuelles.
 */
class ManageCreateUserCommand extends Command {

    // Injecter les services nécessaires
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Constructeur pour injecter les services requis.
     * 
     * @param EntityManagerInterface $em Le gestionnaire d'entités Doctrine
     * @param UserPasswordHasherInterface $passwordHasher Le service pour le hachage des mots de passe
     */
    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher) {

        parent::__construct();
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Configurer la commande avec les arguments et options.
     */
    protected function configure(): void {

        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Le nom d\'utilisateur du nouvel utilisateur')
            ->addArgument('email', InputArgument::REQUIRED, 'L\'email du nouvel utilisateur')
            ->addArgument('password', InputArgument::REQUIRED, 'Le mot de passe du nouvel utilisateur')
            ->addOption('role', null, InputOption::VALUE_REQUIRED, 'Rôle du nouvel utilisateur (admin, moderator, editor)', 'editor')
            ->setHelp('Cette commande crée un nouvel utilisateur avec des rôles spécifiés. Utilisez l\'option --role pour spécifier "admin", "moderator" ou "editor".');
    }

    /**
     * Exécuter la commande pour créer un nouvel utilisateur.
     *
     * @param InputInterface $input L'entrée de la commande
     * @param OutputInterface $output La sortie de la commande
     * @return int Le code de sortie de la commande
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {

        // Récupérer les arguments et options
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $role = $input->getOption('role');

        // Validation du rôle
        if (!in_array($role, ['admin', 'moderator', 'editor'])) {
            
            $output->writeln('[Error] Rôle spécifié invalide. Utilisez "admin", "moderator" ou "editor".');
            return Command::FAILURE;
        }

        // Création et configuration du nouvel utilisateur
        $user = new User();
        $user->setUserName($username);
        $user->setEmail($email);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        // Définir les rôles en fonction de l'option choisie
        $roles = ['ROLE_USER'];
        if ($role === 'admin') {
            $roles[] = 'ROLE_ADMIN';
        } elseif ($role === 'moderator') {
            $roles[] = 'ROLE_MODERATOR';
        } elseif ($role === 'editor') {
            $roles[] = 'ROLE_EDITOR';
        }
        $user->setRoles($roles);

        // Définir les autres propriétés de l'utilisateur
        $user->Presence->setUser($user)->setStatus(UserStatus::OFFLINE);
        $user->setPicture([
            "Type"  => AvatarType::Uploadable,
            "Style" => AvatarStyle::Square,
            "File"  => null
        ]);
        $user->setIsVerified(true);
        $user->setIsEnabled(true);
        $user->setCreateAt(new \DateTimeImmutable());

        // Persister et sauvegarder l'utilisateur
        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('Utilisateur créé avec succès avec le rôle : ' . $role);

        return Command::SUCCESS;
    }
}

<?php
/**
 * C'est commende permet de crée un nouvel utilisateur avec les rôles d'utilisateur 
 * et d'administrateur, définit le statut de présence sur hors ligne, définit l'ima-
 * ge de profil par défaut comme une image téléchargeable avec un style carré sans 
 * fichier associé, marque l'utilisateur comme vérifié et activé, et définit la date 
 * de création sur la date et l'heure actuelles. 
 */
namespace App\Command;

use App\Entity\User;
use App\Enum\AvatarType;
use App\Enum\AvatarStyle;
use App\Enum\UserStatus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-user-admin',
    description: 'Create a new admin user'
)]
class CreateUserAdminCommand extends UserConfigureCommand {

    protected function execute(InputInterface $input, OutputInterface $output): int {

        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = new User();
        $user->setUserName($username);
        $user->setEmail($email);
        $hashedPassword = $this->passwordHasher->hashPassword(

            $user,
            $password
        );
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']); // set the user and admin role
        $user->Presence->setUser($user)->setStatus(UserStatus::OFFLINE);
        $user->setPicture([
            "Type"  => AvatarType::Uploadable,
            "Style" => AvatarStyle::Square,
            "File"  => null
        ]);
        $user->setIsVerified(true);
        $user->setIsEnabled(true);
        $user->setCreateAt(new \DateTimeImmutable());

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('Admin user created successfully.');

        return Command::SUCCESS;
    }
}
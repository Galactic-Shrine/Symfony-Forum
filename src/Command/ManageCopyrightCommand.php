<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

#[AsCommand(
    name: 'app:manage-copyright',
    description: 'Gère les copyrights et les auteurs dans les fichiers PHP.'
)]
/**
 * Commande Symfony pour gérer les copyrights et les auteurs dans les fichiers PHP.
 */
class ManageCopyrightCommand extends Command {

    /**
     * Année de copyright par défaut.
     */
    private const COPYRIGHT_YEAR = 2020;

    /**
     * Nom de l'organisation pour le copyright.
     */
    private const ORGANIZATION_NAME = '⋞Galactic-Shrine⋟';

    /**
     * Auteur par défaut.
     */
    private const DEFAULT_AUTHOR = '⋞Galactic-Shrine⋟ <support@galactic-shrine.com>';

    /**
     * Année actuelle.
     */
    private string $currentYear;

    /**
     * Initialise la commande.
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void {

        $this->currentYear = date('Y');
    }

    /**
     * Configure la commande.
     */
    protected function configure(): void {

        $this
            ->addArgument('directory', InputArgument::OPTIONAL, 'Le répertoire à parcourir', './src')
            ->setHelp('Cette commande permet de gérer les copyrights et auteurs dans les fichiers PHP du répertoire spécifié.');
    }

    /**
     * Exécute la commande.
     *
     * @param InputInterface $input L'entrée de la commande.
     * @param OutputInterface $output La sortie de la commande.
     *
     * @return int Le code de sortie de la commande.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {

        $directory = $input->getArgument('directory');

        if (!is_dir($directory)) {

            $output->writeln('[Error] Le répertoire spécifié n\'existe pas.');
            return Command::FAILURE;
        }

        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $actionChoiceQuestion = new ChoiceQuestion(
            "\nVoulez-vous ajouter ou mettre à jour le copyright et les auteurs ?",
            ['add' => 'Ajouter', 'update' => 'Mettre à jour'],
            'add'
        );

        $actionChoiceQuestion->setErrorMessage('Choix invalide.');
        $action = $questionHelper->ask($input, $output, $actionChoiceQuestion);

        if ($action === 'add') {

            $this->addCopyright($input, $output, $directory);
        } else {

            $this->updateCopyright($input, $output, $directory);
        }

        $output->writeln("\n[Success] Opération terminée.\n");
        return Command::SUCCESS;
    }

    /**
     * Ajoute un copyright dans les fichiers PHP.
     *
     * @param InputInterface $input L'entrée de la commande.
     * @param OutputInterface $output La sortie de la commande.
     * @param string $directory Le répertoire à parcourir.
     */
    private function addCopyright(InputInterface $input, OutputInterface $output, string $directory): void {

        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');
        
        $question = new Question("\nVoulez-vous personnaliser le commentaire de copyright ? (yes/no)\n", 'no');
        $customize = strtolower($questionHelper->ask($input, $output, $question));

        if ($customize === 'yes') {

            $question = new Question("\nCombien d'auteurs voulez-vous ajouter ?\n", 1);
            $numAuthors = (int)$questionHelper->ask($input, $output, $question);

            $authors = [];
            for ($i = 0; $i < $numAuthors; $i++) {

                $nameQuestion = new Question("\nNom et Prénom de l'auteur #" . ($i + 1) . ': ');
                $githubQuestion = new Question("\n(optionnel) GitHub de l'auteur #" . ($i + 1) . ':', null);
                $emailQuestion = new Question("\nEmail de l'auteur #" . ($i + 1) . ': ');

                $name = $questionHelper->ask($input, $output, $nameQuestion);
                $github = $questionHelper->ask($input, $output, $githubQuestion);
                $email = $questionHelper->ask($input, $output, $emailQuestion);

                if ($name === 'GalacticShrine') {

                    $name = self::ORGANIZATION_NAME;
                }

                if ($github) {

                    $authors[] = sprintf(' * @author %s @%s <%s>', $name, $github, $email);
                } else {

                    $authors[] = sprintf(' * @author %s <%s>', $name, $email);
                }
            }

            $copyright = sprintf(
                "/**\n * @copyright © %s %d-%d, Tous droits réservés.\n *\n%s\n * Ce fichier fait partie du projet Symfony-Forum développé par %s et sa communauté.\n */",
                self::ORGANIZATION_NAME,
                self::COPYRIGHT_YEAR,
                $this->currentYear,
                implode("\n", $authors),
                self::ORGANIZATION_NAME
            );

        } else {

            $copyright = sprintf(
                "/**\n * @copyright © %s %d-%d, Tous droits réservés.\n *\n * @author %s\n * Ce fichier fait partie du projet Symfony-Forum développé par %s et sa communauté.\n */",
                self::ORGANIZATION_NAME,
                self::COPYRIGHT_YEAR,
                $this->currentYear,
                self::DEFAULT_AUTHOR,
                self::ORGANIZATION_NAME
            );
        }

        $output->writeln("\n");

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        foreach ($iterator as $file) {

            if ($file->isFile() && $file->getExtension() === 'php') {

                $filePath = $file->getPathname();
                $content = file_get_contents($filePath);

                if (strpos($content, sprintf('© %s %d', self::ORGANIZATION_NAME, self::COPYRIGHT_YEAR)) === false) {

                    if (strpos($content, '<?php') === 0) {

                        $newContent = "<?php\n\n" . $copyright . substr($content, 5);
                    } else {

                        $newContent = $copyright . "\n\n" . $content;
                    }

                    file_put_contents($filePath, $newContent);
                    $output->writeln("[Opération] Ajout du copyright dans `$filePath`");
                }
            }
        }
    }

    /**
     * Met à jour les copyrights et auteurs dans les fichiers PHP.
     *
     * @param InputInterface $input L'entrée de la commande.
     * @param OutputInterface $output La sortie de la commande.
     * @param string $directory Le répertoire à parcourir.
     */
    private function updateCopyright(InputInterface $input, OutputInterface $output, string $directory): void {

        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $files = $this->getPhpFiles($directory);

        if (empty($files)) {

            $output->writeln('[Error] Aucun fichier PHP trouvé dans le répertoire spécifié.');
            return;
        }

        $fileChoiceQuestion = new ChoiceQuestion(
            "Sélectionnez le fichier à modifier:",
            $files
        );
        $fileChoiceQuestion->setErrorMessage('Choix invalide.');
        $filePath = $questionHelper->ask($input, $output, $fileChoiceQuestion);

        $content = file_get_contents($filePath);
        $continue = true;

        while ($continue) {

            $choiceQuestion = new ChoiceQuestion(
                "\nVoulez-vous ajouter, supprimer ou mettre à jour des auteurs ?",
                ['add' => 'Ajouter', 'remove' => 'Supprimer', 'update' => 'Mettre à jour', 'exit' => 'Quitter'],
                'exit'
            );
            $choiceQuestion->setErrorMessage('Choix invalide.');
            $action = $questionHelper->ask($input, $output, $choiceQuestion);

            if ($action === 'add') {

                $this->addAuthors($input, $output, $content, $filePath);
            } elseif ($action === 'remove') {

                $this->removeAuthors($input, $output, $content, $filePath);
            } elseif ($action === 'update') {

                $this->updateAuthors($input, $output, $content, $filePath);
            } else {

                $continue = false;
            }

            if ($continue) {

                $continueQuestion = new Question("\nVoulez-vous effectuer une autre opération ? (yes/no)\n", 'no');
                $continue = strtolower($questionHelper->ask($input, $output, $continueQuestion)) === 'yes';
            }
        }
    }

    /**
     * Obtient la liste des fichiers PHP dans un répertoire.
     *
     * @param string $directory Le répertoire à parcourir.
     *
     * @return array Liste des fichiers PHP.
     */
    private function getPhpFiles(string $directory): array {

        $phpFiles = [];

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        foreach ($iterator as $file) {

            if ($file->isFile() && $file->getExtension() === 'php') {

                $phpFiles[] = $file->getPathname();
            }
        }

        return $phpFiles;
    }

    /**
     * Ajoute des auteurs au contenu d'un fichier.
     *
     * @param InputInterface $input L'entrée de la commande.
     * @param OutputInterface $output La sortie de la commande.
     * @param string $content Le contenu du fichier.
     * @param string $filePath Le chemin du fichier.
     */
    private function addAuthors(InputInterface $input, OutputInterface $output, string &$content, string $filePath): void {

        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $numAuthorsQuestion = new Question("\nCombien d'auteurs voulez-vous ajouter ?\n", 1);
        $numAuthors = (int)$questionHelper->ask($input, $output, $numAuthorsQuestion);

        $authors = [];
        for ($i = 0; $i < $numAuthors; $i++) {

            $nameQuestion = new Question("\nNom et Prénom de l'auteur #" . ($i + 1) . ': ');
            $githubQuestion = new Question("\n(optionnel) GitHub de l'auteur #" . ($i + 1) . ':', null);
            $emailQuestion = new Question("\nEmail de l'auteur #" . ($i + 1) . ': ');

            $name = $questionHelper->ask($input, $output, $nameQuestion);
            $github = $questionHelper->ask($input, $output, $githubQuestion);
            $email = $questionHelper->ask($input, $output, $emailQuestion);

            $authorLine = $github ?
                sprintf(" * @author %s @%s <%s>", $name, $github, $email) :
                sprintf(" * @author %s <%s>", $name, $email);

            if (strpos($content, $authorLine) === false) {

                $authors[] = $authorLine;
            } else {

                $output->writeln("[Info] L'auteur '$name' existe déjà.");
            }
        }

        if (!empty($authors)) {

            $authorsText = implode("\n", $authors);
            $content = preg_replace('/(\*\s@copyright.*\n)/', "$1$authorsText\n", $content);
            file_put_contents($filePath, $content);
            $output->writeln("[Success] Auteur(s) ajouté(s) avec succès.");
        }
    }

    /**
     * Supprime des auteurs du contenu d'un fichier.
     *
     * @param InputInterface $input L'entrée de la commande.
     * @param OutputInterface $output La sortie de la commande.
     * @param string $content Le contenu du fichier.
     * @param string $filePath Le chemin du fichier.
     */
    private function removeAuthors(InputInterface $input, OutputInterface $output, string &$content, string $filePath): void {

        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $numAuthorsQuestion = new Question("\nCombien d'auteurs voulez-vous supprimer ?\n", 1);
        $numAuthors = (int)$questionHelper->ask($input, $output, $numAuthorsQuestion);

        for ($i = 0; $i < $numAuthors; $i++) {

            $nameQuestion = new Question("\nNom et Prénom de l'auteur à supprimer #" . ($i + 1) . ': ');
            $emailQuestion = new Question("\nEmail de l'auteur à supprimer #" . ($i + 1) . ': ');

            $name = $questionHelper->ask($input, $output, $nameQuestion);
            $email = $questionHelper->ask($input, $output, $emailQuestion);

            $authorPattern = sprintf("/\n \* @author %s.*<%s>/", preg_quote($name, '/'), preg_quote($email, '/'));

            if (preg_match($authorPattern, $content)) {

                $content = preg_replace($authorPattern, '', $content);
                $output->writeln("[Success] Auteur '$name' supprimé avec succès.");
            } else {

                $output->writeln("[Info] Auteur '$name' non trouvé.");
            }
        }

        file_put_contents($filePath, $content);
    }

    /**
     * Met à jour les informations sur un auteur dans le contenu d'un fichier.
     *
     * @param InputInterface $input L'entrée de la commande.
     * @param OutputInterface $output La sortie de la commande.
     * @param string $content Le contenu du fichier.
     * @param string $filePath Le chemin du fichier.
     */
    private function updateAuthors(InputInterface $input, OutputInterface $output, string &$content, string $filePath): void {

        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $nameQuestion = new Question("\nNom et Prénom de l'auteur à mettre à jour: ");
        $oldName = $questionHelper->ask($input, $output, $nameQuestion);
        $emailQuestion = new Question("\nEmail de l'auteur à mettre à jour: ");
        $oldEmail = $questionHelper->ask($input, $output, $emailQuestion);

        $authorPattern = sprintf("/(\*\s@author\s%s.*<%s>)/", preg_quote($oldName, '/'), preg_quote($oldEmail, '/'));

        if (preg_match($authorPattern, $content, $matches)) {

            $output->writeln("[Info] Auteur trouvé: " . $matches[0]);

            $newNameQuestion = new Question("\nNouveau nom et prénom de l'auteur: ", $oldName);
            $newName = $questionHelper->ask($input, $output, $newNameQuestion);
            $newGithubQuestion = new Question("\nNouveau GitHub de l'auteur: ", null);
            $newGithub = $questionHelper->ask($input, $output, $newGithubQuestion);
            $newEmailQuestion = new Question("\nNouveau email de l'auteur: ", $oldEmail);
            $newEmail = $questionHelper->ask($input, $output, $newEmailQuestion);

            $newAuthorLine = $newGithub ?
                sprintf(" * @author %s @%s <%s>", $newName, $newGithub, $newEmail) :
                sprintf(" * @author %s <%s>", $newName, $newEmail);

            $content = str_replace($matches[0], $newAuthorLine, $content);
            file_put_contents($filePath, $content);
            $output->writeln("[Success] Auteur mis à jour avec succès.");
        } else {

            $output->writeln("[Info] Auteur '$oldName' avec email '$oldEmail' non trouvé.");
        }
    }
}

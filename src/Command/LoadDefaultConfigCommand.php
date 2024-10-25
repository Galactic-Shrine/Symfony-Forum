<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Command;

use App\Entity\Config;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


#[AsCommand(
    name: 'app:load-default-config',
    description: 'Load default configuration settings'
)]
/**
 * Commande Symfony pour charger des paramètres de configuration par défaut dans la base de données.
 * 
 * Cette commande permet de charger des paramètres de configuration par défaut dans 
 * la base de données. Elle vérifie d'abord si chaque paramètre par défaut existe déjà 
 * et, si ce n'est pas le cas, l'ajoute à la base de données. Les paramètres de 
 * configuration par défaut incluent des informations telles que le nom du site et le thème.
 */
class LoadDefaultConfigCommand extends Command {


    /**
     * Le gestionnaire d'entités Doctrine pour interagir avec la base de données
     */
    private EntityManagerInterface $em;

    /**
     * Constructeur pour injecter le service EntityManagerInterface.
     * 
     * @param EntityManagerInterface $em Le gestionnaire d'entités Doctrine
     */
    public function __construct(EntityManagerInterface $em) {

        parent::__construct();
        $this->em = $em;
    }

    /**
     * Exécute la commande pour charger les paramètres de configuration par défaut.
     * 
     * @param InputInterface $input L'entrée de la commande
     * @param OutputInterface $output La sortie de la commande
     * @return int Le code de sortie de la commande
     */
    protected function execute(InputInterface $input, OutputInterface $output): int {

        // Créer une instance de SymfonyStyle pour une sortie de commande améliorée
        $io = new SymfonyStyle(input: $input, output: $output);

        // Tableau contenant les paramètres de configuration par défaut à charger
        $defaultConfigs = [
            ['Name' => 'Site_Name', 'Value' => 'Symfony Forum'],
            ['Name' => 'Site_Theme', 'Value' => 'dark'],
            ['Name' => 'Link_Youtube', 'Value' => null],
            ['Name' => 'Link_Github', 'Value' => null],
            ['Name' => 'Link_Facebook', 'Value' => null],
        ];

        // Parcourir chaque configuration par défaut
        foreach ($defaultConfigs as $configData) {

            // Vérifier si la configuration existe déjà dans la base de données
            $existingConfig = $this->em->getRepository(className: Config::class)->findOneBy(
                criteria: [
                    'Name' => $configData['Name']
                ]
            );

            // Si la configuration n'existe pas, créer et ajouter une nouvelle entrée
            if (!$existingConfig) {

                $config = new Config();
                $config->setName(Name: $configData['Name']);
                $config->setValue(Value: $configData['Value']);
                $this->em->persist(object: $config);
                $io->success(
                    message: "Added default config: {$configData['Name']}.\n"
                    . "La Ajout de la configuration par défaut : {$configData['Name']}.", 
                );
            } else {

                // Si la configuration existe déjà, informer l'utilisateur
                $io->info(
                    message: "Config already exists: {$configData['Name']}.\n"
                    . "La configuration existe déjà : {$configData['Name']}."
                );
            }
        }

        // Sauvegarder toutes les nouvelles configurations dans la base de données
        $this->em->flush();

        // Retourner le code de succès de la commande
        return Command::SUCCESS;
    }
}

<?php

Namespace App\Command;

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
class LoadDefaultConfigCommand extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $defaultConfigs = [

            ['Name' => 'Site_Name', 'Value' => 'Symfony Forum'],
            ['Name' => 'Site_Theme', 'Value' => 'dark'],
        ];

        foreach ($defaultConfigs as $configData) {

            $existingConfig = $this->em->getRepository(Config::class)->findOneBy(
                [
                    'Name' => $configData['Name']
                ]
            );
            
            if (!$existingConfig) {

                $config = new Config();
                $config->setName($configData['Name']);
                $config->setValue($configData['Value']);
                $this->em->persist($config);
                $io->success(
                    "Added default config: {$configData['Name']}.\n"
                    . "La Ajout de la configuration par défaut : {$configData['Name']}.", 
                );
            } else {

                $io->info(
                    "Config already exists: {$configData['Name']}.\n"
                    . "La configuration existe déjà : {$configData['Name']}."
                );
                //$io->info('Config already exists: ' . $configData['Name']);
            }
        }

        $this->em->flush();

        return Command::SUCCESS;
    }
}

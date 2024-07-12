<?php

namespace App\DataFixtures;

use App\Entity\Config;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConfigFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $configurations = [
            ['Name' => 'Site_Name', 'Value' => 'Symfony Forum'],
            ['Name' => 'Site_Theme', 'Value' => 'dark'],
        ];

        foreach ($configurations as $configData) {
            $config = new Config();
            $config->setName($configData['Name']);
            $config->setValue($configData['Value']);
            $manager->persist($config);
        }

        $manager->flush();
    }
}

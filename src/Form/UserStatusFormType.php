<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Form;

use App\Enum\UserStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour la gestion du statut utilisateur.
 * 
 * Ce formulaire permet de sélectionner un statut utilisateur à partir d'un ensemble prédéfini d'options.
 */
class UserStatusFormType extends AbstractType {

    /**
     * Construit le formulaire en ajoutant les champs nécessaires.
     * 
     * @param FormBuilderInterface $builder Le constructeur de formulaire.
     * @param array $options Les options du formulaire.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {

        $builder->add(
            child: 'status', 
            type: ChoiceType::class, 
            options: [
                'choice_translation_domain' => 'User',
                'choices' => [
                    'Status.Online' => UserStatus::ONLINE,
                    'Status.Absent' => UserStatus::ABSENT,
                    'Status.Occupied' => UserStatus::OCCUPIED,
                    'Status.Invisible' => UserStatus::INVISIBLE,
                ],
                'label' => 'Status', // Label du champ dans le formulaire
            ]
        );
    }

    /**
     * Configure les options du formulaire.
     * 
     * @param OptionsResolver $resolver Le résolveur d'options.
     */
    public function configureOptions(OptionsResolver $resolver): void {

        $resolver->setDefaults(defaults: []);
    }
}

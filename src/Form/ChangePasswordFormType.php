<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulaire pour la modification du mot de passe.
 * 
 * Ce formulaire permet aux utilisateurs de modifier leur mot de passe en fournissant un nouveau mot de passe et en confirmant ce mot de passe.
 */
class ChangePasswordFormType extends AbstractType {

    /**
     * Construit le formulaire en ajoutant les champs nécessaires pour la modification du mot de passe.
     * 
     * @param FormBuilderInterface $builder Le constructeur de formulaire utilisé pour ajouter des champs.
     * @param array $options Les options du formulaire.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {

        $builder->add(
            child: 'plainPassword', 
            type: RepeatedType::class, 
            options: [
                'translation_domain' => 'OAuth', // Domaine de traduction pour les labels et messages
                'type' => PasswordType::class, // Type de champ utilisé pour le mot de passe
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password', // Suggestion d'auto-complétion pour le champ mot de passe
                        'class' => 'form-control rounded-3 mb-1 mt-1', // Classes CSS appliquées au champ
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password', // Message d'erreur si le champ est vide
                        ]),
                        new Length([
                            'min' => 6, // Longueur minimale du mot de passe
                            'minMessage' => 'Your password should be at least {{ limit }} characters', // Message d'erreur pour mot de passe trop court
                            'max' => 4096, // Longueur maximale du mot de passe
                        ]),
                    ],
                    'label' => 'New password', // Label affiché pour le champ de mot de passe
                ],
                'second_options' => [
                    'label' => 'Repeat Password', // Label affiché pour le champ de confirmation du mot de passe
                ],
                'invalid_message' => 'The password fields must match.', // Message d'erreur si les mots de passe ne correspondent pas
                'mapped' => false, // Le champ ne sera pas mappé sur l'entité
            ]
        );
    }

    /**
     * Configure les options du formulaire.
     * 
     * @param OptionsResolver $resolver Le résolveur d'options utilisé pour configurer les options du formulaire.
     */
    public function configureOptions(OptionsResolver $resolver): void {

        $resolver->setDefaults(defaults: []);
    }
}

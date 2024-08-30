<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulaire pour l'inscription des utilisateurs.
 * 
 * Ce formulaire permet aux utilisateurs de s'inscrire en fournissant un e-mail, un nom d'utilisateur et un mot de passe.
 */
class RegistrationFormType extends AbstractType {

    /**
     * Construit le formulaire en ajoutant les champs nécessaires.
     * 
     * @param FormBuilderInterface $builder Le constructeur de formulaire.
     * @param array $options Les options du formulaire.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void {

        $builder->add(
            child: 'Email', 
            type: EmailType::class, 
            options: [
                'label' => 'Email', // Label affiché pour le champ
                'attr' => [
                    'autocomplete' => 'email', // Suggestion d'auto-complétion pour le champ email
                    'placeholder' => 'Email' // Texte d'espace réservé dans le champ
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your email', // Message d'erreur si le champ est vide
                    ]),
                ],
            ]
        )
        ->add(
            child: 'UserName', 
            type: TextType::class, 
            options: [
                'label' => 'Username', // Label affiché pour le champ
                'attr' => [
                    'placeholder' => 'Username' // Texte d'espace réservé dans le champ
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your username', // Message d'erreur si le champ est vide
                    ]),
                ],
            ]
        )
        ->add(
            child: 'agreeTerms', 
            type: CheckboxType::class, 
            options: [
                'mapped' => false, // Le champ ne sera pas mappé sur l'entité
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.', // Message d'erreur si la case n'est pas cochée
                    ]),
                ],
                'label' => 'Agree to terms', // Label affiché pour le champ
            ]
        )
        ->add(
            child: 'plainPassword', 
            type: PasswordType::class, 
            options: [
                // Le mot de passe est lu depuis le formulaire et encodé dans le contrôleur
                'mapped' => false, // Le champ ne sera pas mappé sur l'entité
                'attr' => ['autocomplete' => 'new-password'], // Suggestion d'auto-complétion pour le champ mot de passe
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
                'label' => 'Password', // Label affiché pour le champ
            ]
        )
        ->add(
            child: 'Register', 
            type: SubmitType::class, 
            options: [
                'label' => 'Register', // Label affiché pour le bouton de soumission
            ]
        );
    }

    /**
     * Configure les options du formulaire.
     * 
     * @param OptionsResolver $resolver Le résolveur d'options.
     */
    public function configureOptions(OptionsResolver $resolver): void {

        $resolver->setDefaults(
            defaults: [
                'data_class' => User::class, // La classe de données associée au formulaire
            ]
        );
    }
}

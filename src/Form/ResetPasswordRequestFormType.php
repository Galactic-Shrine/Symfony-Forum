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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulaire pour la demande de réinitialisation du mot de passe.
 * 
 * Ce formulaire permet à l'utilisateur de soumettre son adresse e-mail pour demander une réinitialisation de mot de passe.
 */
class ResetPasswordRequestFormType extends AbstractType {
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
                'translation_domain' => 'OAuth', // Domaine de traduction pour le champ
                'label' => false, // Pas de label affiché pour ce champ
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

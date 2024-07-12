<?php

namespace App\Form;

use App\Enum\UserStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserStatusType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('status', ChoiceType::class, [
                'choice_translation_domain'=> 'User',
                'choices' => [
                    'Status.Online' => UserStatus::ONLINE,
                    'Status.Absent' => UserStatus::ABSENT,
                    'Status.Occupied' => UserStatus::OCCUPIED,
                    'Status.Invisible' => UserStatus::INVISIBLE,
                    //'Status.Offline' => UserStatus::OFFLINE,
                ],
                'label' => 'Status',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        
        $resolver->setDefaults([]);
    }
}

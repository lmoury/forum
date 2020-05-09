<?php

namespace App\Form;

use App\Entity\ConversationUser;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ConversationAddStaffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('participant', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'username',
            'query_builder' => function (UserRepository $qr) {
                return $qr->createQueryBuilder('u')
                ->andWhere('u.role = 3')
                ->orWhere('u.role = 2');
            },
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConversationUser::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\ConversationUser;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ConversationUserType extends AbstractType
{

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('conversation', ConversationType::class)
            ->add('participant', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'multiple' => true,
                'query_builder' => function (UserRepository $qr) {
                    return $qr->createQueryBuilder('u')
                    ->andWhere('u.id != '.$this->tokenStorage->getToken()->getUser()->getId());
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

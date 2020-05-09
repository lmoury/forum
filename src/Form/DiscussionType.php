<?php

namespace App\Form;

use App\Entity\ForumDiscussion;
use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class DiscussionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('locked', CheckboxType::class, [
                'label' => 'Vérrouiller la discussion',
                'required' => false
            ])
            ->add('important', CheckboxType::class, [
                'label' => 'Mettre la discussion en important',
                'required' => false
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'required' => false
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumDiscussion::class,
        ]);
    }
}

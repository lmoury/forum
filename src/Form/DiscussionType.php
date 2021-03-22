<?php

namespace App\Form;

use App\Entity\ForumDiscussion;
use App\Entity\Tag;
use App\Entity\Prefixe;
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
                'label' => 'Vérrouiller',
                'required' => false
            ])
            ->add('important', CheckboxType::class, [
                'label' => 'Importante',
                'required' => false
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'required' => false
            ])
            ->add('prefixe', EntityType::class, [
                'class' => Prefixe::class,
                'choice_label' => 'prefix',
                'choices' => $options['prefixe'],
                'required' => false
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumDiscussion::class,
            'prefixe' => []
        ]);
    }
}

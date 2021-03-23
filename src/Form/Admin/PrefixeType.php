<?php

namespace App\Form\Admin;

use App\Entity\Prefixe;
use App\Entity\ForumCategorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PrefixeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prefix')
            ->add('couleur', ChoiceType::class, [
                'choices' => $this->getChoices()
            ])
            ->add('icon')
            ->add('categories', EntityType::class, [
                'class' => ForumCategorie::class,
                'choices' => $options['category'],
                'choice_label' => 'categorie',
                'required' => false,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prefixe::class,
            'category'=> []
        ]);
    }

    public function getChoices() {
        $choices = Prefixe::STYLE;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}

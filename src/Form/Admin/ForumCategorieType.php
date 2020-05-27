<?php

namespace App\Form\Admin;

use App\Entity\ForumCategorie;
use App\Repository\ForumCategorieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ForumCategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $output = [];
        foreach ($options['parent'] as $k => $v) {
            $output[$v['categorie']] = $v['id'];
            dump($output);
        }
        dump($output);
        $builder
            ->add('categorie')
            ->add('parent', ChoiceType::class, [
                'label' => 'categorie',
                'choices' => $output,
                'required' => false
            ])
            ->add('icon')
            ->add('access')
            ->add('ordre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumCategorie::class,
            'parent'=>null
        ]);
    }
}

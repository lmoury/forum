<?php

namespace App\Form;

use App\Entity\ForumDiscussion;
use App\Entity\ForumCategorie;
use App\Repository\ForumCategorieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DeplacerDiscussionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie', EntityType::class, [
                'class' => ForumCategorie::class,
                'choice_label' => 'categorie',
                'query_builder' => function (ForumCategorieRepository $qr) {
                    return $qr->createQueryBuilder('c')
                    ->andWhere('c.parent is not null');
                }
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

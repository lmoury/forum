<?php

namespace App\Form;

use App\Entity\ForumDiscussionSearch;
use App\Entity\ForumCategorie;
use App\Entity\User;
use App\Entity\Tag;
use App\Repository\ForumCategorieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ForumDiscussionSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mot_cle')
            ->add('titre', CheckboxType::class, [
                'label' => 'Rechercher par titre uniquement',
                'required' => false
            ])
            ->add('date_creation', BirthdayType::class, [
                    'widget' => 'single_text',
                    'format' => 'dd MM yy',
                    'html5' => false,
			        'attr' => ['class' => 'js-datepicker'],
                    'required' => false
                ])
            ->add('categories', EntityType::class, [
                'class' => ForumCategorie::class,
                'choices' => $options['category'],
                'choice_label' => 'categorie',
                'required' => false,
                'multiple' => true,
                
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'required' => false,
                'multiple' => true
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'nom',
                'required' => false,
                'multiple' => true
            ])
            ->add('trier', ChoiceType::class, [
                'choices' =>[
                    'Pertinence' => 'valeur1',
                    'Date dernier commentaire' => 'valeur2',
                    'Date de création' => 'valeur3'
                ],
                'multiple' => false,
                'data' => 'valeur1',
                'expanded' => true
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumDiscussionSearch::class,
            'method' => 'get',
            'csrf_protection' => false,
            'category' => []

        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}

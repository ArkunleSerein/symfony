<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('projects', EntityType::class, [
                'class' => Project::class,
                'choice_label' => function (Project $project) {
                    return "{$project->getName()} (id {$project->getId()})";
                },
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form_scrollable-checkboxes',
                ],
                'by_reference' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC')
                        ->addOrderBy('p.id', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}

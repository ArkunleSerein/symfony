<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Project;
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
                'choice_label' => function(Project $project) {
                    return "{$project->getName()} (id {$project->getId()})";
                },
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form_scrollable-checkboxes',
                ],
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}

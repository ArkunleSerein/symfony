<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Student;
use App\Entity\Tag;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('clientName')
            ->add(
                'startDate',
                DateType::class,
                [
                    'widget' => 'single_text',
                ]
            )
            ->add(
                'checkpointDate',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'required' => false,
                ]
            )
            ->add(
                'deliveryDate',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'required' => false,
                ]
            )
            ->add(
                'students',
                EntityType::class,
                [
                    'class' => Student::class,
                    'choice_label' => function (Student $student) {
                        return "{$student->getFirstName()} (id {$student->getLastName()})";
                    },
                    'multiple' => true,
                    'expanded' => true,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                            ->orderBy('s.firstName', 'ASC')
                            ->addOrderBy('s.lastName', 'ASC');
                    },
                    //! C'est à ajouter que pour les associations du coté possédant.
                    // autrement dit, il est necessaire si dans l'entité Project, la propriété students possédé l'attribut mappedBy.
                    // dans ce cas, Student est le coté possédant et le Project est le coté possédé(inverse).
                    "by_reference" => false,
                ]
            )
            ->add(
                'tags',
                EntityType::class,
                [
                    'class' => Tag::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                            ->orderBy('t.name', 'ASC');
                    },
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}

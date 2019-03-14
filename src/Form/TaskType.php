<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'User story' => 'userstory',
                    'Épique story' => 'epicstory',
                    'Fonctionnalité' => 'feature',
                    'Bug' => 'bug',
                    'Tâche technique' => 'tech'
                ],
                'placeholder' => 'Choisissez'
            ])
            ->add('estimated', NumberType::class, [
                'label' => 'Temps éstimé (h)',
                'required' => false,
            ])
            ->add('priority', ChoiceType::class, [
                'label' => 'Priorité',
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '5' => '5',
                    '8' => '8',
                    '13' => '13',
                    '21' => '21',
                    '34' => '34',
                    '55' => '55',
                ],
                'placeholder' => 'Choisissez',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}

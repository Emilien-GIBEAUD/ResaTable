<?php

namespace App\Form;

use App\Entity\PizzaServiceTemplate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PizzaServiceTemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('startTime', TimeType::class, [
                'label' => 'Horaire de début du service',
                'widget' => 'single_text',
                'attr' => [
                    'step' => '900',
                ],
            ])
            ->add('endTime', TimeType::class, [
                'label' => 'Horaire de fin du service',
                'widget' => 'single_text',
                'attr' => [
                    'step' => '900',
                ],
            ])
            ->add('slotDurationInMin', IntegerType::class, [
                'label' => 'Durée d\'un créneau (en minutes)',
                'attr' => [
                    'step' => '5',
                    'min' => '5',
                ],
            ])
            ->add('capacityPerSlot', IntegerType::class, [
                'label' => 'Capacité par créneau',
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Visible',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PizzaServiceTemplate::class,
        ]);
    }
}

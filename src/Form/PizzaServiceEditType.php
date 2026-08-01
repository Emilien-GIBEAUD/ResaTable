<?php

namespace App\Form;

use App\Entity\PizzaService;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PizzaServiceEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('serviceDate', null, [
                'label' => 'Date de service',
                'widget' => 'single_text',
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
            ->add('bookingOpen', CheckboxType::class, [
                'label' => 'Réservation ouverte',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PizzaService::class,
        ]);
    }
}

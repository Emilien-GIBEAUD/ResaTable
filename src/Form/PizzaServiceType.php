<?php

namespace App\Form;

use App\Entity\PizzaService;
use App\Entity\PizzaServiceTemplate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PizzaServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('serviceDate', null, [
                'label' => 'Date de service',
                'widget' => 'single_text',
            ])
            ->add('template', EntityType::class, [
                'label' => 'Modèle de service',
                'class' => PizzaServiceTemplate::class,
                'choice_label' => 'name',
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

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
                'choice_label' => function (PizzaServiceTemplate $template): string {
                    $interval = $template->getStartTime()->diff($template->getEndTime());
                    $minutes = $interval->h * 60 + $interval->i;
                    $numberOfPizzas = $minutes / $template->getSlotDurationInMin() * $template->getCapacityPerSlot();
                    return sprintf(
                        '(%s - %s) - %s pizzas / %s mins (%s pizzas)',
                        $template->getStartTime()->format('H:i'),
                        $template->getEndTime()->format('H:i'),
                        $template->getCapacityPerSlot(),
                        $template->getSlotDurationInMin(),
                        $numberOfPizzas
                    );
                },
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

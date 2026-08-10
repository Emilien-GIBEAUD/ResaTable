<?php

namespace App\Controller;

use App\Entity\PizzaService;
use App\Entity\PizzaServiceSlot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/slot')]
final class SlotController extends AbstractController
{

    // TODO : Add slot before first or after last slot of a service.
    // #[IsGranted('ROLE_ADMIN')]
    // #[Route('/new', name: 'app_slot_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $pizzaServiceSlot = new PizzaServiceSlot();
    //     $form = $this->createForm(PizzaServiceSlotType::class, $pizzaServiceSlot);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($pizzaServiceSlot);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_slot_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('slot/new.html.twig', [
    //         'pizza_service_slot' => $pizzaServiceSlot,
    //         'form' => $form,
    //     ]);
    // }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/serviceSlots/{id}', name: 'app_slot_show_services_slots', methods: ['GET'])]
    public function showServiceSlots(PizzaService $service): Response
    {
        $serviceSlots = $service->getPizzaServiceSlots();
        return $this->render('slot/showServiceSlots.html.twig', [
            'pizza_service' => $service,
            'pizza_service_slots' => $serviceSlots,
        ]);
    }

    #[Route('/serviceSlots/{id}/json', name: 'app_service_slots_json', methods: ['GET'])]
    public function showServiceSlotsJson(PizzaService $service): Response
    {
        $serviceSlots = $service->getPizzaServiceSlots();
        $data = [];

        foreach ($serviceSlots as $slot) {
            if ($slot->getCapacity() > 0) {
                $endTime = $slot->getStartTime()->modify('+' . $service->getSlotDurationInMin() . ' minutes');
                $availableCapacity = $slot->getCapacity();
                $reservations = $slot->getReservations();
                foreach ($reservations as $reservation) {
                    if ($reservation->getStatus() === 'CONFIRMED' || $reservation->getStatus() === 'PENDING') {
                        $reservationItems = $reservation->getReservationItems();
                        foreach ($reservationItems as $item) {
                            $availableCapacity -= $item->getQuantity();
                        }
                    }
                }
                $data[] = [
                    'id' => $slot->getId(),
                    'startTime' => $slot->getStartTime()->format('H:i'),
                    'endTime' => $endTime->format('H:i'),
                    'availableCapacity' => $availableCapacity,
                ];
            }
        }

        return $this->json($data);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/editCapacity/{action}', name: 'app_slot_edit_capacity', methods: ['POST'])]
    public function edit(
        PizzaServiceSlot $slot, 
        string $action,
        EntityManagerInterface $entityManager): Response
    {
        if ($action === 'increase') {
            $slot->setCapacity($slot->getCapacity() + 1);
        } elseif ($action === 'decrease' && $slot->getCapacity() > 0) {
            // TODO : Add a check to ensure capacity does not go below reservation count
            $slot->setCapacity($slot->getCapacity() - 1);
        }

        $entityManager->flush();

        return $this->redirectToRoute(
            'app_slot_show_services_slots',
            ['id' => $slot->getService()->getId()]
        );
    }

}

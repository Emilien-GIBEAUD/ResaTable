<?php

namespace App\Controller;

use App\Entity\PizzaService;
use App\Entity\PizzaServiceSlot;
use App\Form\PizzaServiceSlotType;
use App\Repository\PizzaServiceRepository;
use App\Repository\PizzaServiceSlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/slot')]
final class SlotController extends AbstractController
{
    // #[Route(name: 'app_slot_index', methods: ['GET'])]
    // public function index(PizzaServiceSlotRepository $pizzaServiceSlotRepository): Response
    // {
    //     return $this->render('slot/index.html.twig', [
    //         'pizza_service_slots' => $pizzaServiceSlotRepository->findAll(),
    //     ]);
    // }

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

    // #[IsGranted('ROLE_ADMIN')]
    // #[Route('/{id}', name: 'app_slot_show', methods: ['GET'])]
    // public function show(PizzaServiceSlot $pizzaServiceSlot): Response
    // {
    //     return $this->render('slot/show.html.twig', [
    //         'pizza_service_slot' => $pizzaServiceSlot,
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
            $data[] = [
                'id' => $slot->getId(),
                'startTime' => $slot->getStartTime()->format('H:i'),
                'capacity' => $slot->getCapacity(),
            ];
        }

        return $this->json($data);
    }

    // #[IsGranted('ROLE_ADMIN')]
    // #[Route('/{id}/edit', name: 'app_slot_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, PizzaServiceSlot $pizzaServiceSlot, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(PizzaServiceSlotType::class, $pizzaServiceSlot);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_slot_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('slot/edit.html.twig', [
    //         'pizza_service_slot' => $pizzaServiceSlot,
    //         'form' => $form,
    //     ]);
    // }

    // #[IsGranted('ROLE_ADMIN')]
    // #[Route('/{id}', name: 'app_slot_delete', methods: ['POST'])]
    // public function delete(Request $request, PizzaServiceSlot $pizzaServiceSlot, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$pizzaServiceSlot->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($pizzaServiceSlot);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_slot_index', [], Response::HTTP_SEE_OTHER);
    // }
}

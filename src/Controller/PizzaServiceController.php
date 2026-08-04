<?php

namespace App\Controller;

use App\Entity\PizzaService;
use App\Form\PizzaServiceEditType;
use App\Form\PizzaServiceNewType;
use App\Repository\PizzaServiceRepository;
use App\Service\SlotGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/pizzas/service')]
final class PizzaServiceController extends AbstractController
{
    #[Route(name: 'app_pizza_service_index', methods: ['GET'])]
    public function index(PizzaServiceRepository $pizzaServiceRepository): Response
    {
        return $this->render('pizza_service/index.html.twig', [
            'pizza_services' => $pizzaServiceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pizza_service_new', methods: ['GET', 'POST'])]
    public function new(Request $request,EntityManagerInterface $entityManager): Response
    {
        $pizzaService = new PizzaService();
        $form = $this->createForm(PizzaServiceNewType::class, $pizzaService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Add the  service to the datatbase
            $template = $pizzaService->getTemplate();
            $pizzaService->setStartTime($template->getStartTime());
            $pizzaService->setEndTime($template->getEndTime());
            $pizzaService->setSlotDurationInMin($template->getSlotDurationInMin());
            $pizzaService->setCapacityPerSlot($template->getCapacityPerSlot());
            $pizzaService->setBookingOpen(true);
            $entityManager->persist($pizzaService);
            $entityManager->flush();

            // Generate the slots for the service
            $slots = new SlotGenerator();
            $slots = $slots->generateSlots(
                $pizzaService->getStartTime(),
                $pizzaService->getEndTime(),
                $pizzaService->getSlotDurationInMin()
            );

            // Add the slots to the database
            foreach ($slots as $slot) {
                $pizzaServiceSlot = new \App\Entity\PizzaServiceSlot();
                $pizzaServiceSlot->setStartTime($slot['start_time']);
                $pizzaServiceSlot->setCapacity($pizzaService->getCapacityPerSlot());
                $pizzaServiceSlot->setService($pizzaService);
                $pizzaServiceSlot->setCreatedAt(new \DateTimeImmutable());
                $entityManager->persist($pizzaServiceSlot);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_pizza_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pizza_service/new.html.twig', [
            'pizza_service' => $pizzaService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'app_pizza_service_show', methods: ['GET'])]
    public function show(PizzaService $pizzaService): Response
    {
        return $this->render('pizza_service/show.html.twig', [
            'pizza_service' => $pizzaService,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pizza_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PizzaService $pizzaService, EntityManagerInterface $entityManager): Response
    {
        $oldStartTime = $pizzaService->getStartTime();
        $oldEndTime = $pizzaService->getEndTime();
        $oldSlotDuration = $pizzaService->getSlotDurationInMin();
        $oldCapacity = $pizzaService->getCapacityPerSlot();
        
        $form = $this->createForm(PizzaServiceEditType::class, $pizzaService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $deleteSlots = 
                $form->get('startTime')->getData() != $oldStartTime ||
                $form->get('endTime')->getData() != $oldEndTime ||
                $form->get('slotDurationInMin')->getData() !== $oldSlotDuration ||
                $form->get('capacityPerSlot')->getData() !== $oldCapacity;

            // Delete the existing slots for the service and create new ones
            // (except if the modification concerns only the ServiceDate or the BookingOpen status)
            if ($deleteSlots) {
                foreach ($pizzaService->getPizzaServiceSlots() as $slot) {
                    $entityManager->remove($slot);
                }
                $entityManager->flush();

                // Generate the slots for the service
                $slots = new SlotGenerator();
                $slots = $slots->generateSlots(
                    $pizzaService->getStartTime(),
                    $pizzaService->getEndTime(),
                    $pizzaService->getSlotDurationInMin()
                );

                // Add the slots to the database
                foreach ($slots as $slot) {
                    $pizzaServiceSlot = new \App\Entity\PizzaServiceSlot();
                    $pizzaServiceSlot->setStartTime($slot['start_time']);
                    $pizzaServiceSlot->setCapacity($pizzaService->getCapacityPerSlot());
                    $pizzaServiceSlot->setService($pizzaService);
                    $pizzaServiceSlot->setCreatedAt(new \DateTimeImmutable());
                    $entityManager->persist($pizzaServiceSlot);
                }
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_pizza_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pizza_service/edit.html.twig', [
            'pizza_service' => $pizzaService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pizza_service_delete', methods: ['POST'])]
    public function delete(Request $request, PizzaService $pizzaService, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pizzaService->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pizzaService);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pizza_service_index', [], Response::HTTP_SEE_OTHER);
    }
}

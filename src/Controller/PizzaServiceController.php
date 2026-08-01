<?php

namespace App\Controller;

use App\Entity\PizzaService;
use App\Form\PizzaServiceNewType;
use App\Form\PizzaServiceEditType;
use App\Repository\PizzaServiceRepository;
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
            $template = $pizzaService->getTemplate();
            $pizzaService->setStartTime($template->getStartTime());
            $pizzaService->setEndTime($template->getEndTime());
            $pizzaService->setSlotDurationInMin($template->getSlotDurationInMin());
            $pizzaService->setCapacityPerSlot($template->getCapacityPerSlot());
            $pizzaService->setBookingOpen(true);
            $entityManager->persist($pizzaService);
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
        $form = $this->createForm(PizzaServiceEditType::class, $pizzaService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

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

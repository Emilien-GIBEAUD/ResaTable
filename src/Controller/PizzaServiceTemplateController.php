<?php

namespace App\Controller;

use App\Entity\PizzaServiceTemplate;
use App\Form\PizzaServiceTemplateType;
use App\Repository\PizzaServiceTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/pizzas/service/template')]
final class PizzaServiceTemplateController extends AbstractController
{
    #[Route(name: 'app_pizza_service_template_index', methods: ['GET'])]
    public function index(PizzaServiceTemplateRepository $pizzaServiceTemplateRepository): Response
    {
        return $this->render('pizza_service_template/index.html.twig', [
            'pizza_service_templates' => $pizzaServiceTemplateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pizza_service_template_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pizzaServiceTemplate = new PizzaServiceTemplate();
        $form = $this->createForm(PizzaServiceTemplateType::class, $pizzaServiceTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pizzaServiceTemplate);
            $entityManager->flush();

            return $this->redirectToRoute('app_pizza_service_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pizza_service_template/new.html.twig', [
            'pizza_service_template' => $pizzaServiceTemplate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pizza_service_template_show', methods: ['GET'])]
    public function show(PizzaServiceTemplate $pizzaServiceTemplate): Response
    {
        return $this->render('pizza_service_template/show.html.twig', [
            'pizza_service_template' => $pizzaServiceTemplate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pizza_service_template_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PizzaServiceTemplate $pizzaServiceTemplate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PizzaServiceTemplateType::class, $pizzaServiceTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pizza_service_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pizza_service_template/edit.html.twig', [
            'pizza_service_template' => $pizzaServiceTemplate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pizza_service_template_delete', methods: ['POST'])]
    public function delete(Request $request, PizzaServiceTemplate $pizzaServiceTemplate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pizzaServiceTemplate->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pizzaServiceTemplate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pizza_service_template_index', [], Response::HTTP_SEE_OTHER);
    }
}

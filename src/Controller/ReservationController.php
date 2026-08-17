<?php

namespace App\Controller;

use App\Entity\{PizzaService, PizzaServiceSlot, Reservation, ReservationItem, User};
use App\Form\ReservationType;
use App\Repository\PizzaRepository;
use App\Repository\PizzaServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/service/{id}',name: 'app_reservation_service', methods: ['GET'])]
    public function showServiceReservations(PizzaService $service): Response
    {
        return $this->render('reservation/showServiceReservations.html.twig', [
            'pizza_service' => $service,
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager, 
        PizzaRepository $pizzaRepository,
        PizzaServiceRepository $pizzaServiceRepository,
        #[CurrentUser] ?User $user,
        ): Response
    {
    // Get request
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

    // Check inputs
        $errors = [];
        $items = $request->request->all('item');
        if (empty($items)) {
            $errors[] = 'Votre panier est vide. Veuillez sélectionner au moins une pizza.';
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if ($user === null) {
            // Visitor
                if (
                    $form->get('email')->isEmpty() ||
                    $form->get('firstName')->isEmpty() || 
                    $form->get('lastName')->isEmpty() || 
                    $form->get('phone')->isEmpty()) 
                {
                    $errors[] = 'Veuillez renseigner tous vos champs de coordonnées.';
                }
                if (!filter_var($form->get('email')->getData(), FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Veuillez entrer une adresse email valide.';
                }
                $phoneCleaned = preg_replace('/\D/', '', $form->get('phone')->getData());
                $regex = '/^0[67]\d{8}$/';
                if (!preg_match($regex, $phoneCleaned)) {
                    $errors[] = 'Veuillez entrer un numéro de téléphone valide.';
                }
            } else {
            // Admin
                $inputNOK = $form->get('email')->isEmpty() && $form->get('firstName')->isEmpty() && $form->get('lastName')->isEmpty() && $form->get('phone')->isEmpty();
                if ($inputNOK) {
                    $errors[] = 'Renseigner au moins un champ de coordonnées.';
                }
                if (!$form->get('email')->isEmpty() && !filter_var($form->get('email')->getData(), FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'L\'adresse email n\'est pas valide.';
                }
                if (!$form->get('phone')->isEmpty()) {
                    $phoneCleaned = preg_replace('/\D/', '', $form->get('phone')->getData());
                    $regex = '/^0[67]\d{8}$/';
                    if (!preg_match($regex, $phoneCleaned)) {
                        $errors[] = 'Le numéro de téléphone n\'est pas valide.';
                    }
                }
            }

            if (!empty($errors)) {
                $formerItems = [];
                foreach ($items as $pizzaId => $quantity) {
                    $pizza = $pizzaRepository->find($pizzaId);
                    $formerItems[] = [
                        'id' => $pizzaId,
                        'name' => $pizza->getName(),
                        'price' => $pizza->getPrice(),
                        'quantity' => (int) $quantity,
                    ];
                }

                $showActive = 1;
                $sort = 'price';
                $direction = 'asc';
                return $this->render('pizza/index.html.twig', [
                    'pizzas' => $pizzaRepository->findByFilters($showActive, $sort, $direction),
                    'pizzasSelect' => $pizzaRepository->findByFilters(1, 'price', 'asc'),
                    'services' => $pizzaServiceRepository->findAfterTomorrow(),
                    'csrf_token' => $this->container
                        ->get('security.csrf.token_manager')
                        ->getToken('reservation')
                        ->getValue(),
                    'errors' => $errors,
                    'formerItems' => $formerItems,
                    'selectedService' => $request->request->get('serviceDate'),
                    'selectedSlot' => $request->request->get('serviceSlot'),
                    'reservationData' => $request->request->all('reservation'),
                ]);
            }

        // Add reservation
            $createdAt = new \DateTimeImmutable();
            if ($user === null) {
            // Visitor
                $reservation->setStatus("PENDING");
                $reservation->setAccessToken(bin2hex(random_bytes(32)));
                $reservation->setConfirmationExpiresAt($createdAt->modify('+15 minutes'));
            } else {
            // Admin
                $reservation->setStatus("CONFIRMED");
            }
            $reservation->setCreatedAt($createdAt);
            $slotId = $request->request->get('serviceSlot');
            $slot = $entityManager->getRepository(PizzaServiceSlot::class)->find($slotId);
            $reservation->setSlot($slot);
            $entityManager->persist($reservation);

        // Add items to reservation
            $reservationQuantity = 0;
            foreach ($items as $pizzaId => $quantity) {
                $pizza = $entityManager->getRepository(\App\Entity\Pizza::class)->find($pizzaId);
                $reservationItem = new ReservationItem();
                $reservationItem->setPizzaName($pizza->getName());
                $reservationItem->setUnitPrice($pizza->getPrice());
                $reservationItem->setQuantity($quantity);
                $reservationItem->setReservation($reservation);
                $entityManager->persist($reservationItem);
                $reservationQuantity += $quantity;
            }

        // Last check of the capacity before flushing the reservation and items to the database
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
            if ($reservationQuantity > $availableCapacity) {
                $errors[] = "La capacité du créneau n'est plus disponible. Une réservation sur ce créneau vient d'être effectuée. Veuillez choisir un autre créneau.";

                $formerItems = [];
                foreach ($items as $pizzaId => $quantity) {
                    $pizza = $pizzaRepository->find($pizzaId);
                    $formerItems[] = [
                        'id' => $pizzaId,
                        'name' => $pizza->getName(),
                        'price' => $pizza->getPrice(),
                        'quantity' => (int) $quantity,
                    ];
                }

                $showActive = 1;
                $sort = 'price';
                $direction = 'asc';
                return $this->render('pizza/index.html.twig', [
                    'pizzas' => $pizzaRepository->findByFilters($showActive, $sort, $direction),
                    'pizzasSelect' => $pizzaRepository->findByFilters(1, 'price', 'asc'),
                    'services' => $pizzaServiceRepository->findAfterTomorrow(),
                    'csrf_token' => $this->container
                        ->get('security.csrf.token_manager')
                        ->getToken('reservation')
                        ->getValue(),
                    'errors' => $errors,
                    'formerItems' => $formerItems,
                    'selectedService' => $request->request->get('serviceDate'),
                    'selectedSlot' => $request->request->get('serviceSlot'),
                    'reservationData' => $request->request->all('reservation'),
                ]);
            }
            $entityManager->flush();

        // Redirect the visitor to the confirmation page
            if ($user === null) {
                // return $this->redirectToRoute('app_reservation_wait_confirmation', ['reservation' => $reservation,...], Response::HTTP_SEE_OTHER);    // route à faire
                return $this->redirectToRoute('app_home');      // redirect fictif
            }

        // Redirect the admin to the service slots page
            $service = $reservation->getSlot()->getService();
            return $this->redirectToRoute('app_slot_show_services_slots', [
                'id' => $service->getId(),
            ], Response::HTTP_SEE_OTHER);
        }
        return $this->redirectToRoute('app_home');  // que retourner ?
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }
}

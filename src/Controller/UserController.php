<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateAdminType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }
    #[Route(path: '/createAdmin', name: 'app_create_admin')]
    function createAdmin(Request $request, UserRepository $userRepository): Response
    {
        if ($userRepository->count([]) > 0) {
            return $this->redirectToRoute('app_login');
        }
        $user = new User();
        $form = $this->createForm(CreateAdminType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $form->get('password')->getData();
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $user->setRoles(['ROLE_ADMIN']);
            $user->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/createAdmin.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(
        AuthenticationUtils $authenticationUtils, 
        UserRepository $userRepository
        ): Response
    {
        if ($userRepository->count([]) === 0) {
            return $this->redirectToRoute('app_create_admin');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

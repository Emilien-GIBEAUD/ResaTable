<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Crée l\'administrateur',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Crée l\'administrateur.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Création de l\'administrateur');
        $countAdmin = $this->entityManager->getRepository(User::class)->count([]);
        if ($countAdmin > 0) {
            $io->error('Un administrateur existe déjà. Cette commande ne peut être exécutée qu\'une seule fois.');
            return Command::FAILURE;
        }

        $email = $io->ask('Adresse e-mail :');
        $password = $io->askHidden('Mot de passe');
        $passwordConfirmation = $io->askHidden('Confirmation du mot de passe');

        if($email===null || $password===null || $passwordConfirmation===null){
            $io->error('Tous les champs sont obligatoires.');
            return Command::FAILURE;
        }

        if ($password !== $passwordConfirmation) {
            $io->error('Les mots de passe ne correspondent pas.');
            return Command::FAILURE;
        }

        $user = new User();
        $user->setEmail($email);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Administrateur créé avec succès.');

        return Command::SUCCESS;
    }
}

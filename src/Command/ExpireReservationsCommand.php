<?php

namespace App\Command;

use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:expire-reservations',
    description: 'Expire les réservations PENDING dont le délai de confirmation est dépassé.',
)]
class ExpireReservationsCommand extends Command
{
    public function __construct(
        private ReservationRepository $reservationRepository,
        private EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Expire les réservations PENDING dont le délai de confirmation est dépassé.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTimeImmutable();

        $reservations = $this->reservationRepository->findPendingExpired($now);

        foreach ($reservations as $reservation) {
            $reservation->setStatus('EXPIRED');
        }

        $this->entityManager->flush();

        $output->writeln(
            sprintf('%d réservation(s) expirée(s).', count($reservations))
        );

        return Command::SUCCESS;
    }
}

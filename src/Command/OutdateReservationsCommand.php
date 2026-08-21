<?php

namespace App\Command;

use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:outdate-reservations',
    description: 'Passe les réservations à l\'état OUTDATED quand la date de service est passée.',
)]
class OutdateReservationsCommand extends Command
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
        $this->setDescription('Passe les réservations à l\'état OUTDATED quand la date de service est passée.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));

        $reservations = $this->reservationRepository->findConfirmedOutdated($now);

        foreach ($reservations as $reservation) {
            $reservation->setStatus('OUTDATED');
            if (empty($reservation->getEmail()) || $reservation->getEmail() === 'non saisi') {
                $reservation->setEmail('non saisi');
            } else {
                $reservation->setEmail('supprimé');
            }

            if (empty($reservation->getPhone()) || $reservation->getPhone() === 'non saisi') {
                $reservation->setPhone('non saisi');
            } else {
                $reservation->setPhone('supprimé');
            }

            if (empty($reservation->getFirstName()) || $reservation->getFirstName() === 'non saisi') {
                $reservation->setFirstName('non saisi');
            }

            if (empty($reservation->getLastName()) || $reservation->getLastName() === 'non saisi') {
                $reservation->setLastName('non saisi');
            }
        }

        $this->entityManager->flush();

        $output->writeln(
            sprintf('%d réservation(s) passée(s).', count($reservations))
        );

        return Command::SUCCESS;
    }
}

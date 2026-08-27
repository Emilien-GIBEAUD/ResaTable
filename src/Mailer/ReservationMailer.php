<?php

namespace App\Mailer;

use App\Entity\Reservation;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface; 
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

final class ReservationMailer
{
    public function __construct(
        private MailerInterface $mailer,
    ) {
    }
    public function sendAskingConfirmation(Reservation $reservation):void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('mail@restaurant.fr', 'Restaurant Bidule'))
            ->to(new Address($reservation->getEmail(), $reservation->getFirstname() . ' ' . $reservation->getLastName()))
            ->subject('Réservation du ' . $reservation->getSlot()->getService()->getServiceDate()->format('d/m/Y') . ' (attente confirmation)')
            ->htmlTemplate('emails/reservation/askConfirmation.html.twig')
            ->textTemplate('emails/reservation/askConfirmation.txt.twig') 
            ->addPart(
                (new DataPart(
                    new File('/app/public/images/logo_mail.png'), 
                    'logo',
                    'image/png'
                ))->asInline())
            ->context([
                'reservation' => $reservation,
            ]);
        $this->mailer->send($email);
    }

    public function sendConfirmation(Reservation $reservation):void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('mail@restaurant.fr', 'Restaurant Bidule'))
            ->to(new Address($reservation->getEmail(), $reservation->getFirstname() . ' ' . $reservation->getLastName()))
            ->subject('Réservation du ' . $reservation->getSlot()->getService()->getServiceDate()->format('d/m/Y') . ' (confirmée)')
            ->htmlTemplate('emails/reservation/confirmation.html.twig')
            ->textTemplate('emails/reservation/confirmation.txt.twig') 
            ->addPart(
                (new DataPart(
                    new File('/app/public/images/logo_mail.png'), 
                    'logo',
                    'image/png'
                ))->asInline())
            ->context([
                'reservation' => $reservation,
            ]);
        $this->mailer->send($email);
    }
}
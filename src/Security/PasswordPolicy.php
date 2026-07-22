<?php

namespace App\Security;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

final class PasswordPolicy
{
    /**
     * @return list<Constraint>
     */
    public static function constraints(): array
    {
        return [
            new NotBlank(message: 'Veuillez saisir un mot de passe.'),
            new Length(
                min: 12,
                max: 50,
                minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                maxMessage: 'Le mot de passe doit contenir au maximum {{ limit }} caractères.',
            ),
            new NotCompromisedPassword(
                message: 'Ce mot de passe est connu dans des fuites de données. Choisissez-en un autre.',
                skipOnError: true,
            ),
        ];
    }
}

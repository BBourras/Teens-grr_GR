<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Vérifications de l'état du compte lors de l'authentification.
 *
 * Double protection avec les Voters :
 * - UserChecker bloque à la CONNEXION (checkPreAuth)
 * - Voters bloquent à l'ACTION
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * Vérifie l'état du compte AVANT la validation du mot de passe.
     * Bloque les comptes bannis avant même de vérifier le mot de passe.
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (in_array('ROLE_BANNED', $user->getRoles(), true)) {
            throw new CustomUserMessageAccountStatusException(
                'Votre compte a été suspendu. Contactez un administrateur.'
            );
        }

        // À décommenter quand la confirmation d'email sera implémentée
        // if (!$user->isVerified()) {
        //     throw new CustomUserMessageAccountStatusException(
        //         'Votre adresse email n\'a pas encore été confirmée.'
        //     );
        // }
    }

    /**
     * Vérifie l'état du compte APRÈS la validation du mot de passe.
     * Intentionnellement vide pour l'instant.
     */
    public function checkPostAuth(UserInterface $user): void
    {
        // Cas d'usage futurs : mot de passe expiré, 2FA obligatoire, etc.
    }
}
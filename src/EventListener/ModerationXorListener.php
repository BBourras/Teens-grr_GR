<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Contract\XorTargetInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;

/**
 * Listener Doctrine qui applique la contrainte XOR sur les entités
 * qui implémentent XorTargetInterface (Report et ModerationActionLog).
 *
 * Appelée automatiquement avant chaque persistance pour garantir
 * qu'un signalement ou un log cible exactement un Post OU un Comment.
 *
 * La logique métier est factorisée dans XorTargetTrait.
 */
class ModerationXorListener
{
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof XorTargetInterface) {
            $entity->assertExactlyOneTarget();
        }
    }
}
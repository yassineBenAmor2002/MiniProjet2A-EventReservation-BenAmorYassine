<?php

namespace App\Repository;

use App\Entity\WebauthnCredential;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebauthnCredential>
 *
 * @method WebauthnCredential|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebauthnCredential|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebauthnCredential[]    findAll()
 * @method WebauthnCredential[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebauthnCredentialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebauthnCredential::class);
    }

    // Ici tu peux ajouter des méthodes personnalisées, par exemple :
    // public function findByUser(User $user): array { ... }
}
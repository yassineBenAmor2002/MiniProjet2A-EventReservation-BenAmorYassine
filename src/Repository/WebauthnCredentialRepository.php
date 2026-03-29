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

    public function findOneByCredentialId(string $credentialId): ?WebauthnCredential
    {
        return $this->findOneBy(['credentialId' => $credentialId]);
    }

    public function save(WebauthnCredential $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
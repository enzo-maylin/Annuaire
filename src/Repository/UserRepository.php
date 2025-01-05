<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findAllVisibilityTrue() : array
    {
        return $this->findBy(["visibility" => true], ["login" => "DESC"]);
    }
    public function findAll(): array
    {
        return $this->findBy([], ["login" => "DESC"]);
    }

    public function findUserPaginated(int $page, bool $isAdmin, int $limit = 12): array
    {
        if ($limit <=0) {
            //si la valeur de limit est absurde, on la met à 12
            $limit = 12;
        }

        $result = [];

        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('user')
            ->from('App\Entity\User', 'user')
            ->setMaxResults($limit)
            ->setFirstResult($page * $limit);

        // on applique le where que pour les non Admin
        if(!$isAdmin) {
            $query->where('user.visibility = true');
        }

        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();

        // on vérifie qu'on a des données
        if(empty($data)){
            return $result;
        }

        // on calcule le nombre de pages
        $pages = ceil($paginator->count() / $limit)-1;

        // on remplit le tableau
        $result['data'] = $data;
        $result['pages'] = $pages;
        $result['page'] = $page;

        return $result;
    }

    public function findByCode($code) : ?User {
        return $this->findOneBy(["code" => $code]);
    }

}

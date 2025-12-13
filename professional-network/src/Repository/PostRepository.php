<?php
namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $reg) {
        parent::__construct($reg, Post::class);
    }

    /**
     * Получить айди постов для ленты пользователя с ранжированием
     * NOTE: пример упрощён — вы можете заменить ранжирование на более сложную формулу
     */
    public function getFeedIdsForUser(User $user, int $limit = 50): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id')
            ->leftJoin('p.author', 'a')
            // relation_score: если автор в связях — позже добавим JOIN connections
            ->where('p.visibility = :public')
            ->setParameter('public', 'public')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit);

        return array_column($qb->getQuery()->getArrayResult(), 'id');
    }
}

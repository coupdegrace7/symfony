<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class SearchService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function searchUsersByFullText(string $query, int $limit = 20): array
    {
        // Простой LIKE-based fallback; при Postgres замените на to_tsquery + ts_rank
        $conn = $this->em->getConnection();
        $sql = 'SELECT id, first_name, last_name, bio
                FROM users
                WHERE first_name ILIKE :q OR last_name ILIKE :q OR bio ILIKE :q
                LIMIT :limit';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('q', '%'.$query.'%');
        $stmt->bindValue('limit', $limit);
        $stmt->executeQuery();
        return $stmt->fetchAllAssociative();
    }
}

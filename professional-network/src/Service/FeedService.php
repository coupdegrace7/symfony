<?php
namespace App\Service;

use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use App\Entity\User;

class FeedService
{
    public function __construct(
        private PostRepository $postRepository,
        private EntityManagerInterface $em,
        private CacheInterface $cache // configure Redis cache pool
    ){}

    public function getFeed(User $user, int $limit = 50): array
    {
        $cacheKey = sprintf('feed:user:%s', $user->getId());
        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($user, $limit) {
            $item->expiresAfter(30); // TTL 30s, настраиваемо
            $ids = $this->postRepository->getFeedIdsForUser($user, $limit);
            if (empty($ids)) return [];
            $posts = $this->em->getRepository(\App\Entity\Post::class)->findBy(['id' => $ids]);
            // optionally sort by original id order
            return $posts;
        });
    }

    public function invalidateUserFeed(User $user): void {
        $this->cache->delete(sprintf('feed:user:%s', $user->getId()));
    }
}

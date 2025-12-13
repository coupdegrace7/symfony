<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use App\Message\ProcessAttachmentMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Service\NotificationService;

#[Route('/api/posts')]
class PostController extends AbstractController
{
    #[Route('', name:'api_posts_create', methods:['POST'])]
    public function create(Request $req, EntityManagerInterface $em, MessageBusInterface $bus, NotificationService $notifService): JsonResponse
    {
        $data = json_decode($req->getContent(), true);
        // простой: получить автора по id
        $author = $em->getRepository(\App\Entity\User::class)->find($data['authorId']);
        if (!$author) return $this->json(['error'=>'Author not found'], 400);

        $post = new Post($author, $data['text'] ?? '');
        $post->setVisibility($data['visibility'] ?? 'public');
        $em->persist($post);
        $em->flush();

        // если есть attachments — запушим задачу обработки
        if (!empty($data['attachments']) && is_array($data['attachments'])) {
            foreach ($data['attachments'] as $filePath) {
                $bus->dispatch(new ProcessAttachmentMessage($filePath, $post->getId()));
            }
        }

        // нотификация подписчикам (обобщённо — можно найти всех, кто подписан на автора)
        // пример: уведомим самого автора (demo)
        $notifService->notify($author, 'post.created', ['postId' => $post->getId(), 'text' => substr($post->getText(),0,200)]);

        return $this->json(['id' => $post->getId()], 201);
    }

    #[Route('/{id}', name:'api_posts_get', methods:['GET'])]
    public function get(Post $post) {
        return $this->json([
            'id' => $post->getId(),
            'author' => $post->getAuthor()->getId(),
            'text' => $post->getText(),
            'createdAt' => $post->getCreatedAt()->format('c'),
        ]);
    }
}

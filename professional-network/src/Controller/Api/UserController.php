<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/users')]
class UserController extends AbstractController
{
    #[Route('', name:'api_users_create', methods:['POST'])]
    public function create(Request $req, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($req->getContent(), true);
        $user = new User($data['email'], $data['firstName'] ?? '', $data['lastName'] ?? '');
        $user->setBio($data['bio'] ?? null);
        $em->persist($user);
        $em->flush();
        return $this->json(['id' => $user->getId()], 201);
    }

    #[Route('/{id}', name:'api_users_get', methods:['GET'])]
    public function get(User $user, SerializerInterface $serializer) {
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstName'=> $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'bio' => $user->getBio(),
        ]);
    }

    #[Route('/{id}', name:'api_users_update', methods:['PUT'])]
    public function update(Request $req, User $user, EntityManagerInterface $em) {
        $data = json_decode($req->getContent(), true);
        if (isset($data['firstName'])) $user->setFirstName($data['firstName']);
        if (isset($data['lastName'])) $user->setLastName($data['lastName']);
        if (isset($data['bio'])) $user->setBio($data['bio']);
        $em->persist($user);
        $em->flush();
        return $this->json(['status'=>'ok']);
    }
}

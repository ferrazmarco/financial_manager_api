<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


#[Route("/api", name: "api_")]
class UserController extends AbstractController
{
    #[Route('/users', name: 'app_user', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        return $this->json([
            'data' => $userRepository->findAll()
        ]);
    }

    #[Route('/users/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(int $id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);
        if (!$user) throw $this->createNotFoundException();

        return $this->json([
            'data' => $user
        ]);
    }

    #[Route('/users/{id}', name: 'app_user_update', methods: ['PATCH', 'PUT'])]
    public function update(int $id, UserRepository $userRepository, Request $request): JsonResponse
    {
        $user = $userRepository->find($id);
        if (!$user) throw $this->createNotFoundException();

        $decoded = json_decode($request->getContent());
        $user->setRoles($decoded->roles);
        $user->setUsername($decoded->username);
        $user->setPassword($decoded->password);
        $userRepository->add($user, true);

        return $this->json([
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }
}

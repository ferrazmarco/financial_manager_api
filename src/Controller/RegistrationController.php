<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

#[Route("/api", name: "api_")]
class RegistrationController extends AbstractController
{   
    #[Route('/registration', name: 'app_registration', methods: ["POST"])]
    public function index(UserRepository $userRepository, Request $request, User $user): JsonResponse
    {   
        $decoded = json_decode($request->getContent());

        $user->setUsername($decoded->username);
        $user->setPassword($decoded->password);
        $userRepository->add($user, true);

        return $this->json([
            'message' => 'Registered Successfully',
            'data' => $user
        ]);
    }
}

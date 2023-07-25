<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

#[Route("/api", name: "api_")]
class RegistrationController extends AbstractController
{   
    #[Route('/registration', name: 'app_registration', methods: ["POST"])]
    public function index(UserRepository $userRepository, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {   
        $decoded = json_decode($request->getContent());
        $plainPassword = $decoded->password;

        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plainPassword
        );

        $user->setUsername($decoded->username);
        $user->setPassword($hashedPassword);
        $userRepository->add($user, true);

        return $this->json(['message' => 'Registered Successfully']);
    }
}

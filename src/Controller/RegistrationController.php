<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\CreateUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

#[Route("/api", name: "api_")]
class RegistrationController extends AbstractApiController
{   
    #[Route('/registration', name: 'app_registration', methods: ["POST"])]
    public function index(UserRepository $userRepository, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {   
        $user = new User;
        $form = $this->createForm(CreateUserType::class, $user);
        $this->processForm($request, $form);
        
        $plainPassword = $form->getData()->getPassword();
        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
        $form->getData()->setPassword($hashedPassword);
        $userRepository->add($user, true);
        
        return $this->json([
            'message' => 'Registered Successfully',
            'data' => $user],
            JsonResponse::HTTP_CREATED,
            [],
            ['groups' => 'main']
        );
    }
}

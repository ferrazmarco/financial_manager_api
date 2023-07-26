<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\User;
use App\Form\CreateUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route("/api", name: "api_")]
class RegistrationController extends AbstractApiController
{   
    #[Route('/registration', name: 'app_registration', methods: ["POST"])]
    public function index(UserRepository $userRepository, Request $request, User $user, ValidatorInterface $validator): JsonResponse
    {   
        $form = $this->createForm(CreateUserType::class, $user);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            return $this->json([
                'data' => $errors
            ], 400);
        };

        $userRepository->add($user, true);
        return $this->json([
            'message' => 'Registered Successfully',
            'data' => $user
        ]);
    }
}

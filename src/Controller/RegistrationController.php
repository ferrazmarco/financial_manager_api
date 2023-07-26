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
class RegistrationController extends AbstractController
{   
    #[Route('/registration', name: 'app_registration', methods: ["POST"])]
    public function index(UserRepository $userRepository, Request $request, User $user, ValidatorInterface $validator): JsonResponse
    {   
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(CreateUserType::class, $user);
        $form->submit($data);

        $errors = $form->getErrors(true, false);    
        
        if (!$form->isValid()) {
            # TODO: refatorar essa forma de pegar os erros para returnar o json
            $errors = $form->getErrors(true, false);
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

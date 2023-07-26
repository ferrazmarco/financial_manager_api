<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\EditUserType;

#[Route("/api", name: "api_")]
class UserController extends AbstractApiController
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
        $form = $this->createForm(EditUserType::class, $user);
        $this->processForm($request, $form);

        // ESTA APAGANDO OS DADOS QUE EU NAO ENVIO NO FORM
        // PESQUISAR COMO EVITAR QUE ISSO ACONTECA
        // dd($form->getData());
        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            return $this->json([
                'data' => $errors
            ], 400);
        };

        $userRepository->add($user, true);
        return $this->json([
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }
}

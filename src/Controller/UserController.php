<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\EditUserType;
use App\Security\Voter\UserVoter;
use App\Entity\User;

#[Route("/api", name: "api_")]
class UserController extends AbstractApiController
{
    #[Route('/users', name: 'app_user', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::VIEW);

        return $this->json([
            'data' => $userRepository->findAll()
        ]);
    }

    #[Route('/users/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(int $id, UserRepository $userRepository): JsonResponse
    {   
        $user = $userRepository->find($id);
        if (!$user) throw $this->createNotFoundException();
        $this->denyAccessUnlessGranted(UserVoter::SHOW, $user);

        return $this->json([
            'data' => $user
        ]);
    }

    #[Route('/users/{id}', name: 'app_user_update', methods: ['PATCH', 'PUT'])]
    public function update(int $id, UserRepository $userRepository, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {   
        $user = $userRepository->find($id);
        if (!$user) throw $this->createNotFoundException();
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);

        $form = $this->createForm(EditUserType::class, $user);
        $this->processForm($request, $form, false);

        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            return $this->json([
                'data' => $errors
            ], 400);
        };

        if ($request->getPayload()->has('password')) {
            $plainPassword = $form->getData()->getPassword();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $form->getData()->setPassword($hashedPassword);
        }

        $userRepository->add($user, true);

        return $this->json([
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    #[Route('/users/{id}', name: 'app_user_destroy', methods: ['DELETE'])]
    public function destroy(int $id, UserRepository $userRepository, Request $request): JsonResponse
    {
        $user = $userRepository->find($id);
        if (!$user) throw $this->createNotFoundException();
        $this->denyAccessUnlessGranted(UserVoter::DESTROY, $user);
        
        $userRepository->remove($user, true);

        return $this->json([
            'message' => 'User destroyed successfully'
        ]);
    }
}

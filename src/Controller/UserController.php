<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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
        return $this->json(
            [
                'data' => $userRepository->findAll()
            ],
            JsonResponse::HTTP_OK,
            [],
            ['groups' => ['main', 'groups_details']]
        );
    }

    #[Route('/users/{id}', name: 'app_user_show', methods: ['GET'])]
    #[IsGranted('show', 'user')]
    public function show(User $user, UserRepository $userRepository): JsonResponse
    {
        return $this->json(
            [
                'data' => $user
            ],
            JsonResponse::HTTP_OK,
            [],
            ['groups' => ['main', 'groups_details']]
        );
    }

    #[Route('/users/{id}', name: 'app_user_update', methods: ['PATCH', 'PUT'])]
    #[IsGranted('edit', 'user')]
    public function update(User $user, UserRepository $userRepository, Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $form = $this->createForm(EditUserType::class, $user);
        $this->processForm($request, $form, false);

        if ($request->getPayload()->has('password')) {
            $plainPassword = $form->getData()->getPassword();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $form->getData()->setPassword($hashedPassword);
        }

        $userRepository->add($user, true);

        return $this->json(
            [
                'message' => 'User updated successfully',
                'data' => $user
            ],
            JsonResponse::HTTP_OK,
            [],
            ['groups' => ['main', 'groups_details']]
        );
    }

    #[Route('/users/{id}', name: 'app_user_destroy', methods: ['DELETE'])]
    #[IsGranted('destroy', 'user')]
    public function destroy(User $user, UserRepository $userRepository, Request $request): JsonResponse
    {
        $userRepository->remove($user, true);

        return $this->json([
            'message' => 'User destroyed successfully'
        ]);
    }
}

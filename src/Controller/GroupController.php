<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupRepository;
use App\Entity\Group;
use App\Form\GroupType;

#[Route("/api", name: "api_")]
class GroupController extends AbstractApiController
{
    #[Route('/groups', name: 'app_group', methods: ['GET'])]
    public function index(GroupRepository $groupRepository): JsonResponse
    {   
        return $this->json([
            'data' => $groupRepository->findAll()], 
            JsonResponse::HTTP_OK, 
            [], 
            ['groups' => ['main', 'users_details']]
        );
    }

    #[Route('/groups/{id}', name: 'app_group_show', methods: ['GET'])]
    public function show(Group $group): JsonResponse
    {
        return $this->json([
            'data' => $group],
            JsonResponse::HTTP_OK, 
            [], 
            ['groups' => ['main', 'users_details']]
        );
    }

    #[Route('/groups', name: 'app_group_create', methods: ['POST'])]
    public function create(GroupRepository $groupRepository, Request $request): JsonResponse
    {
        $group = new Group;
        $form = $this->createForm(GroupType::class, $group);
        $this->processForm($request, $form);
        $form->getData()->addUser($this->getUser());
        $groupRepository->add($group, true);

        return $this->json([
            'data' => $group],
            JsonResponse::HTTP_CREATED, 
            [], 
            ['groups' => ['main', 'users_details']]
        );
    }

    #[Route('/groups/{id}', name: 'app_group_update', methods: ['PUT', 'PATCH'])]
    public function update(Group $group, GroupRepository $groupRepository, Request $request): JsonResponse
    {
        $form = $this->createForm(GroupType::class, $group);
        $this->processForm($request, $form, false);
        $groupRepository->add($group, true);

        return $this->json([
            'message' => 'Group updated successfully',
            'data' => $group],
            JsonResponse::HTTP_OK,
            [], 
            ['groups' => ['main', 'users_details']]
        );
    }

    #[Route('/groups/{id}', name: 'app_group_destroy', methods: ['DELETE'])]
    public function destroy(Group $group, GroupRepository $groupRepository, Request $request): JsonResponse
    {
        $groupRepository->remove($group, true);

        return $this->json([
            'message' => 'Group deleted successfully',
        ]);
    }
}

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
            'data' => $groupRepository->findAll()
        ]);
    }

    #[Route('/groups/{id}', name: 'app_group_show', methods: ['GET'])]
    public function show(Group $group): JsonResponse
    {
        return $this->json([
            'data' => $group
        ]);
    }

    #[Route('/groups', name: 'app_group_create', methods: ['POST'])]
    public function create(GroupRepository $groupRepository, Request $request): JsonResponse
    {
        $group = new Group;
        $form = $this->createForm(GroupType::class, $group);
        $this->processForm($request, $form);
        // $form->getData()->addUser($this->getUser());
        $groupRepository->add($group, true);

        return $this->json([
            'data' => $group
        ]);
    }
}

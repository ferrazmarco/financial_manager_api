<?php

namespace App\Controller;

use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
class ExpenseController extends AbstractController
{
    #[Route('/expenses/{id}', name: 'app_expense_destroy', methods: ['DELETE'])]
    #[IsGranted('destroy', 'expense')]
    public function destroy(Expense $expense, ExpenseRepository $expenseRepository): JsonResponse
    {
        $expenseRepository->remove($expense, true);

        return $this->json([
            'message' => 'Expense deleted successfully',
        ]);
    }
}

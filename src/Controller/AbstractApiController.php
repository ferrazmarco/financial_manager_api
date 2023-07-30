<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Form as FormInterface;
use App\Service\ServiceException;

class AbstractApiController extends AbstractController
{
    public function processForm(Request $request, FormInterface $form, bool $clearMissing = true): void
    {
        $data = json_decode($request->getContent(), true);
        $form->submit($data, $clearMissing);

        if (!$form->isValid()) {
            $errors = json_encode($this->getErrorsFromForm($form));
            throw new ServiceException(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $errors);
        }
    }

    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Form as FormInterface;

class AbstractApiController extends AbstractController
{
    public function processForm(Request $request, FormInterface $form, bool $clearMissing = false): void
    {
        $data = json_decode($request->getContent(), true);
        $form->submit($data, $clearMissing);

        # TODO: verificar uma forma de ja validar o formulario
        # e retornar o erro aqui nessa funcao mesmo
        // if (!$form->isValid()) {
        //     $errors = $this->getErrorsFromForm($form);
        //     return $this->json([
        //         'data' => $errors
        //     ], 400);
        // };
    }

    public function getErrorsFromForm(FormInterface $form): array
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

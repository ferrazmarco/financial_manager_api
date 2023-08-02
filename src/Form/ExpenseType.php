<?php

namespace App\Form;

use App\Entity\Expense;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, NumberType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ExpenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        $expense = $options['data'];

        $builder
        ->add('description', TextType::class)
        ->add('value', NumberType::class)
        ->add('sharedWith', EntityType::class, [
            'class' => User::class,
            'multiple' => true,
            'choices' => $expense->getGroup()->getUsers()
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expense::class,
            'csrf_protection' => false,
        ]);
    }
}

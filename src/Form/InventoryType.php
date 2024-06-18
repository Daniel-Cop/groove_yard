<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Condition;
use App\Entity\Intention;
use App\Entity\Inventory;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InventoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', EntityType::class, [
                'class' => Condition::class,
                'choice_label' => 'name',
            ])
            ->add('intention', EntityType::class, [
                'class' => Intention::class,
                'choice_label' => 'name',
            ])
            ->add('Add', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inventory::class,
        ]);
    }
}

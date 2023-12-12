<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\RoleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private array $roles;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roles = $roleRepository->findAll();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $rolesArray = null;

        foreach($this->roles as $role)
        {
            $rolesArray[$role->getName()] = $role->getName();
        }

        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices'=>$rolesArray,
                'multiple'=>true
            ])
            ->add('password')
            ->add('firstName')
            ->add('lastName')
            ->add('isVerified')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

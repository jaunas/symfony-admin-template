<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter Email Address...'
                ]
            ])
            ->add('password',PasswordType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Password'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_token_id' => 'authenticate',
            'csrf_field_name' => 'csrf_token',
        ]);
    }
}

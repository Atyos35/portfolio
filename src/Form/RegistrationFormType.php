<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter an email address.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['class' => 'password-field'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters.',
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/\d+/',
                        'message' => 'Your password must contain at least one number.',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your first name.',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your last name.',
                    ]),
                ],
            ])
            ->add('job', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your job.',
                    ]),
                ],
            ])
            ->add('linkedin', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your LinkedIn profile.',
                    ]),
                ],
            ])
            ->add('age', NumberType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your age.',
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your city.',
                    ]),
                ],
            ])
            ->add('phone', TelType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your phone number.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true,
            'csrf_protection' => true,
        ]);
    }
}
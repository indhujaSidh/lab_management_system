<?php

namespace App\Form\Type\web;

use App\Entity\User\AppUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

class userRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',TextType::class)
            ->add('lastName',TextType::class)
            ->add('contactNumber',TextType::class,[
                'attr' => [
                    'pattern' => '^([0-9]{10})$'
                ]
            ])
            ->add('email',EmailType::class,[
                'required' => true,
                'constraints' => [
                    new Email([
                        'message' => 'Please enter a valid email address.',
                        'mode' => 'html5',
                    ]),
                ],
            ])
            ->add('nic',TextType::class,[
                'attr' => [
                    'pattern' => '^\d{9}[xXvV]|\d{12}$',
                ],
                'required' => false
            ])
            ->add('gender',ChoiceType::class,[
                'choices' => [
                    'Female' => 'Female',
                    'Male' => 'Male'
                ],
                'placeholder' => 'Select an option'
            ])
            ->add('dob', DateType::class,[
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
                'input_format' => 'Y-m-d H:i:s',
                'input' => 'datetime_immutable',
                'attr' => [
                    'class' => 'js-datepicker',
                    'max' => date('Y-m-d')
                ],

            ])

            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'first_options' => [
                    'label' => 'New Password',
                ],
                'second_options' => [
                    'label' => 'Retype New Password',
                ],
                'required' => true
            ))

            ->add('submit',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppUser::class,
        ]);

    }

}
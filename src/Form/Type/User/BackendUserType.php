<?php

namespace App\Form\Type\User;


use App\Entity\User\AppUser;
use App\Entity\User\UserRole;
use App\Repository\User\UserRoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;

class BackendUserType extends AbstractType
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
                'required' => true
            ])
            ->add('gender',ChoiceType::class,[
                'choices' => [
                    'Female' => 'Female',
                    'Male' => 'Male',
                    'Other' => 'Other,'
                ],
                'placeholder' => 'Select an option'
            ])
            ->add('role',EntityType::class,[
                'class'=> UserRole::class,
                'placeholder' => 'Select an option',
                'choice_label' => 'name',
                'query_builder' => function(UserRoleRepository $btr){
                    return $btr->createQueryBuilder('object')
                        ->where('object.isDeleted=:isDeleted')
                        ->andWhere('object.isActive=:isActive')
                        ->setParameter('isDeleted',false)
                        ->setParameter('isActive',true)
                        ->orderBy('object.name','ASC');
                },
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

            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
                $form = $event->getForm();
                $isNew = $options['isNew'];
                if($isNew){
                    $form
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
                        ));
                }

            })

            ->add('submit',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppUser::class,
            'isNew' => true,
        ]);

        $resolver->setAllowedTypes('isNew', 'bool');
    }

}
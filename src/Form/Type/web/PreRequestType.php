<?php

namespace App\Form\Type\web;

use App\Entity\Appointment\PreRequests;
use App\Entity\User\Doctor;
use App\Repository\User\DoctorRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;

class PreRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('contactNo', TextType::class, [
                'attr' => [
                    'pattern' => '^([0-9]{10})$'
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new Email([
                        'message' => 'Please enter a valid email address.',
                        'mode' => 'html5',
                    ]),
                ],
            ])
            ->add('preferredDate', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'html5' => true,
                'input_format' => 'Y-m-d H:i:s',
                'input' => 'datetime_immutable',
                'attr' => [
                    'class' => 'js-datepicker',
                    'max' => date('Y-m-d')
                ],

            ])
            ->add('testsInfo', TextareaType::class, [
                'attr' => [
                    'pattern' => '^([0-9]{10})$'
                ]
            ])
            ->add('prefferedTime', ChoiceType::class, [
                'choices' => [
                    '7AM - 8AM' => '7AM - 8AM',
                    '8AM - 9AM' => '8AM - 9AM',
                    '9AM - 10AM' => '9AM - 10AM',
                    '10AM - 11AM' => '10AM - 11AM',
                    '11AM - 12PM' => '11AM - 12PM',
                    '12PM - 1PM' => '12PM - 1PM',
                    '1PM - 2PM' => '1PM - 2PM',
                    '2PM - 3PM' => '2PM - 3PM',
                    '3PM - 4PM' => '3PM - 4PM',
                    '4PM - 5PM' => '4PM - 5PM',
                    '5PM - 6PM' => '5PM - 6PM',
                    '6PM - 7PM' => '6PM - 7PM',
                    '7PM - 8PM' => '7PM - 8PM',
                    '8PM - 9PM' => '8PM - 9PM',
                ],
                'placeholder' => 'Preferred Time'
            ])

            ->add('doctor',EntityType::class,[
                'class'=> Doctor::class,
                'placeholder' => 'Select Recommending Doctor',
                'choice_label' => 'firstName',
                'query_builder' => function(DoctorRepository $btr){
                    return $btr->createQueryBuilder('object')
                        ->where('object.isDeleted=:isDeleted')
                        ->andWhere('object.isActive=:isActive')
                        ->setParameter('isDeleted',false)
                        ->setParameter('isActive',true)
                        ->orderBy('object.firstName','ASC');
                },
            ])


            ->add('testRequisitionFilename', FileType::class, [
                'label' => 'Test Requisition File',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF, PNG, or JPEG file',
                    ])
                ],
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PreRequests::class,
        ]);

    }

}
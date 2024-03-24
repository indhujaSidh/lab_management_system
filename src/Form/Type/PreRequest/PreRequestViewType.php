<?php

namespace App\Form\Type\PreRequest;

use App\Entity\Appointment\PreRequests;
use App\Entity\ProcessStatus;
use App\Entity\User\Doctor;
use App\Repository\ProcessStatusRepository;
use App\Repository\User\DoctorRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;


class PreRequestViewType extends AbstractType
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
            ->add('testsInfo', TextareaType::class, [
                'attr' => [
                    'pattern' => '^([0-9]{10})$'
                ]
            ])
            ->add('prefferedTime', TextType::class)

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


            ->add('processState',EntityType::class,[
                'class'=> ProcessStatus::class,
                'placeholder' => 'Select an option',
                'choice_label' => 'name',
//                'query_builder' => function(ProcessStatusRepository $btr){
//                    return $btr->createQueryBuilder('object')
//                        ->where('object.isDeleted=:isDeleted')
//                        ->andWhere('object.isActive=:isActive')
//                        ->setParameter('isDeleted',false)
//                        ->setParameter('isActive',true)
//                        ->orderBy('object.id','ASC');
//                },
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
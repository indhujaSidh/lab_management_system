<?php

namespace App\Form\Type\Appointment;

use App\Entity\Appointment\AppointmentTestMappings;
use App\Entity\Test\Test;
use App\Entity\User\Technician;
use App\Form\Model\TestModel;
use App\Repository\Test\TestRepository;
use App\Repository\User\TechnicianRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AppointmentTestMappingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('test', EntityType::class, [
                'class' => Test::class,
                'placeholder' => 'Select an option',
                'choice_label' => 'name',
                'query_builder' => function (TestRepository $btr) {
                    return $btr->createQueryBuilder('object')
                        ->where('object.isDeleted=:isDeleted')
                        ->andWhere('object.isActive=:isActive')
                        ->setParameter('isDeleted', false)
                        ->setParameter('isActive', true)
                        ->orderBy('object.name', 'ASC');
                },
                'required' => true,
            ])
            ->add('sampleCollected', DateType::class,[
                'required' => true,
                'widget' => 'single_text',
                'html5' => true,
                'input_format' => 'Y-m-d H:i:s',
                'input' => 'datetime_immutable',

            ])
            ->add('readyDate', DateType::class,[
                'required' => true,
                'widget' => 'single_text',
                'html5' => true,
                'input_format' => 'Y-m-d H:i:s',
                'input' => 'datetime_immutable',
                'attr' => [
                    'class' => 'js-datepicker',
                    'min' => date('Y-m-d')
                ],

            ])

            ->add('printedDate', DateType::class,[
                'required' => true,
                'widget' => 'single_text',
                'html5' => true,
                'input_format' => 'Y-m-d H:i:s',
                'input' => 'datetime_immutable',
                'attr' => [
                    'class' => 'js-datepicker',
                    'min' => date('Y-m-d')
                ],

            ])
            ->add('comments',TextareaType::class,[
                'required' => false,
            ])
            ->add('technician', EntityType::class, [
                'class' => Technician::class,
                'placeholder' => 'Select an option',
                'choice_label' => 'firstName',
                'query_builder' => function (TechnicianRepository $btr) {
                    return $btr->createQueryBuilder('object')
                        ->where('object.isDeleted=:isDeleted')
                        ->andWhere('object.isActive=:isActive')
                        ->setParameter('isDeleted', false)
                        ->setParameter('isActive', true)
                        ->orderBy('object.firstName', 'ASC');
                },
                'required' => false,
            ])

            ->add('reportFile', FileType::class, [
                'label' => 'Report File',
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
            'data_class' =>AppointmentTestMappings::class
        ]);
    }

}
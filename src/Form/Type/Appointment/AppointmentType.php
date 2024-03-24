<?php

namespace App\Form\Type\Appointment;

use App\Entity\Test\Test;
use App\Entity\TimeSlot\TimeSlot;
use App\Entity\User\AppUser;
use App\Entity\User\Doctor;
use App\Entity\User\Technician;
use App\Form\Model\AppointmentModel;
use App\Repository\Test\TestRepository;
use App\Repository\TimeSlot\TimeSlotRepository;
use App\Repository\User\AppUserRepository;
use App\Repository\User\DoctorRepository;
use App\Repository\User\TechnicianRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('patient', EntityType::class, [
                'class' => AppUser::class,
                'placeholder' => 'Select an option',
                'choice_label' => 'contactNumber',
                'query_builder' => function (AppUserRepository $btr) {
                    return $btr->createQueryBuilder('object')
                        ->where('object.isDeleted=:isDeleted')
                        ->andWhere('object.isActive=:isActive')
                        ->andWhere('object.isBackendUser=:isBackendUser')
                        ->setParameter('isDeleted', false)
                        ->setParameter('isActive', true)
                        ->setParameter('isBackendUser',false)
                        ->orderBy('object.firstName', 'ASC');
                },
                'required' => true,
            ])
            ->add('doctor', EntityType::class, [
                'class' => Doctor::class,
                'placeholder' => 'Select an option',
                'choice_label' => 'firstName',
                'query_builder' => function (DoctorRepository $btr) {
                    return $btr->createQueryBuilder('object')
                        ->where('object.isDeleted=:isDeleted')
                        ->andWhere('object.isActive=:isActive')
                        ->setParameter('isDeleted', false)
                        ->setParameter('isActive', true)
                        ->orderBy('object.firstName', 'ASC');
                },
                'required' => false,
            ])
            ->add('paymentStatus', ChoiceType::class, [
                'choices' => [
                    'Payment Done' => 'PAYMENT_DONE',
                    'Payment Pending' => 'PAYMENT_PENDING'
                ],
                'placeholder' => 'Select an option'
            ])
            ->add('refDoctor', TextType::class, [
                'required' => false,
            ])
            ->add('timeSlot', EntityType::class, [
                'class' => TimeSlot::class,
                'placeholder' => 'Select an option',
                'choice_label' => 'name',
                'query_builder' => function (TimeSlotRepository $btr) {
                    return $btr->createQueryBuilder('ts')
                        ->where('ts.availableSlots > 0');
                },
                'required' => false,
            ])

            ->add('tests', CollectionType::class, [
                'entry_type' => AppointmentTestType::class,
                'entry_options' => ['label' => true],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])

            ->add('sampleCollected', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
                'input_format' => 'Y-m-d H:i:s',
                'input' => 'datetime_immutable',

            ])
            ->add('submit', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppointmentModel::class
        ]);
    }

}
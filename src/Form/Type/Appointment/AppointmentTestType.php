<?php

namespace App\Form\Type\Appointment;

use App\Entity\Test\Test;
use App\Form\Model\TestModel;
use App\Repository\Test\TestRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('testName', EntityType::class, [
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
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TestModel::class
        ]);
    }


}
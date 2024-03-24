<?php

namespace App\Form\Type\Test;

use App\Entity\Test\Test;
use App\Entity\Test\TestCategory;
use App\Repository\Test\TestCategoryRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'required' => true
            ])
            ->add('category',EntityType::class,[
                'class'=> TestCategory::class,
                'placeholder' => 'Select an option',
                'choice_label' => 'name',
                'query_builder' => function(TestCategoryRepository $btr){
                    return $btr->createQueryBuilder('object')
                        ->where('object.isDeleted=:isDeleted')
                        ->andWhere('object.isActive=:isActive')
                        ->setParameter('isDeleted',false)
                        ->setParameter('isActive',true)
                        ->orderBy('object.name','ASC');
                },
                'required' => true,
            ])
            ->add('metaCode',TextType::class,[
                'required' => true,
            ])
            ->add('price',TextType::class,[
                'required' => true
            ])
            ->add('processingPeriod',TextType::class,[
                'required' => true
            ])
            ->add('description',CKEditorType::class,[
                'config'    => array(
                    'toolbar'   => 'full',
                    'format_tags' => 'p;h1;h2;h3;h4;h5;pre',
                    'required'  => true,
                ),
                'required' => true
            ])
            ->add('submit', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Test::class
        ]);
    }


}
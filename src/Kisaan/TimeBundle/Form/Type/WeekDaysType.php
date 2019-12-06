<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\TimeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeekDaysType extends AbstractType
{

    public function __construct()
    {

    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'mapped' => false,
                'choices' => array(
                    'kisaan.monday' => '1',
                    'kisaan.tuesday' => '2',
                    'kisaan.wednesday' => '3',
                    'kisaan.thursday' => '4',
                    'kisaan.friday' => '5',
                    'kisaan.saturday' => '6',
                    'kisaan.sunday' => '7',
                ),
                'translation_domain' => 'kisaan',
                'multiple' => true,
                'expanded' => true,
                /** @Ignore */
                'label' => false,
                'data' => array('1', '2', '3', '4', '5', '6', '7'),
            )
        );
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'weekdays';
    }
}

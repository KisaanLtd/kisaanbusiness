<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\UserBundle\Form\Type;

use Kisaan\CoreBundle\Form\Type\EntityHiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserImageType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                HiddenType::class,
                array(
                    /** @Ignore */
                    'label' => false
                )
            )
            ->add(
                'file',
                FileType::class,
                array(
                    'image_path' => 'webPath',
                    'imagine_filter' => 'user_small',
                    /** @Ignore */
                    'label' => false,
                    'mapped' => false,
                    'attr' => array(
                        "class" => "dn"
                    )
                )
            )
            ->add(
                'position',
                HiddenType::class,
                array(
                    /** @Ignore */
                    'label' => false,
                    'attr' => array(
                        "class" => "sort-position"
                    )
                )
            )
            ->add(
                'user',
                EntityHiddenType::class,
                array(
                    'class' => 'Kisaan\UserBundle\Entity\User',
                    /** @Ignore */
                    'label' => false
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Kisaan\UserBundle\Entity\UserImage',
                'csrf_token_id' => 'user_image',
                'translation_domain' => 'kisaan_user',
                'cascade_validation' => true,
                /** @Ignore */
                'label' => false
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'user_image';
    }

}

<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\Form\Type\Frontend;

use Kisaan\CoreBundle\Form\Type\CountryFilteredType;
use Kisaan\GeoBundle\Form\Type\GeocodingToCoordinateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * ListingLocationType
 */
class ListingLocationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'country',
                CountryFilteredType::class,
                array(
                    'label' => 'listing.form.location.country',
                    'translation_domain' => 'kisaan_listing',//Not recognized elsewhere
                    'required' => true,
                )
            )
            ->add(
                'city',
                TextType::class,
                array(
                    'label' => 'listing.form.location.city',
                    'translation_domain' => 'kisaan_listing',
                    'required' => true,
                )
            )
            ->add(
                'zip',
                TextType::class,
                array(
                    'label' => 'listing.form.location.zip',
                    'translation_domain' => 'kisaan_listing',
                    'required' => false,
                )
            )
            ->add(
                'route',
                TextType::class,
                array(
                    'label' => 'listing.form.location.route',
                    'translation_domain' => 'kisaan_listing',
                    'required' => true,
                )
            )
            ->add(
                'street_number',
                TextType::class,
                array(
                    'label' => 'listing.form.location.street_number',
                    'translation_domain' => 'kisaan_listing',
                    'required' => true,
                )
            )
            //This field contains geocoding information in JSON format.
            //Its value is transformed to Coordinate entity through data transformer
            ->add(
                'coordinate',
                GeocodingToCoordinateType::class
            );

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            array(
                'translation_domain' => 'kisaan_listing',
                'data_class' => 'Kisaan\CoreBundle\Entity\ListingLocation',
                'constraints' => new Valid(),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'listing_location';
    }
}
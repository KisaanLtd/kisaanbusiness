<?php

namespace Kisaan\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class BookingUserAddressAdmin extends AbstractAdmin
{
    protected $translationDomain = 'kisaan_user';
    protected $baseRoutePattern = 'booking-user-address';
    protected $locales;

    public function setLocales($locales)
    {
        $this->locales = $locales;
    }

    /** @inheritdoc */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'address',
                'textarea',
                array(
                    'label' => 'form.address.address',
                    'required' => false,
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'form.address.city',
                    'required' => false,
                )
            )
            ->add(
                'zip',
                null,
                array(
                    'label' => 'form.address.zip',
                    'required' => false,
                )
            )
            ->add(
                'country',
                'country',
                array(
                    'label' => 'form.address.country',
                    'required' => false,
                    'preferred_choices' => array("GB", "FR", "ES", "DE", "IT", "CH", "US", "RU", "IN"),
                )
            )
            ->end();
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('delete');
    }
}

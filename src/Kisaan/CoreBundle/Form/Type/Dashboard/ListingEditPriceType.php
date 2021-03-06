<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\Form\Type\Dashboard;

use Kisaan\CoreBundle\Event\ListingFormBuilderEvent;
use Kisaan\CoreBundle\Event\ListingFormEvents;
use Kisaan\CoreBundle\Form\Type\PriceType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListingEditPriceType extends AbstractType
{
    protected $dispatcher;

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'price',
                PriceType::class,
                array(
                    'label' => 'listing_edit.form.price',
                )
            );

        //Dispatch LISTING_EDIT_PRICE_FORM_BUILD Event. Listener listening this event can add fields and validation
        //Used for example to add fields to price edition form
        $this->dispatcher->dispatch(
            ListingFormEvents::LISTING_EDIT_PRICE_FORM_BUILD,
            new ListingFormBuilderEvent($builder)
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
                'data_class' => 'Kisaan\CoreBundle\Entity\Listing',
                'translation_domain' => 'kisaan_listing',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'listing_edit_price';
    }
}

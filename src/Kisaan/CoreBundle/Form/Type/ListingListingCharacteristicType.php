<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\Form\Type;

use Kisaan\CoreBundle\Entity\ListingListingCharacteristic;
use Kisaan\CoreBundle\Repository\ListingCharacteristicValueRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListingListingCharacteristicType extends AbstractType
{
    private $locale;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->locale = $requestStack->getCurrentRequest()->getLocale();
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                /** @var ListingListingCharacteristic $llc */
                $llc = $event->getData();

                $form->add(
                    'listingCharacteristicValue',
                    'entity',
                    array(
                        'query_builder' => function (ListingCharacteristicValueRepository $lcvr) use ($llc) {
                            $lct = $llc->getListingCharacteristic()->getListingCharacteristicType();

                            return $lcvr->getFindAllTranslatedQueryBuilder(
                                $lct,
                                $this->locale
                            );
                        },
                        'placeholder' => 'listing.form.characteristic.choose',
                        'choice_label' => 'translations[' . $this->locale . '].name',
                        'class' => 'Kisaan\CoreBundle\Entity\ListingCharacteristicValue',
                    )
                );
            }
        );
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Kisaan\CoreBundle\Entity\ListingListingCharacteristic',
                'translation_domain' => 'kisaan_listing'
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'listing_listing_characteristic';
    }
}

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

use Kisaan\CoreBundle\Entity\BookingUserAddress;
use Kisaan\UserBundle\Entity\User;
use Kisaan\UserBundle\Entity\UserAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Validator\Constraints\Valid;

class BookingUserAddressFormType extends AbstractType
{
    private $securityTokenStorage;
    private $securityAuthChecker;

    /**
     * @param TokenStorage         $securityTokenStorage
     * @param AuthorizationChecker $securityAuthChecker
     */
    public function __construct(
        TokenStorage $securityTokenStorage,
        AuthorizationChecker $securityAuthChecker
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthChecker = $securityAuthChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'address',
                TextareaType::class,
                array(
                    'label' => 'form.address.address',
                    'required' => true
                )
            )
            ->add(
                'city',
                null,
                array(
                    'label' => 'form.address.city',
                    'required' => true
                )
            )
            ->add(
                'zip',
                null,
                array(
                    'label' => 'form.address.zip',
                    'required' => true
                )
            )
            ->add(
                'country',
                CountryType::class,
                array(
                    'label' => 'form.address.country',
                    'required' => true,
                    'preferred_choices' => array("GB", "FR", "ES", "DE", "IT", "CH", "US", "RU", "IN"),
                    'data' => 'FR'
                )
            );


        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                if ($this->securityAuthChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                    /** @var User $user */
                    $user = $this->securityTokenStorage->getToken()->getUser();
                    $addressDeliveries = $user->getAddressesOfType(UserAddress::TYPE_DELIVERY);
                    if ($addressDeliveries->count()) {
                        /** @var UserAddress $addressDelivery */
                        $addressDelivery = $addressDeliveries->first();
                        $address = new BookingUserAddress();
                        $address->setAddress($addressDelivery->getAddress());
                        $address->setCity($addressDelivery->getCity());
                        $address->setZip($addressDelivery->getZip());
                        $address->setCountry($addressDelivery->getCountry());
                        $event->setData($address);
                    }
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Kisaan\CoreBundle\Entity\BookingUserAddress',
                'csrf_token_id' => 'booking_user_address',
                'translation_domain' => 'kisaan_user',
                'constraints' => new Valid(),
                'validation_groups' => array('booking_new'),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_user_address';
    }
}

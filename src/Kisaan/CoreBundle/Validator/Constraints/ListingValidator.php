<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ListingValidator extends ConstraintValidator
{
    private $emr;
    private $maxImages;
    private $minImages;
    private $minCategories;
    private $minPrice;
    private $countries;

    /**
     * @param EntityManager $emr
     * @param               $maxImages
     * @param               $minImages
     * @param               $minCategories
     * @param               $minPrice
     * @param               $countries
     */
    public function __construct(EntityManager $emr, $maxImages, $minImages, $minCategories, $minPrice, $countries)
    {
        $this->emr = $emr;
        $this->maxImages = $maxImages;
        $this->minImages = $minImages;
        $this->minCategories = $minCategories;
        $this->minPrice = $minPrice;
        $this->countries = $countries;
    }

    /**
     * @param mixed      $listing
     * @param Constraint $constraint
     */
    public function validate($listing, Constraint $constraint)
    {
        /** @var $listing \Kisaan\CoreBundle\Entity\Listing */
        /** @var $constraint \Kisaan\CoreBundle\Validator\Constraints\Listing */

        //Images
        if ($listing->getImages()->count() > $this->maxImages) {
            $this->context->buildViolation($constraint::$messageMaxImages)
                ->atPath('image[new]')
                ->setParameter('{{ max_images }}', $this->maxImages)
                ->setTranslationDomain('kisaan_listing')
                ->addViolation();
        }

        if ($listing->getImages()->count() < $this->minImages) {
            $this->context->buildViolation($constraint::$messageMinImages)
                ->atPath('image[new]')
                ->setParameter('{{ min_images }}', $this->minImages)
                ->setTranslationDomain('kisaan_listing')
                ->addViolation();
        }

        //Categories
        if ($listing->getListingListingCategories()->count() < $this->minCategories) {
            $this->context->buildViolation($constraint::$messageMinCategories)
                ->atPath('listingListingCategories')
                ->setParameter('{{ min_categories }}', $this->minCategories)
                ->setTranslationDomain('kisaan_listing')
                ->addViolation();
        }

        //Price
        if ($listing->getPrice() < $this->minPrice) {
            $this->context->buildViolation($constraint::$messageMinPrice)
                ->atPath('price')
                ->setParameter('{{ min_price }}', $this->minPrice / 100)
                ->setTranslationDomain('kisaan_listing')
                ->addViolation();
        }

        //Duration
        if ($listing->getMinDuration() && $listing->getMaxDuration() &&
            $listing->getMinDuration() > $listing->getMaxDuration()
        ) {
            $this->context->buildViolation($constraint::$messageDuration)
                ->atPath('min_duration')
                ->setTranslationDomain('kisaan_listing')
                ->addViolation();
        }


        //Location
        if ($this->countries && !in_array($listing->getLocation()->getCountry(), $this->countries)) {
            $this->context->buildViolation($constraint::$messageCountryInvalid)
                ->atPath('location.country')
                ->setTranslationDomain('kisaan_listing')
                ->addViolation();
        }

        //Status
//        $oldListing = $this->emr->getUnitOfWork()->getOriginalEntityData($listing);
//
//        if (is_array($oldListing) and !empty($oldListing)) {
//            $oldStatus = $oldListing['status'];
//            if ($oldStatus == $listing::STATUS_INVALIDATED && $listing->getStatus() != $listing::STATUS_INVALIDATED) {
//                $listing->setStatus($oldStatus);
//                $this->context->buildViolation($constraint::$messageStatusInvalidated)
//                    ->atPath('status')
//                    ->setTranslationDomain('kisaan_listing')
//                    ->addViolation();
//            }
//        }
    }

}

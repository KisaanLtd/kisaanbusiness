<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\DataFixtures\ORM;

use Kisaan\CoreBundle\Entity\Listing;
use Kisaan\CoreBundle\Entity\ListingCategory;
use Kisaan\CoreBundle\Entity\ListingCharacteristic;
use Kisaan\CoreBundle\Entity\ListingCharacteristicValue;
use Kisaan\CoreBundle\Entity\ListingImage;
use Kisaan\CoreBundle\Entity\ListingListingCategory;
use Kisaan\CoreBundle\Entity\ListingListingCharacteristic;
use Kisaan\CoreBundle\Entity\ListingLocation;
use Kisaan\CoreBundle\Entity\ListingTranslation;
use Kisaan\GeoBundle\Entity\Area;
use Kisaan\GeoBundle\Entity\City;
use Kisaan\GeoBundle\Entity\Coordinate;
use Kisaan\GeoBundle\Entity\Country;
use Kisaan\GeoBundle\Entity\Department;
use Kisaan\UserBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ListingFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        //GeoGraphical entities
        $country = new Country();
        $country->setCode("IN");
        $country->translate('en')->setName('India');
        $country->translate('fr')->setName('Inde');

        $area = new Area();
        $area->setCountry($country);
        $area->translate('en')->setName('Maharashtra');
        $area->translate('fr')->setName('Maharashtra');

        $department = new Department();
        $department->setCountry($country);
        $department->setArea($area);
        $department->translate('en')->setName('Kharadi');
        $department->translate('fr')->setName('Kharadi');

        $city = new City();
        $city->setCountry($country);
        $city->setArea($area);
        $city->setDepartment($department);
        $city->translate('en')->setName('Pune');
        $city->translate('fr')->setName('Pune');

        $manager->persist($country);
        $manager->persist($area);
        $manager->persist($department);
        $manager->persist($city);
        $country->mergeNewTranslations();
        $area->mergeNewTranslations();
        $department->mergeNewTranslations();
        $city->mergeNewTranslations();

        //Coordinate entity
        $coordinate = new Coordinate();
        $coordinate->setCountry($country);
        $coordinate->setArea($area);
        $coordinate->setDepartment($department);
        $coordinate->setCity($city);
        $coordinate->setZip("411014");
        $coordinate->setRoute("Kharadi Road");
        $coordinate->setStreetNumber("SR14/1");
        $coordinate->setAddress("SR14/1, Kharadi Road, Kharadi, Pune, Maharashtra, 411014 India");
        $coordinate->setLat(18.5204);
        $coordinate->setLng(73.8567);
        $manager->persist($coordinate);

        //Listing Location
        $location = new ListingLocation();
        $location->setCountry("IN");
        $location->setCity("Pune");
        $location->setZip("411014");
        $location->setRoute("Kharadi Road");
        $location->setStreetNumber("SR14/1");
        $location->setCoordinate($coordinate);
        $manager->persist($location);

        //Listing Image
        $image1 = new ListingImage();
        $image1->setName(ListingImage::IMAGE_DEFAULT);
        $image1->setPosition(1);

        $image2 = new ListingImage();
        $image2->setName(ListingImage::IMAGE_DEFAULT);
        $image2->setPosition(2);

        //Listing
        $listing = new Listing();
        $listing->setLocation($location);
        $listing->addImage($image1);
        $listing->addImage($image2);
        $listing->translate('en')->setTitle('Listing One');
        $listing->translate('fr')->setTitle('Annonce une');

        $listing->translate('en')->setDescription('Listing One Description');
        $listing->translate('fr')->setDescription('Description de l\'annonce une');
        $listing->setStatus(Listing::STATUS_PUBLISHED);
        $listing->setPrice(10000);
        $listing->setCertified(1);

        /** @var User $user */
        $user = $manager->merge($this->getReference('offerer'));
        $listing->setUser($user);

        /** @var ListingCategory $category */
        $category = $manager->merge($this->getReference('category1_1'));
        $listingCategory = new ListingListingCategory();
        $listingCategory->setListing($listing);
        $listingCategory->setCategory($category);
        $listing->addListingListingCategory($listingCategory);

        /** @var ListingCharacteristic $characteristic */
        $characteristic = $manager->merge($this->getReference('characteristic_1'));
        $listingListingCharacteristic = new ListingListingCharacteristic();
        $listingListingCharacteristic->setListing($listing);
        $listingListingCharacteristic->setListingCharacteristic($characteristic);
        /** @var ListingCharacteristicValue $value */
        $value = $manager->merge($this->getReference('characteristic_value_yes'));
        $listingListingCharacteristic->setListingCharacteristicValue($value);
        $listing->addListingListingCharacteristic($listingListingCharacteristic);


        $characteristic = $manager->merge($this->getReference('characteristic_2'));
        $listingListingCharacteristic = new ListingListingCharacteristic();
        $listingListingCharacteristic->setListing($listing);
        $listingListingCharacteristic->setListingCharacteristic($characteristic);
        $value = $manager->merge($this->getReference('characteristic_value_2'));
        $listingListingCharacteristic->setListingCharacteristicValue($value);
        $listing->addListingListingCharacteristic($listingListingCharacteristic);


        $characteristic = $manager->merge($this->getReference('characteristic_3'));
        $listingListingCharacteristic = new ListingListingCharacteristic();
        $listingListingCharacteristic->setListing($listing);
        $listingListingCharacteristic->setListingCharacteristic($characteristic);
        $value = $manager->merge($this->getReference('characteristic_value_custom_1'));
        $listingListingCharacteristic->setListingCharacteristicValue($value);
        $listing->addListingListingCharacteristic($listingListingCharacteristic);


        $characteristic = $manager->merge($this->getReference('characteristic_4'));
        $listingListingCharacteristic = new ListingListingCharacteristic();
        $listingListingCharacteristic->setListing($listing);
        $listingListingCharacteristic->setListingCharacteristic($characteristic);
        $value = $manager->merge($this->getReference('characteristic_value_1'));
        $listingListingCharacteristic->setListingCharacteristicValue($value);
        $listing->addListingListingCharacteristic($listingListingCharacteristic);

        $manager->persist($listing);
        $listing->mergeNewTranslations();
        $manager->flush();


        /** @var ListingTranslation $translation */
        foreach ($listing->getTranslations() as $i => $translation) {
            $translation->generateSlug();
        }
        $manager->persist($listing);
        $manager->flush();

        $this->addReference('listing-one', $listing);
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            ListingCategoryFixtures::class,
            ListingCharacteristicFixtures::class,
            ListingCharacteristicValueFixtures::class,
        );
    }

}

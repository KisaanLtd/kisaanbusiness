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

use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Listing extends Constraint implements TranslationContainerInterface
{
    public static $messageMaxImages = "listing_images.max {{ max_images }}";
    public static $messageMinImages = "listing_images.min {{ min_images }}";
    public static $messageMinCategories = "listing_categories.min {{ min_categories }}";
//    public static $messageStatusInvalidated = "listing_status.invalidated";
    public static $messageMinPrice = "listing_price.min {{ min_price }}";
    public static $messageDuration = "listing_duration.overlap";
    public static $messageCountryInvalid = "listing_location_country.invalid";

    public function validatedBy()
    {
        return 'listing';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * JMS Translation messages
     *
     * @return array
     */
    public static function getTranslationMessages()
    {
        $messages = array();
        $messages[] = new Message(self::$messageMaxImages, 'kisaan_listing');
        $messages[] = new Message(self::$messageMinImages, 'kisaan_listing');
        $messages[] = new Message(self::$messageMinCategories, 'kisaan_listing');
        $messages[] = new Message(self::$messageMinPrice, 'kisaan_listing');
        $messages[] = new Message(self::$messageDuration, 'kisaan_listing');
        $messages[] = new Message(self::$messageCountryInvalid, 'kisaan_listing');

        return $messages;
    }
}
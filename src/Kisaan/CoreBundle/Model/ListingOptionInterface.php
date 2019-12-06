<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\Model;


use Kisaan\CoreBundle\Entity\Listing;

interface ListingOptionInterface
{
    /**
     * @param Listing $listing
     * @return mixed
     */
    public function setListing(Listing $listing);

    /**
     * @return Listing
     */
    public function getListing();

    public function mergeNewTranslations();

    public function getTranslations();

    public function getName();

}
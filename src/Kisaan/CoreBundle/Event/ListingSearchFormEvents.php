<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\Event;


class ListingSearchFormEvents
{
    /**
     * The LISTING_SEARCH_RESULT_FORM_BUILD event is thrown each time listing search result form is build
     *
     * This event allows you to add form fields and validation on them.
     *
     * The event listener receives a \Kisaan\CoreBundle\Event\ListingFormBuilderEvent instance.
     */
    const LISTING_SEARCH_RESULT_FORM_BUILD = 'kisaan.listing_search.result.form.build';
}
<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\Form\Handler\Frontend;

use Kisaan\CoreBundle\Entity\Booking;
use Kisaan\CoreBundle\Entity\Listing;
use Kisaan\CoreBundle\Model\DateRange;
use Kisaan\CoreBundle\Model\ListingSearchRequest;
use Kisaan\CoreBundle\Model\Manager\BookingManager;
use Kisaan\CoreBundle\Model\TimeRange;
use Kisaan\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Handle Booking Price Form
 *
 */
class BookingPriceFormHandler
{
    protected $session;
    protected $request;
    protected $listingSearchRequest;
    protected $bookingManager;

    /**
     * Initialize the handler with the form and the request
     *
     * @param Session              $session
     * @param RequestStack         $requestStack ,
     * @param ListingSearchRequest $listingSearchRequest
     * @param BookingManager       $bookingManager
     *
     */
    public function __construct(
        Session $session,
        RequestStack $requestStack,
        ListingSearchRequest $listingSearchRequest,
        BookingManager $bookingManager
    ) {
        $this->session = $session;
        $this->request = $requestStack->getCurrentRequest();
        $this->listingSearchRequest = $listingSearchRequest;
        $this->bookingManager = $bookingManager;
    }


    /**
     * Init form
     *
     * @param User|null $user
     * @param Listing   $listing
     *
     * @return Booking $booking
     */
    public function init($user, Listing $listing)
    {
        /** @var ListingSearchRequest $searchRequest */
        $searchRequest = $this->session->get('listing_search_request', $this->listingSearchRequest);
        $dateTimeRange = $searchRequest->getDateTimeRange();
        $booking = $this->bookingManager->initBooking($listing, $user, $dateTimeRange);

        return $booking;
    }

}
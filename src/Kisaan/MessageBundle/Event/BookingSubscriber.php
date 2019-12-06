<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\MessageBundle\Event;


use Kisaan\CoreBundle\Event\BookingEvent;
use Kisaan\CoreBundle\Event\BookingEvents;
use Kisaan\MessageBundle\Model\ThreadManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class BookingSubscriber implements EventSubscriberInterface
{
    protected $threadManager;

    /**
     * @param ThreadManager $threadManager
     */
    public function __construct(ThreadManager $threadManager)
    {
        $this->threadManager = $threadManager;
    }


    public function onBookingNewCreated(BookingEvent $event)
    {
        $booking = $event->getBooking();
        $user = $booking->getUser();
        $this->threadManager->createNewListingThread($user, $booking);
    }


    public static function getSubscribedEvents()
    {
        return array(
            BookingEvents::BOOKING_NEW_CREATED => array('onBookingNewCreated', 1),
        );
    }

}
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


use Kisaan\CoreBundle\Entity\Booking;

interface BookingDepositRefundInterface
{
    /**
     * @param Booking $booking
     * @return mixed
     */
    public function setBooking(Booking $booking);

    /**
     * @return Booking
     */
    public function getBooking();
}
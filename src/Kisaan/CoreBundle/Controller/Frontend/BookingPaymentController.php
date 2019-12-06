<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\Controller\Frontend;

use Kisaan\CoreBundle\Entity\Booking;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Booking Payment controller.
 *
 * @Route("/booking/payment")
 */
class BookingPaymentController extends Controller
{
    /**
     * Payment page.
     *
     * @Route("/{booking_id}/new",
     *      name="kisaan_booking_payment_new",
     *      requirements={
     *          "booking_id" = "\d+"
     *      },
     * )
     *
     * @Security("is_granted('create', booking) and not has_role('ROLE_ADMIN') and has_role('ROLE_USER')")
     *
     * @ParamConverter("booking", class="KisaanCoreBundle:Booking", options={"id" = "booking_id"})
     *
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Booking $booking
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, Booking $booking)
    {
        //Breadcrumbs
        $breadcrumbs = $this->get('kisaan.breadcrumbs_manager');
        $breadcrumbs->addBookingNewItems($request, $booking);

        return $this->render(
            'KisaanCoreBundle:Frontend/BookingPayment:new.html.twig',
            array(
                'booking' => $booking,
            )
        );
    }
}

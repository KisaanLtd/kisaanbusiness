<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\Controller\Dashboard\Asker;

use Kisaan\CoreBundle\Entity\Booking;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Booking Payin Dashboard controller.
 *
 * @Route("/asker/booking-payin")
 */
class BookingPayinController extends Controller
{

    /**
     * Lists all booking payin.
     *
     * @Route("/{page}", name="kisaan_dashboard_booking_payin_asker", defaults={"page" = 1})
     * @Method("GET")
     *
     * @param  Request $request
     * @param  int     $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page)
    {
        $bookingManager = $this->get('kisaan.booking.manager');
        $bookings = $bookingManager->findPayedByAsker(
            $this->getUser()->getId(),
            $request->getLocale(),
            $page,
            array(Booking::STATUS_PAYED, Booking::STATUS_CANCELED_ASKER)
        );

        return $this->render(
            'KisaanCoreBundle:Dashboard/BookingPayin:index.html.twig',
            array(
                'bookings' => $bookings,
                'pagination' => array(
                    'page' => $page,
                    'pages_count' => ceil($bookings->count() / $bookingManager->maxPerPage),
                    'route' => $request->get('_route'),
                    'route_params' => $request->query->all()
                )
            )
        );
    }


    /**
     * Show booking Bank Wire bill.
     *
     * @Route("/{id}/show-bill", name="kisaan_dashboard_booking_payin_show_bill_asker", requirements={"id" = "\d+"})
     * @Method("GET")
     *
     * @param  Request $request
     * @param  int     $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showBillAction(Request $request, $id)
    {
        $bookingManager = $this->get('kisaan.booking.manager');
        try {
            $booking = $bookingManager->findOneByAsker(
                $id,
                $this->getUser()->getId(),
                $request->getLocale(),
                array(Booking::STATUS_PAYED, Booking::STATUS_CANCELED_ASKER)
            );
        } catch (\Exception $e) {
            throw $this->createNotFoundException('Bill not found');
        }

        return $this->render(
            'KisaanCoreBundle:Dashboard/BookingPayin:show_bill.html.twig',
            array(
                'booking' => $booking,
            )
        );
    }


}

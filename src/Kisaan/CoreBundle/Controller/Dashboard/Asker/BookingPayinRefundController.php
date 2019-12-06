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

use Kisaan\CoreBundle\Entity\BookingPayinRefund;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Booking Payin Refund Dashboard controller.
 *
 * @Route("/asker/booking-payin-refund")
 */
class BookingPayinRefundController extends Controller
{

    /**
     * Lists all booking payin refund.
     *
     * @Route("/{page}", name="kisaan_dashboard_booking_payin_refund_asker", defaults={"page" = 1})
     * @Method("GET")
     *
     * @param  Request $request
     * @param  int     $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page)
    {
        $bookingPayinRefundManager = $this->get('kisaan.booking_payin_refund.manager');
        $bookingPayinRefunds = $bookingPayinRefundManager->findByAsker(
            $this->getUser()->getId(),
            $page,
            array(BookingPayinRefund::STATUS_PAYED)
        );

        return $this->render(
            'KisaanCoreBundle:Dashboard/BookingPayinRefund:index.html.twig',
            array(
                'booking_payin_refunds' => $bookingPayinRefunds,
                'pagination' => array(
                    'page' => $page,
                    'pages_count' => ceil($bookingPayinRefunds->count() / $bookingPayinRefundManager->maxPerPage),
                    'route' => $request->get('_route'),
                    'route_params' => $request->query->all()
                )
            )
        );
    }


    /**
     * Show booking Payin Refund bill.
     *
     * @Route("/{id}/show-bill", name="kisaan_dashboard_booking_payin_refund_show_bill_asker", requirements={"id" = "\d+"})
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
        $bookingPayinRefundManager = $this->get('kisaan.booking_payin_refund.manager');
        try {
            $bookingPayinRefund = $bookingPayinRefundManager->findOneByAsker(
                $id,
                $this->getUser()->getId(),
                array(BookingPayinRefund::STATUS_PAYED)
            );
        } catch (\Exception $e) {
            throw $this->createNotFoundException('Bill not found');
        }

        return $this->render(
            'KisaanCoreBundle:Dashboard/BookingPayinRefund:show_bill.html.twig',
            array(
                'booking_payin_refund' => $bookingPayinRefund,
            )
        );
    }


}

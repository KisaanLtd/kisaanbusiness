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
use Kisaan\CoreBundle\Entity\Listing;
use Kisaan\CoreBundle\Form\Type\Frontend\BookingPriceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Booking controller.
 *
 * @Route("/booking")
 */
class BookingPriceController extends Controller
{
    /**
     * Creates a new Booking price form.
     *
     * @param  Listing $listing
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function bookingPriceFormAction(Listing $listing)
    {
        $bookingPriceHandler = $this->get('kisaan.form.handler.booking_price');
        $booking = $bookingPriceHandler->init($this->getUser(), $listing);

        $form = $this->createBookingPriceForm($booking);

        return $this->render(
            '@KisaanCore/Frontend/Booking/form_booking_price.html.twig',
            array(
                'form' => $form->createView(),
                'booking' => $booking
            )
        );
    }

    /**
     * Creates a form for Booking Price.
     *
     * @param Booking $booking The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createBookingPriceForm(Booking $booking)
    {
        $form = $this->get('form.factory')->createNamed(
            '',
            BookingPriceType::class,
            $booking,
            array(
                'method' => 'POST',
                'action' => $this->generateUrl(
                    'kisaan_booking_price',
                    array(
                        'listing_id' => $booking->getListing()->getId()
                    )
                )
            )
        );

        return $form;
    }


    /**
     * Get Booking Price
     *
     * @Route("/{listing_id}/price", name="kisaan_booking_price", requirements={"listing_id" = "\d+"})
     * @Security("is_granted('booking', listing)")
     *
     * @ParamConverter("listing", class="KisaanCoreBundle:Listing", options={"id" = "listing_id"})
     *
     * @Method({"POST"})
     *
     * @param Request  $request
     * @param  Listing $listing
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function getBookingPriceAction(Request $request, Listing $listing)
    {
        $bookingPriceHandler = $this->get('kisaan.form.handler.booking_price');
        $booking = $bookingPriceHandler->init($this->getUser(), $listing);

        $form = $this->createBookingPriceForm($booking);
        $form->handleRequest($request);

        //Return form if Ajax request
        if ($request->isXmlHttpRequest()) {
            return
                $this->render(
                    '@KisaanCore/Frontend/Booking/form_booking_price.html.twig',
                    array(
                        'form' => $form->createView(),
                        'booking' => $booking
                    )
                );
        } else {//Redirect to new Booking page if no ajax request
            return $this->redirect(
                $this->generateUrl(
                    'kisaan_booking_new',
                    array(
                        'listing_id' => $listing->getId(),
                        'start' => $booking->getStart()->format('Y-m-d-H:i'),
                        'end' => $booking->getEnd()->format('Y-m-d-H:i'),
                    )
                )
            );
        }
    }
}
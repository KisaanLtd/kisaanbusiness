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
use Kisaan\CoreBundle\Event\BookingEvent;
use Kisaan\CoreBundle\Event\BookingEvents;
use Kisaan\CoreBundle\Form\Type\Frontend\BookingNewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Booking controller.
 *
 * @Route("/booking")
 */
class BookingController extends Controller
{
    /**
     * Creates a new Booking entity.
     *
     * @Route("/{listing_id}/{start}/{end}/new",
     *      name="kisaan_booking_new",
     *      requirements={
     *          "listing_id" = "\d+"
     *      },
     * )
     *
     *
     * @Security("is_granted('booking', listing) and not has_role('ROLE_ADMIN') and has_role('ROLE_USER')")
     *
     * @ParamConverter("listing", class="KisaanCoreBundle:Listing", options={"id" = "listing_id"}, converter="doctrine.orm")
     * @ParamConverter("start", options={"format": "Y-m-d-H:i"}, converter="datetime")
     * @ParamConverter("end", options={"format": "Y-m-d-H:i"}, converter="datetime")
     *
     *
     * @Method({"GET", "POST"})
     *
     * @param Request   $request
     * @param Listing   $listing
     * @param \DateTime $start format yyyy-mm-dd-H:i
     * @param \DateTime $end   format yyyy-mm-dd-H:i
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(
        Request $request,
        Listing $listing,
        \DateTime $start,
        \DateTime $end
    ) {
        $bookingHandler = $this->get('kisaan.form.handler.booking');
        $booking = $bookingHandler->init($this->getUser(), $listing, $start, $end);
        //Availability is validated through BookingValidator and amounts are setted through Form Event PRE_SET_DATA
        $form = $this->createCreateForm($booking);

        $success = $bookingHandler->process($form);
        if ($success === 1) {//Success
            $event = new BookingEvent($booking);

            try {
                $this->get('event_dispatcher')->dispatch(BookingEvents::BOOKING_NEW_SUBMITTED, $event);
                $response = $event->getResponse();

                if ($response === null) {//No response means we can create new booking
                    if ($booking) {
                        //New Booking confirmation
                        $this->get('session')->getFlashBag()->add(
                            'success',
                            $this->get('translator')->trans('booking.new.success', array(), 'kisaan_booking')
                        );

                        $response = new RedirectResponse(
                            $this->generateUrl(
                                'kisaan_dashboard_booking_show_asker',
                                array('id' => $booking->getId())
                            )
                        );
                    } else {
                        throw new \Exception('booking.new.form.error');
                    }
                }

                return $response;
            } catch (\Exception $e) {
                //Errors message are created in event subscribers
                $this->get('session')->getFlashBag()->add(
                    'error',
                    /** @Ignore */
                    $this->get('translator')->trans($e->getMessage(), array(), 'kisaan_booking')
                );
            }
        } else {
            $this->addFormMessagesToFlashBag($success);
        }

        //Breadcrumbs
        $breadcrumbs = $this->get('kisaan.breadcrumbs_manager');
        $breadcrumbs->addBookingNewItems($request, $booking);

        return $this->render(
            'KisaanCoreBundle:Frontend/Booking:new.html.twig',
            array(
                'booking' => $booking,
                'form' => $form->createView(),
                //Used to hide errors fields message when a secondary submission (Voucher, Delivery, ...) is done successfully
                'display_errors' => ($success < 2)
            )
        );
    }

    /**
     * Form message for specific bundles
     *
     * @param int $success
     */
    private function addFormMessagesToFlashBag($success)
    {
        $session = $this->get('session');
        $translator = $this->get('translator');

        if ($success === 2) {//Voucher code is valid
            $session->getFlashBag()->add(
                'success_voucher',
                $translator->trans('booking.new.voucher.success', array(), 'kisaan_booking')
            );
        } elseif ($success === 3) {//Delivery is valid
            $session->getFlashBag()->add(
                'success',
                $translator->trans('booking.new.delivery.success', array(), 'kisaan_booking')
            );
        } elseif ($success === 4) {//Options is valid
            $session->getFlashBag()->add(
                'success',
                $translator->trans('booking.new.options.success', array(), 'kisaan_booking')
            );
        } elseif ($success < 0) {//Errors
            $this->addFlashError($success);
        }
    }

    /**
     * @param $success
     */
    private function addFlashError($success)
    {
        $translator = $this->get('translator');
        $errorMsg = $translator->trans('booking.new.unknown.error', array(), 'kisaan_booking'); //-4
        $flashType = 'error';

        if ($success == -1) {
            $errorMsg = $translator->trans('booking.new.form.error', array(), 'kisaan_booking');
        } elseif ($success == -2) {
            $errorMsg = $translator->trans('booking.new.self_booking.error', array(), 'kisaan_booking');
        } elseif ($success == -3) {
            $errorMsg = $translator->trans('booking.new.voucher_code.error', array(), 'kisaan_booking');
            $flashType = 'error_voucher';
        } elseif ($success == -4) {
            $errorMsg = $translator->trans('booking.new.voucher_amount.error', array(), 'kisaan_booking');
            $flashType = 'error_voucher';
        } elseif ($success == -5) {
            $errorMsg = $translator->trans('booking.new.delivery_max.error', array(), 'kisaan_booking');
            $flashType = 'error';
        } elseif ($success == -6) {
            $errorMsg = $translator->trans('booking.new.delivery.error', array(), 'kisaan_booking');
            $flashType = 'error';
        }

        $this->get('session')->getFlashBag()->add($flashType, $errorMsg);
    }

    /**
     * Creates a form to create a Booking entity.
     *
     * @param Booking $booking The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Booking $booking)
    {
        $form = $this->get('form.factory')->createNamed(
            '',
            BookingNewType::class,
            $booking,
            array(
                'method' => 'POST',
                'action' => $this->generateUrl(
                    'kisaan_booking_new',
                    array(
                        'listing_id' => $booking->getListing()->getId(),
                        'start' => $booking->getStart()->format('Y-m-d-H:i'),
                        'end' => $booking->getEnd()->format('Y-m-d-H:i'),
                    )
                ),
            )
        );

        return $form;
    }
}

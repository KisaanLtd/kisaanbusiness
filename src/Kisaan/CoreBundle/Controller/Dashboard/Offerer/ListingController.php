<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\Controller\Dashboard\Offerer;

use Kisaan\CoreBundle\Entity\Listing;
use Kisaan\CoreBundle\Form\Type\Dashboard\ListingEditDurationType;
use Kisaan\CoreBundle\Form\Type\Dashboard\ListingEditPriceType;
use Kisaan\CoreBundle\Form\Type\Dashboard\ListingEditStatusType;
use Kisaan\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Listing Dashboard controller.
 *
 * @Route("/listing")
 */
class ListingController extends Controller
{

    /**
     * @param  Listing $listing
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function statusIndexFormAction($listing)
    {
        $form = $this->createStatusForm($listing, 'index');

        return $this->render(
            '@KisaanCore/Dashboard/Listing/form_status_index.html.twig',
            array(
                'form' => $form->createView(),
                'listing' => $listing
            )
        );
    }

    /**
     * @param  Listing $listing
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function statusNavSideFormAction($listing)
    {
        $form = $this->createStatusForm($listing, 'nav_side');

        return $this->render(
            '@KisaanCore/Dashboard/Listing/form_status_nav_side.html.twig',
            array(
                'form' => $form->createView(),
                'listing' => $listing
            )
        );
    }

    /**
     * @param Listing $listing
     * @param string  $view
     *
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createStatusForm(Listing $listing, $view)
    {
        $form = $this->get('form.factory')->createNamed(
            'listing_status',
            ListingEditStatusType::class,
            $listing,
            array(
                'method' => 'POST',
                'action' => $this->generateUrl(
                        'kisaan_dashboard_listing_edit_status',
                        array('id' => $listing->getId())
                    ) . '?view=' . $view,
            )
        );

        return $form;
    }


    /**
     * Edit Listing status.
     *
     * @Route("/{id}/edit_status", name="kisaan_dashboard_listing_edit_status", requirements={"id" = "\d+"})
     * @Security("is_granted('edit', listing)")
     * @ParamConverter("listing", class="KisaanCoreBundle:Listing")
     *
     * @Method({"POST"})
     *
     * @param Request $request
     * @param         $listing
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editStatusAction(Request $request, Listing $listing)
    {
        $view = $request->get('view');

        $form = $this->createStatusForm($listing, $view);
        $form->handleRequest($request);

        $formIsValid = $form->isSubmitted() && $form->isValid();

        if ($formIsValid) {
            $listing = $this->get("kisaan.listing.manager")->save($listing);
            $this->addFormSuccessMessagesToFlashBag('status');
        }

        if ($request->isXmlHttpRequest()) {
            if ($view == 'index') {
                return $this->statusIndexFormAction($listing);
            } elseif ($view == 'nav_side') {
                return $this->statusNavSideFormAction($listing);
            } else {
                return new Response("View missing");
            }
        } else {
            if (!$formIsValid) {
                $this->addFormErrorMessagesToFlashBag($form);
            }

            return new RedirectResponse($request->headers->get('referer'));
        }
    }

    /**
     * @param  Listing $listing
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function priceFormAction($listing)
    {
        $form = $this->createPriceForm($listing);

        return $this->render(
            '@KisaanCore/Dashboard/Listing/form_price.html.twig',
            array(
                'form' => $form->createView(),
                'listing' => $listing,
                'feeAsOfferer' => $this->getFeeAsOfferer($listing->getUser())
            )
        );
    }

    /**
     * @param Listing $listing
     *
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createPriceForm(Listing $listing)
    {
        $form = $this->get('form.factory')->createNamed(
            'listing_price',
            ListingEditPriceType::class,
            $listing,
            array(
                'method' => 'POST',
                'action' => $this->generateUrl(
                    'kisaan_dashboard_listing_edit_price',
                    array('id' => $listing->getId())
                ),
            )
        );

        return $form;
    }

    /**
     * Edit Listing status.
     *
     * @Route("/{id}/edit_price", name="kisaan_dashboard_listing_edit_price", requirements={"id" = "\d+"})
     * @Security("is_granted('edit', listing)")
     * @ParamConverter("listing", class="KisaanCoreBundle:Listing")
     *
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Listing $listing
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editPriceAction(Request $request, Listing $listing)
    {
        $form = $this->createPriceForm($listing);
        $form->handleRequest($request);

        $formIsValid = $form->isSubmitted() && $form->isValid();
        if ($formIsValid) {
            $listing = $this->get("kisaan.listing.manager")->save($listing);
            $this->addFormSuccessMessagesToFlashBag('price');

            return $this->redirectToRoute(
                'kisaan_dashboard_listing_edit_price',
                array(
                    'id' => $listing->getId()
                )
            );
        }


        if ($request->isXmlHttpRequest()) {
            return $this->render(
                '@KisaanCore/Dashboard/Listing/form_price.html.twig',
                array(
                    'form' => $form->createView(),
                    'listing' => $listing,
                    'feeAsOfferer' => $this->getFeeAsOfferer($listing->getUser())
                )
            );
        } else {
            if (!$formIsValid) {
                $this->addFormErrorMessagesToFlashBag($form);
            }

            return new RedirectResponse($request->headers->get('referer'));
        }
    }

    /**
     * @param User $user
     * @return float|mixed
     */
    private function getFeeAsOfferer(User $user)
    {
        $feeAsOfferer = $this->getParameter('kisaan.fee_as_offerer');
        if ($user->getFeeAsOfferer() || $user->getFeeAsOfferer() === 0) {
            $feeAsOfferer = $user->getFeeAsOfferer() / 100;
        }

        return $feeAsOfferer;
    }

    /**
     * @param  Listing $listing
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function durationFormAction($listing)
    {
        $form = $this->createDurationForm($listing);

        return $this->render(
            '@KisaanCore/Dashboard/Listing/form_duration.html.twig',
            array(
                'form' => $form->createView(),
                'listing' => $listing
            )
        );
    }

    /**
     * @param Listing $listing
     *
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createDurationForm(Listing $listing)
    {
        $form = $this->get('form.factory')->createNamed(
            'listing_duration',
            ListingEditDurationType::class,
            $listing,
            array(
                'method' => 'POST',
                'action' => $this->generateUrl(
                    'kisaan_dashboard_listing_edit_duration',
                    array('id' => $listing->getId())
                ),
            )
        );

        return $form;
    }

    /**
     * Edit Listing duration.
     *
     * @Route("/{id}/edit_duration", name="kisaan_dashboard_listing_edit_duration", requirements={"id" = "\d+"})
     * @Security("is_granted('edit', listing)")
     * @ParamConverter("listing", class="KisaanCoreBundle:Listing")
     *
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Listing $listing
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editDurationAction(Request $request, Listing $listing)
    {
        $form = $this->createDurationForm($listing);
        $form->handleRequest($request);

        $formIsValid = $form->isSubmitted() && $form->isValid();
        if ($formIsValid) {
            $listing = $this->get("kisaan.listing.manager")->save($listing);
            $this->addFormSuccessMessagesToFlashBag('price');
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                '@KisaanCore/Dashboard/Listing/form_duration.html.twig',
                array(
                    'form' => $form->createView(),
                    'listing' => $listing
                )
            );
        } else {
            if (!$formIsValid) {
                $this->addFormErrorMessagesToFlashBag($form);
            }

            return new RedirectResponse($request->headers->get('referer'));
        }
    }

    /**
     * Lists all Listing entities.
     *
     * @Route("/{page}", name="kisaan_dashboard_listing", defaults={"page" = 1 })
     *
     * @Method("GET")
     *
     * @param  Request $request
     * @param  int     $page
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $page)
    {
        $listingManager = $this->get('kisaan.listing.manager');
        $listings = $listingManager->findByOwner(
            $this->getUser()->getId(),
            $request->getLocale(),
            Listing::$visibleStatus,
            $page
        );

        return $this->render(
            'KisaanCoreBundle:Dashboard/Listing:index.html.twig',
            array(
                'listings' => $listings,
                'pagination' => array(
                    'page' => $page,
                    'pages_count' => ceil($listings->count() / $listingManager->maxPerPage),
                    'route' => $request->get('_route'),
                    'route_params' => $request->query->all()
                )
            )
        );

    }

    /**
     * Form Error
     *
     * @param $form
     */
    private function addFormErrorMessagesToFlashBag($form)
    {
        $this->get('kisaan.helper.global')->addFormErrorMessagesToFlashBag(
            $form,
            $this->get('session')->getFlashBag()
        );
    }

    /**
     * Form Success
     *
     * @param $type
     */
    private function addFormSuccessMessagesToFlashBag($type)
    {
        $session = $this->get('session');

        if ($type == 'price') {
            $session->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('listing.edit_price.success', array(), 'kisaan_listing')
            );
        } elseif ($type == 'status') {
            $session->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('listing.edit_status.success', array(), 'kisaan_listing')
            );
        }

    }

    /**
     * @param  Listing $listing
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function completionNoticeAction(Listing $listing)
    {
        $listingCompletion = $listing->getCompletionInformations(
            $this->getParameter("kisaan.listing_img_min")
        );
        $userCompletion = $listing->getUser()->getCompletionInformations(
            $this->getParameter("kisaan.user_img_min")
        );

        return $this->render(
            '@KisaanCore/Dashboard/Listing/_completion_notice.html.twig',
            array(
                'listing_id' => $listing->getId(),
                'listing_title' => $listingCompletion["title"],
                'listing_desc' => $listingCompletion["description"],
                'listing_price' => $listingCompletion["price"],
                'listing_image' => $listingCompletion["image"],
                'listing_characteristics' => $listingCompletion["characteristic"],
                'profile_photo' => $userCompletion["image"],
                'profile_desc' => $userCompletion["description"]
            )
        );
    }

}

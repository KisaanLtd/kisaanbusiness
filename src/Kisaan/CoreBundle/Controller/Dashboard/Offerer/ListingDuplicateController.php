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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Listing Duplicate Dashboard controller.
 *
 * @Route("/listing")
 */
class ListingDuplicateController extends Controller
{
    /**
     * Duplicate Listing.
     *
     * @Route("/{id}/duplicate", name="kisaan_dashboard_listing_duplicate", requirements={"id" = "\d+"})
     * @Security("is_granted('edit', listing)")
     * @ParamConverter("listing", class="KisaanCoreBundle:Listing")
     *
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Listing $listing
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function duplicateAction(Request $request, Listing $listing)
    {
        //Duplicate listing
        $duplicatedListing = $this->get("kisaan.listing.manager")->duplicate($listing);

        if ($duplicatedListing->getId()) {
            //Duplicate availabilities
            $this->get("kisaan.listing_availability.manager")->duplicate(
                $listing->getId(),
                $duplicatedListing->getId(),
                $this->getParameter('kisaan.days_max_edition')
            );

            $url = $this->generateUrl(
                'kisaan_dashboard_listing_edit_presentation',
                array(
                    'id' => $duplicatedListing->getId()
                )
            );

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('listing.duplicate.success', array(), 'kisaan_listing')

            );
        } else {
            $url = $request->headers->get('referer');

            $this->get('session')->getFlashBag()->add(
                'error',
                $this->get('translator')->trans('listing.duplicate.error', array(), 'kisaan_listing')

            );
        }

        return $this->redirect($url);
    }
}

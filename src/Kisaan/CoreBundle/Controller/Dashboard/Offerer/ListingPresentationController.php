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
use Kisaan\CoreBundle\Form\Type\Dashboard\ListingEditDescriptionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Listing Dashboard controller.
 *
 * @Route("/listing")
 */
class ListingPresentationController extends Controller
{
    /**
     * Edits Listing presentation.
     *
     * @Route("/{id}/edit_presentation", name="kisaan_dashboard_listing_edit_presentation", requirements={"id" = "\d+"})
     * @Security("is_granted('edit', listing)")
     * @ParamConverter("listing", class="KisaanCoreBundle:Listing")
     *
     * @Method({"GET", "PUT", "POST"})
     *
     * @param Request $request
     * @param         $listing
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editPresentationAction(Request $request, Listing $listing)
    {
        $translator = $this->get('translator');
        $editForm = $this->createEditPresentationForm($listing);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->get("kisaan.listing.manager")->save($listing);

            $this->get('session')->getFlashBag()->add(
                'success',
                $translator->trans('listing.edit.success', array(), 'kisaan_listing')

            );

            return $this->redirectToRoute(
                'kisaan_dashboard_listing_edit_presentation',
                array('id' => $listing->getId())
            );
        }

        return $this->render(
            'KisaanCoreBundle:Dashboard/Listing:edit_presentation.html.twig',
            array(
                'listing' => $listing,
                'form' => $editForm->createView()
            )
        );

    }

    /**
     * Creates a form to edit a Listing entity.
     *
     * @param Listing $listing The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditPresentationForm(Listing $listing)
    {
        $form = $this->get('form.factory')->createNamed(
            'listing',
            ListingEditDescriptionType::class,
            $listing,
            array(
                'action' => $this->generateUrl(
                    'kisaan_dashboard_listing_edit_presentation',
                    array('id' => $listing->getId())
                ),
                'method' => 'POST',
            )
        );

        return $form;
    }

}

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

use Kisaan\CoreBundle\Entity\Listing;
use Kisaan\CoreBundle\Form\Type\Frontend\ListingNewCategoriesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Listing Dashboard category controller.
 *
 * @Route("/listing")
 */
class ListingCategoriesController extends Controller
{
    /**
     * @param  Listing $listing
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoriesFormAction($listing)
    {
        $form = $this->createCategoriesForm($listing);

        return $this->render(
            '@KisaanCore/Frontend/Listing/form_categories.html.twig',
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
    private function createCategoriesForm(Listing $listing)
    {
        $form = $this->get('form.factory')->createNamed(
            'listing_categories',
            ListingNewCategoriesType::class,
            $listing,
            array(
                'method' => 'POST',
                'action' => $this->generateUrl(
                    'kisaan_dashboard_listing_new_categories'
                ),
            )
        );

        return $form;
    }

    /**
     * New Listing categories in ajax mode.
     *
     * @Route("/new_categories", name="kisaan_dashboard_listing_new_categories")
     *
     * @Method({"POST", "GET"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newCategoriesAction(Request $request)
    {
        $listing = new Listing();
        $listing = $this->get('kisaan.form.handler.listing')->addCategories($listing);
        $form = $this->createCategoriesForm($listing);
        $form->handleRequest($request);

        $formIsValid = $form->isSubmitted() && $form->isValid();
//        if ($formIsValid) {
//
//        }

        if ($request->isXmlHttpRequest()) {
            return $this->render(
                'KisaanCoreBundle:Frontend/Listing:form_categories.html.twig',
                array(
                    'listing' => $listing,
                    'form' => $form->createView()
                )
            );
        } else {
            if (!$formIsValid) {
                $this->get('kisaan.helper.global')->addFormErrorMessagesToFlashBag(
                    $form,
                    $this->get('session')->getFlashBag()
                );
            }

            return new RedirectResponse($request->headers->get('referer'));
        }
    }
}

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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Currency controller.
 *
 * @Route("/currency")
 */
class CurrencyController extends Controller
{
    /**
     * Switch currency in session
     *
     * @Route("/{currency}/switch", name="kisaan_currency_switch",
     *      requirements={"currency" = "%kisaan.currencies_string%"}
     * )
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string  $currency
     *
     * @return RedirectResponse
     */
    public function switchAction(Request $request, $currency)
    {
        $this->get('session')->set('currency', $currency);

        if ($request->headers->get('referer')) {
            return $this->redirect($request->headers->get('referer'));
        } else {
            return $this->redirect($this->generateUrl('kisaan_home'));
        }
    }

}

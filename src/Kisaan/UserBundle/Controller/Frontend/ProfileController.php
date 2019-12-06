<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\UserBundle\Controller\Frontend;

use Kisaan\CoreBundle\Entity\Listing;
use Kisaan\UserBundle\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Controller managing the user profile
 *
 * @Route("/user")
 */
class ProfileController extends Controller
{
    /**
     * Show user profile
     *
     * @Route("/{id}/show", name="kisaan_user_profile_show", requirements={
     *      "id" = "\d+"
     * })
     * @Method("GET")
     * @ParamConverter("user", class="KisaanUserBundle:User")
     *
     * @param  Request $request
     * @param  User    $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     */

    public function showAction(Request $request, User $user)
    {
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $userListings = $this->get('doctrine')->getManager()->getRepository('KisaanCoreBundle:Listing')->findByOwner(
            $user->getId(),
            $request->getLocale(),
            array(Listing::STATUS_PUBLISHED)
        );

        //Breadcrumbs
        $breadcrumbs = $this->get('kisaan.breadcrumbs_manager');
        $breadcrumbs->addProfileShowItems($request, $user);

        return $this->render(
            'KisaanUserBundle:Frontend/Profile:show.html.twig',
            array(
                'user' => $user,
                'user_listings' => $userListings
            )
        );
    }
}

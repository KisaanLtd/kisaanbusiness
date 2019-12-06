<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Kisaan\UserBundle\Controller\Dashboard;

use Kisaan\UserBundle\Form\Type\ProfileContactFormType;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ProfileController
 *
 * @Route("/user")
 */
class ProfileContactController extends Controller
{
    /**
     * Edit user profile
     *
     * @Route("/edit-contact", name="kisaan_user_dashboard_profile_edit_contact")
     * @Method({"GET", "POST"})
     *
     * @param $request Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $formHandler = $this->get('kisaan_user.form.handler.contact');
        $user = $formHandler->init($user);

        $form = $this->createContactForm($user);
        $success = $formHandler->process($form);

        $session = $this->get('session');
        $translator = $this->get('translator');

        if ($success > 0) {
            $session->getFlashBag()->add(
                'success',
                $translator->trans('user.edit.contact.success', array(), 'kisaan_user')
            );

            return $this->redirect(
                $this->generateUrl(
                    'kisaan_user_dashboard_profile_edit_contact'
                )
            );
        } elseif ($success < 0) {
            $session->getFlashBag()->add(
                'error',
                $translator->trans('user.edit.contact.error', array(), 'kisaan_user')
            );
        }

        return $this->render(
            'KisaanUserBundle:Dashboard/Profile:edit_contact.html.twig',
            array(
                'form' => $form->createView(),
                'user' => $user
            )
        );
    }

    /**
     * Creates a form to edit a user entity.
     *
     * @param mixed $user
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createContactForm($user)
    {
        $form = $this->get('form.factory')->createNamed(
            'user',
            ProfileContactFormType::class,
            $user,
            array(
                'method' => 'POST',
                'action' => $this->generateUrl('kisaan_user_dashboard_profile_edit_contact'),
            )
        );

        return $form;
    }
}

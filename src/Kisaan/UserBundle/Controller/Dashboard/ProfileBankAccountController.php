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

use Kisaan\UserBundle\Form\Type\ProfileBankAccountFormType;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class BankAccountFormHandler
 *
 * @Route("/user")
 */
class ProfileBankAccountController extends Controller
{
    /**
     * Edit user profile
     *
     * @Route("/edit-bank-account", name="kisaan_user_dashboard_profile_edit_bank_account")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->createBankAccountForm($user);
        $success = $this->get('kisaan_user.form.handler.bank_account')->process($form);

        $session = $this->get('session');
        $translator = $this->get('translator');

        if ($success > 0) {
            $session->getFlashBag()->add(
                'success',
                $translator->trans('user.edit.payment.success', array(), 'kisaan_user')
            );

            return $this->redirect(
                $this->generateUrl(
                    'kisaan_user_dashboard_profile_edit_bank_account'
                )
            );
        } elseif ($success < 0) {
            $session->getFlashBag()->add(
                'error',
                $translator->trans('user.edit.payment.error', array(), 'kisaan_user')
            );
        }

        return $this->render(
            'KisaanUserBundle:Dashboard/Profile:edit_bank_account.html.twig',
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
    private function createBankAccountForm($user)
    {
        $form = $this->get('form.factory')->createNamed(
            'user',
            ProfileBankAccountFormType::class,
            $user,
            array(
                'method' => 'POST',
                'action' => $this->generateUrl('kisaan_user_dashboard_profile_edit_bank_account'),
            )
        );

        return $form;
    }
}

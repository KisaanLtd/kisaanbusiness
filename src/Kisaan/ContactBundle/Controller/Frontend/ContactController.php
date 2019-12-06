<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\ContactBundle\Controller\Frontend;

use Kisaan\ContactBundle\Entity\Contact;
use Kisaan\ContactBundle\Form\Type\Frontend\ContactNewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Booking controller.
 *
 * @Route("/contact")
 */
class ContactController extends Controller
{
    /**
     * Creates a new Contact entity.
     *
     * @Route("/new", name="kisaan_contact_new")
     *
     * @Method({"GET", "POST"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $contact = new Contact();
        $form = $this->createCreateForm($contact);

        $submitted = $this->get('kisaan_contact.form.handler.contact')->process($form);
        if ($submitted !== false) {
            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('contact.new.success', array(), 'kisaan_contact')
            );

            return $this->redirect($this->generateUrl('kisaan_contact_new'));
        }

        return $this->render(
            'KisaanContactBundle:Frontend:index.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * Creates a form to create a contact entity.
     *
     * @param Contact $contact The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Contact $contact)
    {
        $form = $this->get('form.factory')->createNamed(
            '',
            ContactNewType::class,
            $contact,
            array(
                'method' => 'POST',
                'action' => $this->generateUrl('kisaan_contact_new')
            )
        );

        return $form;
    }
}

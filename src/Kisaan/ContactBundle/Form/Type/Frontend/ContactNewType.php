<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\ContactBundle\Form\Type\Frontend;

use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class ContactNewType extends AbstractType implements TranslationContainerInterface
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstName',
                null,
                array(
                    'label' => 'contact.form.first_name.label'
                )
            )
            ->add(
                'lastName',
                null,
                array(
                    'label' => 'contact.form.last_name.label'
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'contact.form.email.label'
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'contact.form.phone.label'
                )
            )
            ->add(
                'subject',
                null,
                array(
                    'label' => 'contact.form.subject.label'
                )
            )
            ->add(
                'message',
                null,
                array(
                    'label' => 'contact.form.message.label'
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Kisaan\ContactBundle\Entity\Contact',
                'translation_domain' => 'kisaan_contact',
                'constraints' => new Valid(),
                'validation_groups' => array('KisaanContact'),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'contact_new';
    }

    /**
     * JMS Translation messages
     *
     * @return array
     */
    public static function getTranslationMessages()
    {
        $messages[] = new Message("entity.contact.status.new", 'kisaan_contact');
        $messages[] = new Message("entity.contact.status.read", 'kisaan_contact');

        $messages[] = new Message("kisaan_contact.first_name.blank", 'kisaan_contact');
        $messages[] = new Message("kisaan_contact.first_name.short", 'kisaan_contact');
        $messages[] = new Message("kisaan_contact.first_name.long", 'kisaan_contact');

        $messages[] = new Message("kisaan_contact.last_name.blank", 'kisaan_contact');
        $messages[] = new Message("kisaan_contact.last_name.short", 'kisaan_contact');
        $messages[] = new Message("kisaan_contact.last_name.long", 'kisaan_contact');

        $messages[] = new Message("kisaan_contact.email.invalid", 'kisaan_contact');
        $messages[] = new Message("kisaan_contact.email.blank", 'kisaan_contact');
        $messages[] = new Message("kisaan_contact.email.short", 'kisaan_contact');
        $messages[] = new Message("kisaan_contact.email.long", 'kisaan_contact');

        $messages[] = new Message("kisaan_contact.phone.short", 'kisaan_contact');
        $messages[] = new Message("kisaan_contact.phone.long", 'kisaan_contact');

        $messages[] = new Message("kisaan_contact.subject.blank", 'kisaan_contact');
        $messages[] = new Message("kisaan_contact.subject.short", 'kisaan_contact');
        $messages[] = new Message("kisaan_contact.subject.long", 'kisaan_contact');

        $messages[] = new Message("kisaan_contact.message.blank", 'kisaan_contact');
        $messages[] = new Message("kisaan_contact.message.short", 'kisaan_contact');

        return $messages;
    }
}

<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\Form\Type\Dashboard;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Kisaan\CoreBundle\Form\Type\LanguageFilteredType;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ListingEditDescriptionType extends ListingEditType implements TranslationContainerInterface
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        //Translations fields
        $titles = $descriptions = $rules = array();
        foreach ($this->locales as $i => $locale) {
            $titles[$locale] = array(
                /** @Ignore */
                'label' => "listing.form.title.$locale",
                'constraints' => array(new NotBlank())
            );
            $descriptions[$locale] = array(
                /** @Ignore */
                'label' => "listing.form.description.$locale",
                'constraints' => array(new NotBlank())
            );
            $rules[$locale] = array(
                /** @Ignore */
                'label' => "listing.form.rules.$locale"
            );
        }

        $builder
            ->add(
                'translations',
                TranslationsType::class,
                array(
                    'required_locales' => array($this->locale),
                    'fields' => array(
                        'title' => array(
                            'field_type' => 'text',
                            'locale_options' => $titles
                        ),
                        'description' => array(
                            'field_type' => 'textarea',
                            'locale_options' => $descriptions
                        ),
                        'rules' => array(
                            'field_type' => 'textarea',
                            'locale_options' => $rules
                        ),
                        'slug' => array(
                            'display' => false
                        )
                    ),
                    /** @Ignore */
                    'label' => false
                )
            )
            ->add(
                'fromLang',
                LanguageFilteredType::class,
                array(
                    'mapped' => false,
                    'label' => 'kisaan.from',
                    'data' => $this->locale,
                    'translation_domain' => 'kisaan_user'
                )
            )
            ->add(
                'toLang',
                LanguageFilteredType::class,
                array(
                    'mapped' => false,
                    'label' => 'kisaan.to',
                    'data' => LanguageFilteredType::getLocaleTo($this->locales, $this->locale),
                    'translation_domain' => 'kisaan_user'
                )
            );

        //Status field already added
        //$builder->remove('status');
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'listing_edit_description';
    }

    /**
     * JMS Translation messages
     *
     * @return array
     */
    public static function getTranslationMessages()
    {
        $messages[] = new Message("listing.form.title.en", 'kisaan_listing');
        $messages[] = new Message("listing.form.title.fr", 'kisaan_listing');
        $messages[] = new Message("listing.form.description.en", 'kisaan_listing');
        $messages[] = new Message("listing.form.description.fr", 'kisaan_listing');
        $messages[] = new Message("listing.form.rules.en", 'kisaan_listing');
        $messages[] = new Message("listing.form.rules.fr", 'kisaan_listing');
        $messages[] = new Message("kisaan.en", 'kisaan_listing');
        $messages[] = new Message("kisaan.fr", 'kisaan_listing');
        $messages[] = new Message("listing_translations_en_title_placeholder", 'kisaan_listing');
        $messages[] = new Message("listing_translations_fr_title_placeholder", 'kisaan_listing');
        $messages[] = new Message("listing_translations_en_description_placeholder", 'kisaan_listing');
        $messages[] = new Message("listing_translations_fr_description_placeholder", 'kisaan_listing');
        $messages[] = new Message("listing_translations_en_rules_placeholder", 'kisaan_listing');
        $messages[] = new Message("listing_translations_fr_rules_placeholder", 'kisaan_listing');

        return $messages;
    }

}

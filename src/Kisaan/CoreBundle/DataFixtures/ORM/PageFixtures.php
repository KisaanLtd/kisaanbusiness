<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\DataFixtures\ORM;

use Kisaan\PageBundle\Entity\Page;
use Kisaan\PageBundle\Entity\PageTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PageFixtures extends Fixture
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        //Page Who we are
        $page = new Page();
        $page->setPublished(true);

        $page->translate('en')->setMetaTitle('Who we are?');
        $page->translate('fr')->setMetaTitle('Qui sommes nous?');

        $page->translate('en')->setTitle('Who we are?');
        $page->translate('fr')->setTitle('Qui sommes nous?');

        $page->translate('en')->setMetaDescription('in progress');
        $page->translate('fr')->setMetaDescription('en cours');

        $page->translate('en')->setDescription(
            '<p>We are shared services marketplace platform!</p>
            <h3>What is it?</h3>
            <p>A Common Platform where Farmers & Agriculture Stake Holders can borrow and offer Agriculture services, agriculture Equipments and Agriculture Manpower</p>
            <h3>Who&rsquo;s maintaining this platform?</h3>
            <p><a href="http://www.kisaaninfotech.com" target="_blank" title="marketplace solution">KISAAN INFOTECH LTD</a>&nbsp;is. we are the India based infotech agency specialised in building collaborative marketplaces for the rental and service industry.</p>
            <h3>What are the common features of this platform?</h3>
            <p>KisaanBusiness: (1) peer to peer (2) actively maintained supported.(3) Multi-Channel Access </p>
            <h3>How should I contact you?</h3>
            <p>Here: you may contact us through an email, Message on WhatsApp, Twitter or even call our customer care representative.</p>
            <h3>Do you have a video presentation?</h3>
            <p>Here&rsquo;s an introductory video of our platform;: <a href="https://www.youtube.com/channel/UCWFCXOanuZTFnmbHVLzj8Vw">Kisaan Business youtube Channel</a></p>
            '
        );
        $page->translate('fr')->setDescription(
            '<p>Nous sommes Kisaan bien s&ucirc;r !</p>
            <h3>Qu&#39;est-ce que c&#39;est?</h3>
            <p>Kisaan est un projet open source d&eacute;di&eacute; &agrave; la r&eacute;alisation d&#39;une solution puissante (et gratuite) pour les places de march&eacute; collaboratives (ou pas &agrave; vrai dire).</p>
            <h3>Who&rsquo;s maintaining this platform?</h3>
            <p><a href="http://www.kisaaninfotech.com" target="_blank" title="marketplace solution">KISAAN INFOTECH LTD</a>&nbsp;is. we are the India based infotech agency specialised in building collaborative marketplaces for the rental and service industry.</p>
            <h3>What are the common features of this platform?</h3>
            <p>KisaanBusiness: (1) peer to peer (2) actively maintained supported.(3) Multi-Channel Access</p>
            <h3>O&ugrave; puis-je l&rsquo;obtenir?</h3>
            <p>Here: you may contact us through an email, Message on WhatsApp, Twitter or even call our customer care representative.</p>
            <h3>Do you have a video presentation?</h3>
            <p>Here&rsquo;s an introductory video of our platform;: <a href="https://www.youtube.com/channel/UCWFCXOanuZTFnmbHVLzj8Vw">Kisaan Business youtube Channel</a></p>'
        );


        //Page How it Works
        $page1 = new Page();
        $page1->setPublished(true);

        $page1->translate('en')->setMetaTitle('How it works?');
        $page1->translate('fr')->setMetaTitle('Comment ca marche?');

        $page1->translate('en')->setTitle('How it works?');
        $page1->translate('fr')->setTitle('Comment ca marche?');

        $page1->translate('en')->setMetaDescription('in progress');
        $page1->translate('fr')->setMetaDescription('en cours');

        $page1->translate('en')->setDescription('in progress');
        $page1->translate('fr')->setDescription('en cours');

        //Page The team
        $page2 = new Page();
        $page2->setPublished(true);

        $page2->translate('en')->setMetaTitle('The team');
        $page2->translate('fr')->setMetaTitle('L\'équipe');

        $page2->translate('en')->setTitle('The team');
        $page2->translate('fr')->setTitle('L\'équipe');

        $page2->translate('en')->setMetaDescription('in progress');
        $page2->translate('fr')->setMetaDescription('en cours');

        $page2->translate('en')->setDescription('in progress');
        $page2->translate('fr')->setDescription('en cours');

        //Page FAQ
        $page3 = new Page();
        $page3->setPublished(true);

        $page3->translate('en')->setMetaTitle('FAQ');
        $page3->translate('fr')->setMetaTitle('FAQ');

        $page3->translate('en')->setTitle('FAQ');
        $page3->translate('fr')->setTitle('FAQ');

        $page3->translate('en')->setMetaDescription('in progress');
        $page3->translate('fr')->setMetaDescription('en cours');

        $page3->translate('en')->setDescription('in progress');
        $page3->translate('fr')->setDescription('en cours');


        //Page Legal notices
        $page4 = new Page();
        $page4->setPublished(true);

        $page4->translate('en')->setMetaTitle('Legal notices');
        $page4->translate('fr')->setMetaTitle('Mentions légales');

        $page4->translate('en')->setTitle('Legal notices');
        $page4->translate('fr')->setTitle('Mentions légales');

        $page4->translate('en')->setMetaDescription('in progress');
        $page4->translate('fr')->setMetaDescription('en cours');

        $page4->translate('en')->setDescription('in progress');
        $page4->translate('fr')->setDescription('en cours');


        $page5 = new Page();
        $page5->setPublished(true);

        $page5->translate('en')->setMetaTitle('Terms of use');
        $page5->translate('fr')->setMetaTitle('Conditions générales d\'utilisation');

        $page5->translate('en')->setTitle('Terms of use');
        $page5->translate('fr')->setTitle('Conditions générales d\'utilisation');

        $page5->translate('en')->setMetaDescription('in progress');
        $page5->translate('fr')->setMetaDescription('en cours');

        $page5->translate('en')->setDescription('in progress');
        $page5->translate('fr')->setDescription('en cours');

        $manager->persist($page);
        $manager->persist($page1);
        $manager->persist($page2);
        $manager->persist($page3);
        $manager->persist($page4);
        $manager->persist($page5);

        $page->mergeNewTranslations();
        $page1->mergeNewTranslations();
        $page2->mergeNewTranslations();
        $page3->mergeNewTranslations();
        $page4->mergeNewTranslations();
        $page5->mergeNewTranslations();

        $manager->flush();

        /** @var PageTranslation $translation */
        foreach ($page->getTranslations() as $i => $translation) {
            $translation->generateSlug();
        }
        foreach ($page1->getTranslations() as $i => $translation) {
            $translation->generateSlug();
        }
        foreach ($page2->getTranslations() as $i => $translation) {
            $translation->generateSlug();
        }
        foreach ($page3->getTranslations() as $i => $translation) {
            $translation->generateSlug();
        }
        foreach ($page4->getTranslations() as $i => $translation) {
            $translation->generateSlug();
        }
        foreach ($page5->getTranslations() as $i => $translation) {
            $translation->generateSlug();
        }
        $manager->persist($page);
        $manager->persist($page1);
        $manager->persist($page2);
        $manager->persist($page3);
        $manager->persist($page4);
        $manager->persist($page5);

        $manager->flush();

        $this->addReference('who-we-are', $page);
        $this->addReference('how-it-works', $page1);
        $this->addReference('the-team', $page2);
        $this->addReference('faq', $page3);
        $this->addReference('legal-notice', $page4);
        $this->addReference('terms-of-use', $page5);

    }


}

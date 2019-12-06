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

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Lexik\Bundle\CurrencyBundle\Entity\Currency;

class CurrencyFixtures extends Fixture
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $currency = new Currency();
        $currency->setCode('EUR');
        $currency->setRate(1.0000);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setCode('USD');
        $currency->setRate(1.2448);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setCode('JPY');
        $currency->setRate(145.8900);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setCode('GBP');
        $currency->setRate(0.7932);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setCode('CHF');
        $currency->setRate(1.10);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setCode('RUB');
        $currency->setRate(70.554);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setCode('AUD');
        $currency->setRate(1.6228);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setCode('CAD');
        $currency->setRate(1.4614);
        $manager->persist($currency);

        $currency = new Currency();
        $currency->setCode('INR');
        $currency->setRate(78.91);
        $manager->persist($currency);

        $manager->flush();
    }

}

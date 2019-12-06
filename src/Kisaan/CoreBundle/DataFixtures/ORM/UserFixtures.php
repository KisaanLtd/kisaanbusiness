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

use Kisaan\UserBundle\Entity\User;
use Kisaan\UserBundle\Event\UserEvent;
use Kisaan\UserBundle\Event\UserEvents;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserFixtures extends Fixture implements ContainerAwareInterface
{
    /** @var  ContainerInterface container */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('kisaan_user.user_manager');
        $locale = $this->container->getParameter('kisaan.locale');
        $timeZone = $this->getDefaultUserTimeZone();

        /** @var  User $user */
        $user = $userManager->createUser();
        $user->setPersonType(User::PERSON_TYPE_NATURAL);
        $user->setUsername('offerer@kisaan.rocks');
        $user->setEmail('offerer@kisaan.rocks');
        $user->setPlainPassword('12345678');
        $user->setLastName('OffererName');
        $user->setFirstName('OffererFirstName');
        $user->setCountryOfResidence('FR');
        $user->setBirthday(new DateTime('1973-05-29'));
        $user->setEnabled(true);
        $user->setAnnualIncome(1000);
        $user->setEmailVerified(true);
        $user->setPhoneVerified(false);
        $user->setMotherTongue($locale);
        $user->setTimeZone($timeZone);

        $event = new UserEvent($user);
        $this->container->get('event_dispatcher')->dispatch(UserEvents::USER_REGISTER, $event);
        $user = $event->getUser();

        $userManager->updateUser($user);
        $this->addReference('offerer', $user);

        $user = $userManager->createUser();
        $user->setPersonType(User::PERSON_TYPE_NATURAL);
        $user->setUsername('asker@kisaan.rocks');
        $user->setEmail('asker@kisaan.rocks');
        $user->setPlainPassword('12345678');
        $user->setLastName('AskerName');
        $user->setFirstName('AskerFirstName');
        $user->setCountryOfResidence('IN');
        $user->setBirthday(new DateTime('1975-08-27'));
        $user->setEnabled(true);
        $user->setAnnualIncome(1000);
        $user->setMotherTongue($locale);
        $user->setTimeZone($timeZone);

        $event = new UserEvent($user);
        $this->container->get('event_dispatcher')->dispatch(UserEvents::USER_REGISTER, $event);
        $user = $event->getUser();

        $userManager->updateUser($user);
        $this->addReference('asker', $user);

        $user = $userManager->createUser();
        $user->setPersonType(User::PERSON_TYPE_NATURAL);
        $user->setUsername('disableuser@kisaan.rocks');
        $user->setEmail('disableuser@kisaan.rocks');
        $user->setPlainPassword('12345678');
        $user->setLastName('DisableUserLastName');
        $user->setFirstName('DisableUserFirstName');
        $user->setCountryOfResidence('IN');
        $user->setBirthday(new DateTime('1978-08-27'));
        $user->setEnabled(false);
        $user->setAnnualIncome(1000);
        $user->setMotherTongue($locale);
        $user->setTimeZone($timeZone);

        $event = new UserEvent($user);
        $this->container->get('event_dispatcher')->dispatch(UserEvents::USER_REGISTER, $event);
        $user = $event->getUser();

        $userManager->updateUser($user);
        $this->addReference('disable-user', $user);

        $user = $userManager->createUser();
        $user->setPersonType(User::PERSON_TYPE_NATURAL);
        $user->setLastName('super-admin');
        $user->setFirstName('super-admin');
        $user->setUsername('super-admin@kisaan.rocks');
        $user->setEmail('super-admin@kisaan.rocks');
        $user->setPlainPassword('super-admin');
        $user->setCountryOfResidence('IN');
        $user->setBirthday(new DateTime('1978-07-01'));
        $user->setEnabled(true);
        $user->setTimeZone($timeZone);
        $user->addRole('ROLE_SUPER_ADMIN');

        $event = new UserEvent($user);
        $this->container->get('event_dispatcher')->dispatch(UserEvents::USER_REGISTER, $event);
        $user = $event->getUser();

        $userManager->updateUser($user);
        $this->addReference('super-admin', $user);
    }

    /**
     * Get default user time zone
     * If time unit is day default user timezone = UTC else user timezone = default app time zone
     * @return mixed|string
     */
    private function getDefaultUserTimeZone()
    {
        $userTimeZone = 'UTC';
        $timeUnitIsDay = ($this->container->getParameter('kisaan.time_unit') % 1440 == 0) ? true : false;
        if (!$timeUnitIsDay) {
            $userTimeZone = $this->container->getParameter('kisaan.time_zone');
        }

        return $userTimeZone;
    }
}

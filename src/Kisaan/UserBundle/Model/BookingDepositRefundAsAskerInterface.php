<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\UserBundle\Model;

use Kisaan\UserBundle\Entity\User;

interface BookingDepositRefundAsAskerInterface
{
    /**
     * @param User $asker
     * @return mixed
     */
    public function setAsker(User $asker);

    /**
     * @return User
     */
    public function getAsker();
}
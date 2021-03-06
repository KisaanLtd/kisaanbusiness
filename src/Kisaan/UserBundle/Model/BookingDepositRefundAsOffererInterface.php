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

interface BookingDepositRefundAsOffererInterface
{
    /**
     * @param User $offerer
     * @return mixed
     */
    public function setOfferer(User $offerer);

    /**
     * @return User
     */
    public function getOfferer();
}
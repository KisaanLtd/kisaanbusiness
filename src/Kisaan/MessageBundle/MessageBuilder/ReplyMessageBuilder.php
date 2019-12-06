<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\MessageBundle\MessageBuilder;

use FOS\MessageBundle\MessageBuilder\AbstractMessageBuilder;

/**
 * Fluent interface message builder for reply to a thread
 *
 *
 */
class ReplyMessageBuilder extends AbstractMessageBuilder
{

    /**
     * Sets $createdAt message.
     *
     * @param  date
     * @return $this (fluent interface)
     */
    public function setCreatedAt($createdAt)
    {
        $this->message->setCreatedAt($createdAt);

        return $this;
    }

}

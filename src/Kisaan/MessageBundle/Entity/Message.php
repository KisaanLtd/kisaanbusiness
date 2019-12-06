<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\Message as BaseMessage;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;

/**
 * @ORM\Entity(repositoryClass="Kisaan\MessageBundle\Repository\MessageRepository")
 *
 * @ORM\Table(name="message")
 */
class Message extends BaseMessage
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Kisaan\MessageBundle\Entity\Thread",
     *   inversedBy="messages"
     * )
     * @var ThreadInterface
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Kisaan\UserBundle\Entity\User",
     *   inversedBy="messages")
     * @var ParticipantInterface
     */
    protected $sender;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Kisaan\MessageBundle\Entity\MessageMetadata",
     *   mappedBy="message",
     *   cascade={"all"}
     * )
     * @var MessageMetadata
     */
    protected $metadata;

    public function setCreatedAt($createdAt)
    {
        return $this->createdAt = $createdAt;
    }
}

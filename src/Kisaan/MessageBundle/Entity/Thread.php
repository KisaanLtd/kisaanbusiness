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

use Kisaan\CoreBundle\Entity\Booking;
use Kisaan\CoreBundle\Entity\Listing;
use Kisaan\CoreBundle\Model\BaseBooking;
use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\Thread as BaseThread;

/**
 * @ORM\Entity(repositoryClass="Kisaan\MessageBundle\Repository\ThreadRepository")
 *
 * @ORM\Table(name="message_thread")
 */
class Thread extends BaseThread
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Kisaan\UserBundle\Entity\User")
     */
    protected $createdBy;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Kisaan\MessageBundle\Entity\Message",
     *   mappedBy="thread"
     * )
     * @var Message[]|\Doctrine\Common\Collections\Collection
     */
    protected $messages;

    /**
     * @ORM\OneToMany(
     *   targetEntity="Kisaan\MessageBundle\Entity\ThreadMetadata",
     *   mappedBy="thread",
     *   cascade={"all"}
     * )
     * @var ThreadMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

    /**
     * @ORM\ManyToOne(targetEntity="\Kisaan\CoreBundle\Entity\Listing", inversedBy="threads")
     * @ORM\JoinColumn(name="listing_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $listing;

    /**
     * @ORM\OneToOne(targetEntity="\Kisaan\CoreBundle\Entity\Booking", inversedBy="thread")
     * @ORM\JoinColumn(name="booking_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $booking;

    /**
     * @return Listing
     */
    public function getListing()
    {
        return $this->listing;
    }

    /**
     * @param Listing $listing
     * @return $this
     */
    public function setListing(Listing $listing)
    {
        $this->listing = $listing;

        return $this;
    }

    /**
     * @return Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * @param  BaseBooking $booking
     * @return $this
     */
    public function setBooking(BaseBooking $booking = null)
    {
        $this->booking = $booking;

        return $this;
    }

    public function __toString()
    {
        return "" . $this->getId();
    }

    /**
     * @param Message $message
     */
    public function removeMessage(Message $message)
    {
        $this->messages->removeElement($message);
    }


}

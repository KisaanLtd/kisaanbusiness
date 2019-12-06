<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Kisaan\CoreBundle\Entity;

use Kisaan\CoreBundle\Model\BaseListingLocation;
use Kisaan\GeoBundle\Entity\Coordinate;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ListingLocation
 *
 * @ORM\Table(name="listing_location")
 *
 * @ORM\Entity
 */
class ListingLocation extends BaseListingLocation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Kisaan\CoreBundle\Entity\Listing", mappedBy="location")
     * @ORM\JoinColumn(name="listing_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $listing;

    /**
     * @Assert\NotBlank(message="assert.not_blank")
     *
     * @ORM\ManyToOne(targetEntity="Kisaan\GeoBundle\Entity\Coordinate", inversedBy="listingLocations", cascade={"persist"})
     * @ORM\JoinColumn(name="coordinate_id", referencedColumnName="id", nullable=false)
     *
     * @var Coordinate
     */
    private $coordinate;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set coordinate
     *
     * @param  \Kisaan\GeoBundle\Entity\Coordinate $coordinate
     * @return ListingLocation
     */
    public function setCoordinate(Coordinate $coordinate)
    {
        $this->coordinate = $coordinate;

        return $this;
    }

    /**
     * Get coordinate
     *
     * @return \Kisaan\GeoBundle\Entity\Coordinate
     */
    public function getCoordinate()
    {
        return $this->coordinate;
    }

    /**
     * Set listing
     *
     * @param  \Kisaan\CoreBundle\Entity\Listing $listing
     * @return ListingLocation
     */
    public function setListing(Listing $listing = null)
    {
        $this->listing = $listing;

        return $this;
    }

    /**
     * Get listing
     *
     * @return \Kisaan\CoreBundle\Entity\Listing
     */
    public function getListing()
    {
        return $this->listing;
    }

    /**
     * @return string
     */
    public function getCompleteAddress()
    {
        $address = $this->getStreetNumber() . " " . $this->getRoute() . ", " . $this->getZip() . " " . $this->getCity();
        if ($this->getCoordinate()) {
            $address .= ", " . $this->getCoordinate()->getCountry();
        }

        return $address;
    }

    public function __clone()
    {
        if ($this->id) {
            $this->id = null;

            $coordinate = $this->getCoordinate();
            $this->setCoordinate(clone $coordinate);
        }
    }
}

<?php

/*
* This file is part of the Kisaan package.
*
* (c) Cocolabs SAS <contact@cocolabs.io>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Kisaan\GeoBundle\Model;

use Kisaan\GeoBundle\Entity\Geocoding;

/**
 * Geocodable trait.
 *
 * Should be used inside entity, that needs to be Geocodable.
 */
trait GeocodableTrait
{
    /**
     * @var Geocoding
     * @ORM\OneToOne(targetEntity="Kisaan\GeoBundle\Entity\Geocoding", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="geocoding_id", referencedColumnName="id", onDelete="SET NULL")
     **/
    protected $geocoding;


    /**
     * @return Geocoding
     */
    public function getGeocoding()
    {
        return $this->geocoding;
    }

    /**
     * @param Geocoding $geocoding
     */
    public function setGeocoding($geocoding)
    {
        $this->geocoding = $geocoding;
    }
}

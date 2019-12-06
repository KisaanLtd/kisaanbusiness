<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\Routing;

use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class ExtraBundleLoader extends Loader
{
    protected $bundles;
    protected $env;

    public function __construct(array $bundles, $env)
    {
        $this->bundles = $bundles;
        $this->env = $env;
    }

    /**
     * Add routing from extra bundles
     *
     * @param mixed $resource
     * @param null  $type
     * @return RouteCollection
     * @throws FileLoaderLoadException
     */
    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();

        try {
            if (array_key_exists("KisaanMangoPayBundle", $this->bundles)) {
                $resource = '@KisaanMangoPayBundle/Resources/config/routing.yml';
                $type = 'yaml';
                $importedRoutes = $this->import($resource, $type);
                $collection->addCollection($importedRoutes);
            }

            if (array_key_exists("KisaanMangoPayCardSavingBundle", $this->bundles)) {
                $resource = '@KisaanMangoPayCardSavingBundle/Resources/config/routing.yml';
                $type = 'yaml';
                $importedRoutes = $this->import($resource, $type);
                $collection->addCollection($importedRoutes);
            }

            if (array_key_exists("KisaanListingAlertBundle", $this->bundles)) {
                $resource = '@KisaanListingAlertBundle/Resources/config/routing.yml';
                $type = 'yaml';
                $importedRoutes = $this->import($resource, $type);
                $collection->addCollection($importedRoutes);
            }

            if (array_key_exists("KisaanListingOptionBundle", $this->bundles)) {
                $resource = '@KisaanListingOptionBundle/Resources/config/routing.yml';
                $type = 'yaml';
                $importedRoutes = $this->import($resource, $type);
                $collection->addCollection($importedRoutes);
            }

            if (array_key_exists("KisaanSMSBundle", $this->bundles) && $this->env == 'dev') {
                $resource = '@KisaanSMSBundle/Resources/config/routing_dev.yml';
                $type = 'yaml';
                $importedRoutes = $this->import($resource, $type);
                $collection->addCollection($importedRoutes);
            }

            if (array_key_exists("KisaanListingCategoryFieldBundle", $this->bundles)) {
                $resource = '@KisaanListingCategoryFieldBundle/Resources/config/routing.yml';
                $type = 'yaml';
                $importedRoutes = $this->import($resource, $type);
                $collection->addCollection($importedRoutes);
            }

            if (array_key_exists("KisaanListingDepositBundle", $this->bundles)) {
                $resource = '@KisaanListingDepositBundle/Resources/config/routing.yml';
                $type = 'yaml';
                $importedRoutes = $this->import($resource, $type);
                $collection->addCollection($importedRoutes);
            }

        } catch (FileLoaderLoadException  $e) {
            throw new FileLoaderLoadException($resource);
        }

        return $collection;
    }


    public function supports($resource, $type = null)
    {
        return 'extra_bundle' === $type;
    }
}
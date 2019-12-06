<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\SonataUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KisaanSonataUserBundle extends Bundle
{

    public function getParent()
    {
        return 'SonataUserBundle';
    }

}

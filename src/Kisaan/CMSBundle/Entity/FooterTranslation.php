<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CMSBundle\Entity;

use Kisaan\CMSBundle\Model\BaseFooterTranslation;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="footer_translation",indexes={
 *    @ORM\Index(name="footer_url_hash_idx", columns={"url_hash"})
 *  })
 *
 */
class FooterTranslation extends BaseFooterTranslation
{
    use ORMBehaviors\Translatable\Translation;

}

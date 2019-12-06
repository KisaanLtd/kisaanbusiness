<?php

/*
 * This file is part of the Kisaan package.
 *
 * (c) Cocolabs SAS <contact@cocolabs.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kisaan\CoreBundle\DependencyInjection\Compiler;

use Kisaan\CoreBundle\Twig\TranslationExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigTranslationExtensionCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        /**
         * Override Twig Translation extension
         */
        if ($container->hasDefinition('twig.extension.trans')) {
            $definition = $container->getDefinition('twig.extension.trans');
            $definition->setClass(TranslationExtension::class);
            $definition->addMethodCall(
                'setCheckTranslation',
                array($container->getParameter('kisaan.check_translation'))
            );
        }
    }
}

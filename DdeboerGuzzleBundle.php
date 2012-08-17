<?php

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Ddeboer\GuzzleBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ddeboer\GuzzleBundle\DependencyInjection\Compiler\GrabCommandsPass;

class DdeboerGuzzleBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new GrabCommandsPass());
    }
}

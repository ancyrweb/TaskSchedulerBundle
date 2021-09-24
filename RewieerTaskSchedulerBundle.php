<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle;

use Rewieer\TaskSchedulerBundle\DependencyInjection\Compiler\EventDispatcherPass;
use Rewieer\TaskSchedulerBundle\DependencyInjection\Compiler\TaskPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RewieerTaskSchedulerBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TaskPass());
        $container->addCompilerPass(new EventDispatcherPass());
    }
}

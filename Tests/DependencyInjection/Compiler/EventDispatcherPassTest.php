<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\Tests\DependencyInjection\Compiler;

use Rewieer\TaskSchedulerBundle\DependencyInjection\Compiler\EventDispatcherPass;
use Rewieer\TaskSchedulerBundle\Tests\DependencyInjection\ContainerAwareTest;
use Rewieer\TaskSchedulerBundle\Tests\EventSubscriberMock;
use Symfony\Component\DependencyInjection\Definition;

class EventDispatcherPassTest extends ContainerAwareTest
{
    public function testLoadingPass(): void
    {
        $container = $this->loadContainer();

        $def = new Definition(EventSubscriberMock::class);
        $def->addTag("ts.event_subscriber");
        $def->setPublic(true);
        $container->setDefinition("mock.event_subscriber", $def);

        $pass = new EventDispatcherPass();
        $pass->process($container);
        $container->compile();

        $dispatcher = $container->get("ts.event_dispatcher");
        $this->assertEquals([
            $container->get("ts.scheduler_logger"),
            $container->get("mock.event_subscriber")
        ], $dispatcher->getSubscribers());
    }
}

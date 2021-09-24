<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\Tests\DependencyInjection\Compiler;

use Rewieer\TaskSchedulerBundle\DependencyInjection\Compiler\TaskPass;
use Rewieer\TaskSchedulerBundle\Tests\DependencyInjection\ContainerAwareTest;
use Symfony\Component\DependencyInjection\Definition;

class TaskPassTest extends ContainerAwareTest
{
    protected function setUp(): void
    {
        Task::$runCount = 0;
    }

    public function testLoadingPass(): void
    {
        $container = $this->loadContainer();

        $def = new Definition(Task::class);
        $def->addTag("ts.task");
        $container->setDefinition("mock.task", $def);

        $pass = new TaskPass();
        $pass->process($container);
        $container->compile();

        $scheduler = $container->get("ts.scheduler");
        $scheduler->run();

        $this->assertEquals(1, Task::$runCount);
    }
}

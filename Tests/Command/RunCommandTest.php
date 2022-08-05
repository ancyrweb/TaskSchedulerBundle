<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\Tests\Command;

use Rewieer\TaskSchedulerBundle\Command\RunCommand;
use Rewieer\TaskSchedulerBundle\Task\Scheduler;
use Rewieer\TaskSchedulerBundle\Tests\DependencyInjection\ContainerAwareTest;
use Rewieer\TaskSchedulerBundle\Tests\TaskMock;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class RunCommandTest extends ContainerAwareTest
{
    protected function setUp(): void
    {
        TaskMock::$runCount = 0;
    }

    public function testRunCommand(): void
    {
        $container = $this->loadContainer();
        /** @var Scheduler $scheduler */
        $scheduler = $container->get("ts.scheduler");
        $scheduler->addTask(new TaskMock());

        $application = new Application();
        $application->add(new RunCommand($scheduler));
        $command = $application->find("ts:run");

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            "command" => $command->getName(),
        ]);

        $this->assertEquals(1, TaskMock::$runCount);
    }

    public function testRunCommandWithId(): void
    {
        $container = $this->loadContainer();

        /** @var Scheduler $scheduler */
        $scheduler = $container->get("ts.scheduler");

        $t1 = new TaskMock();
        $t2 = new TaskMock();

        $scheduler->addTask($t1);
        $scheduler->addTask($t2);

        $application = new Application();
        $application->add(new RunCommand($scheduler));
        $command = $application->find("ts:run");

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            "command" => $command->getName(),
            "id" => 1,
        ]);

        $this->assertEquals(1, TaskMock::$runCount);
        $this->assertEquals(1, $t1->localCount);
        $this->assertEquals(0, $t2->localCount);
    }

    public function testRunCommandWithClassName(): void
    {
        $container = $this->loadContainer();

        /** @var Scheduler $scheduler */
        $scheduler = $container->get("ts.scheduler");

        $t1 = new TaskMock();
        $t2 = new TaskMock();

        $scheduler->addTask($t1);
        $scheduler->addTask($t2);

        $application = new Application();
        $application->add(new RunCommand($scheduler));
        $command = $application->find("ts:run");

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            "command" => $command->getName(),
            "--class" => "TaskMock",
        ]);


        $this->assertEquals(1, TaskMock::$runCount);
        $this->assertEquals(1, $t1->localCount);
        $this->assertEquals(0, $t2->localCount);
    }
}

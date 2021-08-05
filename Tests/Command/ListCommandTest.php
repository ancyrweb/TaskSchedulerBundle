<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\Tests\Command;

use Rewieer\TaskSchedulerBundle\Command\ListCommand;
use Rewieer\TaskSchedulerBundle\Task\Scheduler;
use Rewieer\TaskSchedulerBundle\Tests\DependencyInjection\ContainerAwareTest;
use Rewieer\TaskSchedulerBundle\Tests\TaskMock;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ListCommandTest extends ContainerAwareTest
{
    protected function setUp(): void
    {
        TaskMock::$runCount = 0;
    }

    public function testListCommand(): void
    {
        $container = $this->loadContainer();

        /** @var Scheduler $scheduler */
        $scheduler = $container->get("ts.scheduler");
        $scheduler->addTask(new TaskMock());

        $application = new Application();
        $application->add(new ListCommand($scheduler));

        $command = $application->find("ts:list");

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            "command" => $command->getName(),
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringNotContainsString("run dates", $output);
        $this->assertStringContainsString("| 1  | Rewieer\TaskSchedulerBundle\Tests\TaskMock |", $output);
    }

    public function testListCommandWithOption(): void
    {
        $container = $this->loadContainer();
        $scheduler = $container->get("ts.scheduler");
        $scheduler->addTask(new TaskMock());

        $application = new Application();
        /** @var Scheduler $scheduler */
        $application->add(new ListCommand($scheduler));

        $command = $application->find("ts:list");

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            "command" => $command->getName(),
            "--show-run-dates" => 42,
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString("42 run dates", $output);
        $this->assertStringContainsString(
            "| 1  | Rewieer\TaskSchedulerBundle\Tests\TaskMock | nextRunDate, anotherRunDate",
            $output
        );
    }
}

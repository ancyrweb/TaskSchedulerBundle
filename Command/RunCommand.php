<?php

/**
 * Copyright (c) 2017-present, Evosphere.
 * All rights reserved.
 */

namespace Rewieer\TaskSchedulerBundle\Command;

use Rewieer\TaskSchedulerBundle\Task\Scheduler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    /**
     * @var Scheduler
     */
    private $scheduler;

    public function __construct(Scheduler $scheduler)
    {
        parent::__construct();
        $this->scheduler = $scheduler;
    }

    protected function configure(): void
    {
        $this
            ->setName("ts:run")
            ->setDescription("Run due tasks")
            ->setHelp("This command actually run the tasks that are due at the moment the command is called.\nThis command should not be called manually. Check the documentation to learn how to set CRON jobs.")
            ->addArgument("id", InputArgument::OPTIONAL, "The ID of the task. Check ts:list for IDs")
            ->addOption("class", "c", InputOption::VALUE_OPTIONAL, "the class name of the task (without namespace)");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument("id");
        $class = $input->getOption("class");


        if (!$id && !$class) {
            $this->scheduler->run();
        } elseif ($class) {
            $tasks = $this->scheduler->getTasks();
            foreach ($tasks as $task) {
                if (strpos(get_class($task), "\\$class")) {
                    $this->scheduler->runTask($task);
                    return 0;
                }
            }
            throw new \Exception("There are no tasks corresponding to this class name");
        } else {
            $tasks = $this->scheduler->getTasks();
            $id = (int)$id;

            if (array_key_exists($id - 1, $tasks) === false) {
                throw new \Exception("There are no tasks corresponding to this ID");
            }

            $this->scheduler->runTask($tasks[$id - 1]);
        }

        return 0;
    }
}

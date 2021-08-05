<?php
/**
 * Copyright (c) 2017-present, Evosphere.
 * All rights reserved.
 */

namespace Rewieer\TaskSchedulerBundle\Command;

use Rewieer\TaskSchedulerBundle\Task\Scheduler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    private const NUMBER_OF_RUN_DATES = 3;

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
            ->setName("ts:list")
            ->setDescription("List the existing tasks")
            ->setHelp("This command display the list of registered tasks.")
            ->addOption(
                "show-run-dates",
                null,
                InputOption::VALUE_OPTIONAL,
                "Show next run dates (default value: " . self::NUMBER_OF_RUN_DATES . ")",
                false
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $numberOfRunDates = $input->getOption('show-run-dates') ?? self::NUMBER_OF_RUN_DATES;
        $showRunDates = $numberOfRunDates !== false;

        $table = new Table($output);
        $tableHeaders = ["ID", "Class"];

        if ($showRunDates) {
            $tableHeaders[] = "Next " . $numberOfRunDates . " run dates";
        }

        $table->setHeaders($tableHeaders);

        foreach ($this->scheduler->getTasks() as $id => $task) {
            $row = [($id + 1), get_class($task)];

            if ($showRunDates) {
                $nextRunDates = $task->getNextRunDates($numberOfRunDates);
                $row[] = implode(', ', $nextRunDates);
            }

            $table->addRow($row);
        }

        $table->render();

        return 0;
    }
}

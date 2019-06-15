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
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command {
  /**
   * @var Scheduler
   */
  private $scheduler;

  public function __construct(Scheduler $scheduler)
  {
    parent::__construct();
    $this->scheduler = $scheduler;
  }

  protected function configure() {
    $this
      ->setName("ts:list")
      ->setDescription("List the existing tasks")
      ->setHelp("This command display the list of registered tasks.");
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $table = new Table($output);
    $table->setHeaders([
      "ID",
      "Class",
    ]);

    $id = 1;
    foreach ($this->scheduler->getTasks() as $task) {
      $table->addRow([$id++, get_class($task)]);
    };

    $table->render();
  }
}

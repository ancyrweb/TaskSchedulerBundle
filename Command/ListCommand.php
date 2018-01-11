<?php
/**
 * Copyright (c) 2017-present, Evosphere.
 * All rights reserved.
 */

namespace Rewieer\TaskSchedulerBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends ContainerAwareCommand {
  protected function configure() {
    $this
      ->setName("ts:list")
      ->setDescription("List the existing tasks")
      ->setHelp("This command display the list of registered tasks.");
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $scheduler = $this->getContainer()->get("ts.scheduler");
    foreach ($scheduler->getTasks() as $task) {
      $output->writeln(sprintf("%s", get_class($task)));
    };
  }
}
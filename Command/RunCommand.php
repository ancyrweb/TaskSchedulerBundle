<?php
/**
 * Copyright (c) 2017-present, Evosphere.
 * All rights reserved.
 */

namespace Rewieer\TaskSchedulerBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends ContainerAwareCommand {
  protected function configure() {
    $this
      ->setName("ts:run")
      ->setDescription("Run due tasks")
      ->setHelp("This command actually run the tasks that are due at the moment the command is called.
      This command should not be called manually. Check the documentation to learn how to set CRON jobs.");
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $scheduler = $this->getContainer()->get("ts.scheduler");
    $scheduler->run();
  }
}
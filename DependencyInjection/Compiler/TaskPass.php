<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TaskPass
 * @package Rewieer\TaskSchedulerBundle\DependencyInjection\Compiler
 *
 * Adds services tagged with "ts.task" to the scheduler
 */
class TaskPass implements CompilerPassInterface {
  public function process(ContainerBuilder $container) {
    $definition = $container->findDefinition('ts.scheduler');
    $tasks = $container->findTaggedServiceIds('ts.task');

    foreach($tasks as $id => $tags) {
      $definition->addMethodCall("addTask", [new Reference($id)]);
    }
  }
}
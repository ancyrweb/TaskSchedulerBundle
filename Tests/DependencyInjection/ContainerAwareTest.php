<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\Tests\DependencyInjection;

use Monolog\Logger;
use Rewieer\TaskSchedulerBundle\DependencyInjection\RewieerTaskSchedulerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Router;

abstract class ContainerAwareTest extends \PHPUnit\Framework\TestCase {
  public function loadContainer($config = []) {
    $container = new ContainerBuilder();
    $container->set("logger", new Logger(""));
    $extension = new RewieerTaskSchedulerExtension();
    $extension->load($config, $container);

    return $container;
  }
}
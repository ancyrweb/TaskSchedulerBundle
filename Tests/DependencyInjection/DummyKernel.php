<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\Tests\DependencyInjection;

use Rewieer\TaskSchedulerBundle\RewieerTaskSchedulerBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class DummyKernel extends {
  public function registerBundles() {
    return [
      new RewieerTaskSchedulerBundle(),
    ];
  }

  public function registerContainerConfiguration(LoaderInterface $loader) {

  }
}
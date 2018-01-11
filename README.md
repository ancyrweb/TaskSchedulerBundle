# Task Scheduler Bundle

A [Task Scheduler](https://laravel.com/docs/master/scheduling) porting for Symfony applications that
will allow you to run jobs regularly.

```php
use Rewieer\TaskSchedulerBundle\Task\AbstractScheduledTask;
use Rewieer\TaskSchedulerBundle\Task\Schedule;

class Task extends AbstractScheduledTask {
  protected function initialize(Schedule $schedule) {
    $schedule
      ->everyMinutes(5); // Perform the task every 5 minutes
  }

  public function run() {
    // Do suff
  }
}
```

## Installation

Start by adding the bundle to your composer.json
`composer require rewieer/taskschedulerbundle`

Then add the bundle to your AppKernel.php :
```php
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Rewieer\TaskSchedulerBundle\RewieerTaskSchedulerBundle(),
    // ...
);
```

You are good to go !

## Usage
Tasks can be anything, like services ! You just have to add the tag `ts.task` and implement 
`TaskInterface`, or for simplicity extends from `AbstractScheduledTask`.

In your services.xml : 
```xml
<service id="my.task" class="Foo\Bar\Task">
  <tag name="ts.task" />
</service>
```

Then in `Foo\Bar\Task`
```php
use Rewieer\TaskSchedulerBundle\Task\AbstractScheduledTask;
use Rewieer\TaskSchedulerBundle\Task\Schedule;

class Task extends AbstractScheduledTask {
  protected function initialize(Schedule $schedule) {
    $schedule
      ->everyMinutes(5); // Perform the task every 5 minutes
  }

  public function run() {
    // Do suff
  }
}
```

Your task is registered and will now be called every 5 minutes.

## Enabling CRON

For this to work, you must be able to define CRON jobs. Add the following line to your CRON tab

`* * * * * php /path/to/your/project/bin/console ts:run 1>> /dev/null 2>&1`

You're good to go ! You can now check your logs to see if this is working.

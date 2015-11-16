<?php
require_once('../src/TaskBalancer/Balancer.php');
require_once('../src/TaskBalancer/Task.php');
require_once('../src/TaskBalancer/Driver.php');

use Toplan\TaskBalance\Balancer;

//define task:
$data = [
    'name' => 'top lan',
    'age'  => '20'
];
$t = Balancer::task('test1', $data, function($task){
    $task->driver('driver_1 100 backup', function($driver, $data){
                    $person = new Person($data['name'], $data['age']);
                    $driver->failed();
                    print_r('run work! by '.$driver->name.'<br>');
                    return ['test.driver1 working', $person->toString()];
                });

    $task->driver('driver_2', 90, function($driver, $data){
             $driver->failed();
             print_r('run work! by '.$driver->name.'<br>');
             return ['test.driver2 working', $data];
         })
         ->data(['this is data 2']);

    $task->driver('driver_3 ')
         ->weight(0)->backUp()
         ->data(['this is data 3'])
         ->work(function($driver, $data){
                    $driver->failed();
                    print_r('run work! by '.$driver->name.'<br>');
                    return ['test.driver3 working', $data];
                });
});

//run task:
$data['age'] = '25';
$result = Balancer::run('test1', $data);

print_r('<br>resuts data:<br>');
var_dump($result);
print_r('<hr>task object:<br>');
var_dump($t);

class Person {

    protected  $name;

    protected  $age;

    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function toString()
    {
        return "hi, I am $this->name, and $this->age year old";
    }
}
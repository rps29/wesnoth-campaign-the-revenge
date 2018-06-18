<?php
namespace Source;

/**
 * Implement this class and define your command by the given properties.
 */
abstract class AbstractCommand
{

    /**
     * Initialize your command here
     */
    public $command = '';

    /**
     * Declare a message for your command that is shown in the help message
     */
    public $help = '';

    /**
     * The function that will be called for your command
     */
    abstract public function run(array $args);

    public function writeln(string $message = '')
    {
        echo $message . PHP_EOL;
        return $this;
    }

}

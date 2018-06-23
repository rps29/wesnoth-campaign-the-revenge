<?php
namespace Source;

/**
 * Extend this class and define your command by the given properties.
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
     * todo Implement detailed help message explaining the command's arguments
     */
    public $expectedArgs = [
        // '--argument' => [
        //     'aliases' => [
        //         '-a'
        //     ],
        //     'description' => ''
        // ];
    ];

    public function __construct()
    {
    }

    /**
     * The function that will be called for your command
     */
    abstract public function run(array $args);

    public function writeln(string $message = '')
    {
        // todo...
        if (PHP_OS === 'LINUX') {
            // todo....
            // On linux we can highlight text!
        }
        echo $message . PHP_EOL;
        return $this;
    }

}

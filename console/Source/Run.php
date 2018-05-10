<?php
namespace Source;

class Run
{

    /**
     * @var AbstractCommand[]
     */
    private $commands;

    /**
     * Handle a command, general execution
     */
    public function execute(array $argv)
    {
        $this->init();
        unset($argv[0]);
        $command = array_shift($argv);
        echo PHP_EOL;
        if (isset($this->commands[$command])) {
            // Run the desired command
            echo 'Running ' . $command . PHP_EOL . PHP_EOL;
            $this->commands[$command]->run($argv);
        } else {
            // List all available commands
            echo 'Command "' . $command . '" not found.' . PHP_EOL;
            echo 'Here is a list of all available commands:' . PHP_EOL . PHP_EOL;
            foreach ($this->commands as $command => $instance) {
                echo str_pad($command . ':', 30) . $instance->help . PHP_EOL;
            }
        }
        echo PHP_EOL;
    }

    /**
     * Scan and initialize all valid commands, located at `console/Command/*`
     */
    private function init()
    {
        $scan = BASE . '/console/Command';
        $paths = scandir($scan);
        foreach ($paths as $path) {
            $file = $scan . '/' . $path;
            if (is_file($file)) {
                $this->registerCommand($file);
            }
        }
    }

    /**
     * Register the currently iterated command
     * @throws \Exception if the class couldn't be injected
     */
    private function registerCommand(string $file)
    {
        $class = 'Command\\' . basename($file, '.php');
        $register = inject($class);
        $this->commands[$register->command] = $register;
    }

}

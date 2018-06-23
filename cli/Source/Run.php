<?php
namespace Source;

/**
 * Executing the command requested by the CLI
 */
class Run
{

    const PAD_LENGTH_COMMANDS = 50;

    const PAD_LENGTH_ARGS = 30;

    const PAD_LENGTH_ALIASES = 15;

    /**
     * @var AbstractCommand[]
     */
    private $commands;

    private $output;

    public function __construct(
        Output $output
    ) {
        $this->output = $output;
    }

    /**
     * Handle a command, general execution
     */
    public function execute(array $argv)
    {
        $this->init();

        // First argument determines the called command
        $command = array_shift($argv);
        var_dump($argv);
        $this->output->writeln();

        if (isset($this->commands[$command])) {
            // Run the desired command
            $this->output->writeln('Running ' . $command)->writeln();
            try {
                $this->call($command, 'run', $argv);
            } catch (\Exception $e) {
                $this->output->error($e->getMessage());
                exit();
            }
        } else {
            // List all available commands
            $this->output->error('Command "' . $command . '" not found.')->writeln();
            $this->output->writeln('Here is a list of all available commands:')->writeln();
            foreach ($this->commands as $command => $instance) {
                $this->output->writeln(str_pad("<info>$command</info>:", self::PAD_LENGTH_COMMANDS) . $instance->help);
            }
            $this->output->writeln()->info('For detailed information, use <command> -h | --help');
        }
        echo PHP_EOL;
    }

    /**
     * Scan and initialize all valid commands, located at `cli/Command/*`
     */
    private function init()
    {
        $scan = BASE . '/cli/Command';
        $paths = scandir($scan);
        foreach ($paths as $path) {
            $file = $scan . '/' . $path;
            if (is_file($file)) {
                try {
                    $this->registerCommand($file);
                } catch (\Exception $e) {
                    $this->output->error($e->getMessage());
                }

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

    /**
     * Run the specific command
     * In addition,
     */
    private function call(string $command, $main, array $args)
    {
        if (array_search('-h', $args) !== false || array_search('--help', $args) !== false) {
            foreach ($this->commands[$command]->expectedArgs as $arg => $info) {
                // Display detailed help for the command
                $this->output->info($command)->writeln();
                $aliasesStr = '';
                foreach ($info['aliases'] as $alias) {
                    $aliasesStr .= "  " . $alias;
                }
                $this->output->writeln(
                    str_pad($arg, self::PAD_LENGTH_ARGS)
                    . "\t" . str_pad($aliasesStr, self::PAD_LENGTH_ALIASES)
                    . "\t" . $info['description']
                )->writeln();
            }
        } else {
            if ($this->commands[$command]->$main($args)) {
                $this->output->writeln()->info('Success')->writeln();
            } else {
                $this->output->writeln()->error('Failure')->writeln();
            }
        }
    }

}

<?php
namespace Source;

class Output
{

    /**
     * Output is always displayed, even when --quiet is active
     */
    const QUIET = 0;

    /**
     * Default output level
     */
    const NORMAL = 1;

    /**
     * Verbose information, displayed when --verbose is active
     */
    const VERBOSE = 2;

    /**
     * Codes for colored console output
     * Don't use a constant, to allow custom colors being set
     *
     * Example:
     *
     * \**
     *  * Set your custom color tag
     *  * $this->output class property is set in @see AbstractCommand::__construct
     *  *\
     * public function whateverSetYourColorCode()
     * {
     *     $this->output['custom_color_blue'] = '34'
     *     $this->output->writeln('<custom_color_blue>This is a blue output.</custom_color_blue> Well done!');
     * }
     */
    public $tags = [
        // Info output is colored green
        'info' => '',
        // Debug output is colored yellow
        'debug' => '',
        // Error output is colored red
        'error' => ''
    ];

    /**
     *
     */
    public function writeln(string $message = '', $verbosity = self::NORMAL): self
    {
        if (!$this->checkVerbosity($verbosity)) {
            return $this;
        }

        $message = $this->dissect($message);

        echo $message . PHP_EOL;

        return $this;
    }

    public function info(string $message = '', $verbosity = self::NORMAL)
    {
        return $this->writeln("<info>$message</info>", $verbosity);
    }

    public function debug(string $message = '', $verbosity = self::VERBOSE)
    {
        return $this->writeln("<debug>$message</debug>", $verbosity);
    }

    public function error(string $message = '', $verbosity = self::NORMAL)
    {
        return $this->writeln("<error>$message</error>", $verbosity);
    }

    /**
     * Outputs PHP_EOL respectively line breaks to the CLI
     */
    public function nl($count = 1): self
    {
        for ($i = 0; $i < $count; $i++) {
            echo PHP_EOL;
        }

        return $this;
    }

    /**
     * Check whether to display the current output or not
     */
    private function checkVerbosity(int $verbosity)
    {
        return true;
        // todo Check verbosity, whether to output the message
    }

    /**
     *
     */
    private function dissect(string $str)
    {
        // todo Dissect / replace the tags like <info> with the related color string, but for linux OS only!
        if (strtoupper(PHP_OS) === 'LINUX') {
            // todo....
            // On linux we can highlight text!
        } else {
            // Unfortunately, on windows we can't
            // I don't know about mac, but anyone using it should be assassinated anyway

        }

        return $str;
    }

}

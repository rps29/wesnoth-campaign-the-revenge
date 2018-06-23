<?php
/** https://stackoverflow.com/questions/6543841/php-cli-getting-input-from-user-and-then-dumping-into-variable-possible */
/** http://php.net/manual/en/features.commandline.io-streams.php */

namespace Source;

class Input
{

    /**
     * Get the next line the user inputted
     */
    public function nextLine(): string
    {
        return trim(fgets(STDIN));
    }

    /**
     * Get the next integer the user inputted
     */
    public function nextInt(): int
    {
        $input = $this->nextLine();

        if (is_numeric($input)) {
            return $input;
        }

        return $this->nextInt();
    }

    /**
     * Get the next boolean the user inputted
     */
    public function nextBool(): bool
    {
        return filter_var($this->nextLine(), FILTER_VALIDATE_BOOLEAN);
    }

}

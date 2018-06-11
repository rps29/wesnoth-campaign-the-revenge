<?php
namespace Command;

use Source\AbstractCommand;

class MergeMap extends AbstractCommand
{

    public $command = 'pot-translations';

    public $help = 'Generate the `translations/en/LC_MESSAGES/wesnoth-The_Revenge.pot` file.';

    public function run(array $args)
    {
        // todo This command merges the given maps into the complete.map
        // This allows editing a single map and merging it into the complete.map
        // The cause for this idea is the tiredness of lags when editing the big 200x200 complete.map
    }

}

<?php
namespace Command;

use Source\AbstractCommand;

class Test extends AbstractCommand
{

    const POT_FILE = BASE . '/translations/en/LC_MESSAGES/wesnoth-Eternal_Silence.pot';

    public $command = 'test';

    public $help = 'Just some command for testing stuff. Finally!';

    public $expectedArgs = [
        '--argument_one' => [
            'aliases' => [
                '-one'
            ],
            'description' => 'Test description'
        ]
    ];

    public function run(array $args)
    {

    }

}

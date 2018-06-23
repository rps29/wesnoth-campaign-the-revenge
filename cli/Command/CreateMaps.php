<?php
namespace Command;

use Source\AbstractCommand;

class CreateMaps extends AbstractCommand
{

    /**
     * All corner coordinates for cutting the maps
     */
    const MAP_SPECS = [
        '01' => ['name' => '01_beginning', 'x1' => 106, 'y1' => 80, 'x2' => 130, 'y2' => 116],
        '02' => ['name' => '02_resurrection', 'x1' => 10, 'y1' => 63, 'x2' => 48, 'y2' => 100],
        // Story A
        '03A' => ['name' => '03A_undead_life', 'x1' => 0, 'y1' => 96, 'x2' => 42, 'y2' => 155],
        '04A' => ['name' => '04A_elves_war', 'x1' => 0, 'y1' => 140, 'x2' => 36, 'y2' => 176],
        '05A' => ['name' => '05A_taking_sides', 'x1' => 4, 'y1' => 142, 'x2' => 54, 'y2' => 163],
        // Story Aa
        '06Aa' => ['name' => '06Aa_reconquest', 'x1' => 100, 'y1' => 80, 'x2' => 130, 'y2' => 110],
        '07Aa' => ['name' => '07Aa_revenge', 'x1' => 72, 'y1' => 74, 'x2' => 136, 'y2' => 126],
        // Story Ab
        '06Ab' => ['name' => '06Ab_annihilation', 'x1' => 92, 'y1' => 162, 'x2' => 144, 'y2' => 189],
        '07Ab' => ['name' => '07Ab_war_council', 'x1' => 164, 'y1' => 178, 'x2' => 200, 'y2' => 200],
        '08Ab' => ['name' => '08Ab_revenge', 'x1' => 124, 'y1' => 138, 'x2' => 180, 'y2' => 160],
        // Story B
        '03B' => ['name' => '03B_wilderness', 'x1' => 28, 'y1' => 96, 'x2' => 112, 'y2' => 109],
        '04B' => ['name' => '04B_mountain_hike', 'x1' => 66, 'y1' => 50, 'x2' => 110, 'y2' => 109],
        '05B' => ['name' => '05B_friend_or_foe', 'x1' => 74, 'y1' => 33, 'x2' => 104, 'y2' => 52],
        '06B' => ['name' => '06B_dwarvish_stranger', 'x1' => 100, 'y1' => 1, 'x2' => 124, 'y2' => 31],
        // 07B_prisoned is an external map!
        // ['name' => '07B_prisoned', 'x1' => 102, 'y1' => 1, 'x2' => 150, 'y2' => 30],
        // 08B_armory is an external map!
        // ['name' => '08B_armory', 'x1' => 102, 'y1' => 1, 'x2' => 150, 'y2' => 30],
        '09B' => ['name' => '09B_battle_plan', 'x1' => 136, 'y1' => 30, 'x2' => 186, 'y2' => 108],
        // 10B_canalization is an external map!
        // ['name' => '10B_canalization', 'x1' => 108, 'y1' => 98, 'x2' => 162, 'y2' => 142],
        '11B' => ['name' => '11B_bostim', 'x1' => 72, 'y1' => 110, 'x2' => 140, 'y2' => 158],
        '12B' => ['name' => '12B_revenge', 'x1' => 88, 'y1' => 123, 'x2' => 116, 'y2' => 144],
        '13B' => ['name' => '13B_eternal_silence', 'x1' => 106, 'y1' => 122, 'x2' => 118, 'y2' => 130],
    ];

    public $command = 'map-split';

    public $help = 'Cut the complete.map into the different scenario maps.';

    public $expectedArgs = [
        'map_id' => [
            'aliases' => [],
            'description' => 'Pass the ID for which map you want to create / update out of the complete.map.' . PHP_EOL . PHP_EOL
                             . '<info>There are no more required arguments, you can also pass a list of map IDs, for example</info> "php cli/execute.php map-split id_one id_two id_three"'
        ]
    ];

    public function run(array $args)
    {
        $path = BASE . '/maps';
        $completeMap = file($path . '/complete.map');
        $success = true;
        if ($args) {
            // Create every given map id
            $this->debug('Passed arguments, iterating.');
            foreach ($args as $arg) {
                if (isset(self::MAP_SPECS[$arg])) {
                    $this->createMap($path, $completeMap, self::MAP_SPECS[$arg]);
                } else {
                    $this->error('Passed argument "' . $arg . '" is invalid.');
                    $success = false;
                }
            }
        } else {
            $this->debug('No arguments given, creating all maps.');
            try {
                $this->createAllMaps($path, $completeMap);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
                $success = false;
            }
        }

        if (!$success) {
            $this->info('The following arguments are valid:');
            foreach (array_keys(self::MAP_SPECS) as $validArg) {
                $this->writeln($validArg);
            }
        }
        return $success;
    }

    /**
     * Create all maps
     */
    private function createAllMaps(string $path, array $completeMap) {
        $complete = $path.'/complete.map';
        if (!is_file($complete)) {
            throw new \Exception('Map not found: ' . $complete);
        }
        foreach (self::MAP_SPECS as $mapspec) {
            $this->debug('Creating map: ' . $mapspec['name']);
            try {
                $this->createMap($path, $completeMap, $mapspec);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }

        }
    }

    /**
     * Create a single map
     */
    private function createMap(string $path, array $completeMap, array $mapspec) {
        try {
            $this->checkMapSpec($mapspec);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        // Before 1.14, there had been 3 lines before the map hex specifications that needed to be inserted
        // Since 1.14 this is not required anymore
        // If you run an older version of wesnoth, please set $firstLinesToCopy = 3
        $firstLinesToCopy = 0;
        $newLines = array_slice($completeMap, 0, $firstLinesToCopy);

        // Add desired rows and columns to the map that's being created
        $selectedLines = array_slice($completeMap, $firstLinesToCopy + $mapspec['y1'], $mapspec['y2'] - $mapspec['y1']);
        $this->debug('Adding new lines.');
        foreach ($selectedLines as $line) {
            $newCodes = array_slice(explode(', ', $line), $mapspec['x1'], $mapspec['x2'] - $mapspec['x1']);
            $newLines[] = implode(', ', $newCodes)."\n";
        }

        // abspeichern
        $mapFileName = $path.'/'.$mapspec['name'].'.map';
        $this->writeln('Saving: ' . $mapspec['name']);
        file_put_contents($mapFileName, $newLines);
        $this->debug('Saved file: ' . $mapFileName);
    }

    /**
     * Validate the given mapspec
     */
    private function checkMapSpec($mapspec) {
        if ($mapspec['x1'] >= $mapspec['x2']) {
            throw new \Exception('x1 >= x2');
        }
        $this->debug('X1 is larger than X2, continue...');
        if ($mapspec['y1'] >= $mapspec['y2']) {
            throw new \Exception('y1 >= y2');
        }
        $this->debug('Y1 is larger than Y2, continue...');
        // x1 muss gerade sein, weil sich sonst die Spalten gegeneinander verschieben!
        if ($mapspec['x1'] % 2 == 1) {
            throw new \Exception('x1 is uneven: '.$mapspec['x1']);
        }
        $this->debug('X is even, continue...');
        foreach (['x1','x2','y1','y2'] as $index) {
            if ($mapspec[$index] > 200) {
                throw new \Exception($index.' > 200: '.$mapspec[$index]);
            }
            $this->debug($index . ' does not exceed the maximum of 200, continue...');
        }
        $this->writeln($mapspec['name'] . ' passed all checks, mapspec can be created!');
    }

}

<?php
namespace Command;

use Source\AbstractCommand;

class CreateMaps extends AbstractCommand
{

    /**
     * All corner coordinates for cutting the maps
     */
    const MAP_SPECS = [
        ['name' => '01_beginning', 'x1' => 106, 'y1' => 80, 'x2' => 130, 'y2' => 116],
        ['name' => '02_resurrection', 'x1' => 10, 'y1' => 63, 'x2' => 48, 'y2' => 100],
        // Story A
        ['name' => '03A_undead_life', 'x1' => 0, 'y1' => 96, 'x2' => 42, 'y2' => 155],
        ['name' => '04A_elves_war', 'x1' => 0, 'y1' => 140, 'x2' => 36, 'y2' => 176],
        ['name' => '05A_taking_sides', 'x1' => 4, 'y1' => 142, 'x2' => 54, 'y2' => 163],
        // Story Aa
        ['name' => '06Aa_reconquest', 'x1' => 100, 'y1' => 80, 'x2' => 130, 'y2' => 110],
        ['name' => '07Aa_revenge', 'x1' => 72, 'y1' => 74, 'x2' => 136, 'y2' => 126],
        // Story Ab
        ['name' => '06Ab_annihilation', 'x1' => 92, 'y1' => 162, 'x2' => 144, 'y2' => 189],
        ['name' => '07Ab_war_council', 'x1' => 164, 'y1' => 178, 'x2' => 200, 'y2' => 200],
        ['name' => '08Ab_revenge', 'x1' => 124, 'y1' => 138, 'x2' => 180, 'y2' => 160],
        // Story B
        ['name' => '03B_wilderness', 'x1' => 28, 'y1' => 96, 'x2' => 112, 'y2' => 109],
        ['name' => '04B_mountain_hike', 'x1' => 66, 'y1' => 50, 'x2' => 110, 'y2' => 109],
        ['name' => '05B_friend_or_foe', 'x1' => 74, 'y1' => 33, 'x2' => 104, 'y2' => 52],
        ['name' => '06B_dwarvish_stranger', 'x1' => 100, 'y1' => 1, 'x2' => 124, 'y2' => 31],
        // 07B_prisoned ist eine externe Map!
        // ['name' => '07B_prisoned', 'x1' => 102, 'y1' => 1, 'x2' => 150, 'y2' => 30],
        // 08B_armory ist eine externe Map!
        // ['name' => '08B_armory', 'x1' => 102, 'y1' => 1, 'x2' => 150, 'y2' => 30],
        ['name' => '09B_battle_plan', 'x1' => 136, 'y1' => 30, 'x2' => 186, 'y2' => 108],
        // 10B_canalization ist eine externe Map!
        // ['name' => '10B_canalization', 'x1' => 108, 'y1' => 98, 'x2' => 162, 'y2' => 142],
        ['name' => '11B_bostim', 'x1' => 72, 'y1' => 110, 'x2' => 140, 'y2' => 158],
        ['name' => '12B_revenge', 'x1' => 88, 'y1' => 123, 'x2' => 116, 'y2' => 144],
        ['name' => '13B_eternal_silence', 'x1' => 106, 'y1' => 122, 'x2' => 118, 'y2' => 130],
    ];

    public $command = 'map-split';

    public $help = 'Cut the `maps/complete.map` into the different scenario maps.';

    public function run(array $args)
    {
        $path = BASE . '/maps';
        $completeMap = file($path . '/complete.map');
        if ($args) {
            foreach ($args as $arg) {
                if (isset(self::MAP_SPECS[$arg])) {
                    $this->createMap($path, $completeMap, self::MAP_SPECS[$arg]);
                } else {
                    $this->writeln('Passed argument "' . $arg . '" is invalid. The following arguments are valid:');
                    foreach (array_keys(self::MAP_SPECS) as $validArg) {
                        $this->writeln($validArg);
                    }
                }
            }
        } else {
            $this->createAllMaps($path, $completeMap);
        }

    }

    /**
     * Create all maps
     */
    public function createAllMaps(string $path, array $completeMap) {
        $complete = $path.'/complete.map';
        if (!is_file($complete)) {
            throw new \Exception('Map not found: ' . $complete);
        }
        foreach (self::MAP_SPECS as $mapspec) {
            $this->createMap($path, $completeMap, $mapspec);
        }
    }

    /**
     * Create a single map
     */
    private function createMap(string $path, array $completeMap, array $mapspec) {
        // checks
        $this->checkMapSpec($mapspec);

        // erste Zeilen einfach kopieren
        // Seit 1.14 werden die ersten 3 Zeilen nicht mehr benötigt! WICHTIG!
        $firstLinesToCopy = 0;
        $newLines = array_slice($completeMap, 0, $firstLinesToCopy);

        // gewünschte Zeilen und Spalten der alten Map hinzufügen
        $selectedLines = array_slice($completeMap, $firstLinesToCopy + $mapspec['y1'], $mapspec['y2'] - $mapspec['y1']);
        foreach ($selectedLines as $line) {
            $newCodes = array_slice(explode(', ', $line), $mapspec['x1'], $mapspec['x2'] - $mapspec['x1']);
            $newLines[] = implode(', ', $newCodes)."\n";
        }

        // abspeichern
        $mapFileName = $path.'/'.$mapspec['name'].'.map';
        echo "Es wird gespeichert: $mapFileName\n";
        file_put_contents($mapFileName, $newLines);
    }

    /**
     * Validate the given mapspec
     */
    private function checkMapSpec($mapspec) {
        if ($mapspec['x1'] >= $mapspec['x2']) {
            throw new \Exception('x1 >= x2');
        }
        if ($mapspec['y1'] >= $mapspec['y2']) {
            throw new \Exception('y1 >= y2');
        }
        // x1 muss gerade sein, weil sich sonst die Spalten gegeneinander verschieben!
        if ($mapspec['x1'] % 2 == 1) {
            throw new \Exception('x1 ist ungerade: '.$mapspec['x1']);
        }
        foreach (['x1','x2','y1','y2'] as $index) {
            if ($mapspec[$index] > 200) {
                throw new \Exception($index.' > 84: '.$mapspec[$index]);
            }
        }
    }

}

<?php
namespace Command;

use Source\AbstractCommand;

class CreateMaps extends AbstractCommand
{

    /**
     * All corner coordinates for cutting the maps
     */
    const MAP_SPECS = [
        1 => ['name' => '01_beginning', 'x1' => 50, 'y1' => 15, 'x2' => 84, 'y2' => 52],
        2 => ['name' => '02_resurrection', 'x1' => 12, 'y1' => 0, 'x2' => 44, 'y2' => 32],
        3 => ['name' => '03_undead_life', 'x1' => 0, 'y1' => 25, 'x2' => 42, 'y2' => 54],
        4 => ['name' => '04_elves_war', 'x1' => 0, 'y1' => 40, 'x2' => 34, 'y2' => 74],
        5 => ['name' => '05_taking_sides', 'x1' => 4, 'y1' => 32, 'x2' => 54, 'y2' => 64],
        6 => ['name' => '06_reconquest', 'x1' => 40, 'y1' => 12, 'x2' => 82, 'y2' => 43],
        // 07 identical with complete.map (at the moment!)
    ];

    public $command = 'maps';

    public $help = 'Cut the `maps/complete.map` into the different scenario (01 - 07) maps.';

    public function run(array $args)
    {
        $path = BASE . '/maps';
        $completeMap = file($path . '/complete.map');
        if (!$args) {
            $this->createAllMaps($path, $completeMap);
        } else {
            foreach ($args as $arg) {
                if (isset(self::MAP_SPECS[$arg])) {
                    $this->createMap($path, $completeMap, self::MAP_SPECS[$arg]);
                } else {
                    $this->writeln('Passed argument "' . $arg . '" is unvalid. The following arguments are valid:');
                    foreach (array_keys(self::MAP_SPECS) as $validArg) {
                        $this->writeln($validArg);
                    }
                }
            }
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
        $firstLinesToCopy = 3;
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
            if ($mapspec[$index] > 84) {
                throw new \Exception($index.' > 84: '.$mapspec[$index]);
            }
        }
    }

}

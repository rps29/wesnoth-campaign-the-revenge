<?php
namespace Command;

use Source\AbstractCommand;

class MergeMap extends AbstractCommand
{

    const COMPLETE_MAP = BASE . '/maps/complete.map';

    public $command = 'map-merge';

    public $help = 'Merge one specified map ID into the `maps/complete.map`.';

    private $createMaps;

    /**
     * Whether to update all other maps after having merged the desired map into complete.map
     */
    private $update = false;

    public function __construct(
        CreateMaps $createMaps
    ) {
        $this->createMaps = $createMaps;
    }

    /**
     * This command merges the given maps into the complete.map
     * This allows editing a single map and merging it into the complete.map
     * The cause for this idea is the tiredness of lags when editing the big 200x200 complete.map
     */
    public function run(array $args)
    {
        $maps = $this->getMapsToMerge($args);
        if ($this->validateMaps($maps)) {
            $this->mergeAll($maps);
            $this->update();
        } else {
            $this->writeln('Failed to merge your desired maps.');
            exit();
        }
    }

    private function update()
    {
        if ($this->update) {
            $this->writeln();
            $this->createMaps->run([]);
            $this->writeln()->writeln('Updated all single maps out of the updated complete.map.');
        } else {
            $this->writeln('No update parameter given. Update each single map by passing "-u" or "--update" as parameter.');
        }
    }

    private function mergeAll($maps)
    {
        foreach ($maps as $mapSpec) {
            $file = BASE . '/maps/' . $mapSpec['name'] . '.map';
            $completeMapFile = self::COMPLETE_MAP;

            // Generate array of complete.map
            $complete = file($completeMapFile);
            $completeCodes = [];
            foreach ($complete as $completeLine) {
                $completeLineCodes = explode(', ', $completeLine);
                $completeCodes[] = $completeLineCodes;
            }

            // Replace the codes in complete.map
            $map = file($file);
            foreach ($map as $line => $lineStr) {
                $line += $mapSpec['y1'];
                $newCodes = explode(', ', $lineStr);
                foreach ($newCodes as $row => $code) {
                    // Set proper keys for merge
                    $row += $mapSpec['x1'];
                    // Merge
                    $completeCodes[$line][$row] = trim($code);
                }
            }

            $completeLines = [];
            // Generate map string
            foreach ($completeCodes as $lineCodes) {
                $completeLines[] = implode(', ', $lineCodes);
            }

            $this->writeln('Merging ' . $file . ' into complete.map');
            file_put_contents($completeMapFile, $completeLines);
        }
    }

    /**
     * Validate map files exist and map size fits the size defined at CreateMaps::MAP_SPECS
     */
    private function validateMaps(array $maps): bool
    {
        foreach ($maps as $mapSpec) {
            $failedMsg = 'Map invalid! Please ensure that "' . $mapSpec['name'] . '" is sized ' . ($mapSpec['x2'] - $mapSpec['x1']) . ' x ' . ($mapSpec['y2'] - $mapSpec['y1']) . ' hexes!';
            $file = BASE . '/maps/' . $mapSpec['name'] . '.map';
            $complete = self::COMPLETE_MAP;

            // Files must exist!
            if (!is_file($file)) {
                $this->writeln('Map not found: ' . $file);
                return false;
            } elseif (!is_file($complete)) {
                $this->writeln('Map not found: ' . $complete);
                return false;
            }

            // Ensure the map has correct size
            $map = file($file);
            foreach ($map as $line) {
                $newCodes = explode(', ', $line);
                // Width
                if ($mapSpec['x2'] - $mapSpec['x1'] !== count($newCodes)) {
                    $this->writeln($failedMsg);
                    return false;
                }
                // Height
                if ($mapSpec['y2'] - $mapSpec['y1'] !== count($map)) {
                    $this->writeln($failedMsg);
                        return false;
                }
            }
        }
        return true;
    }

    /**
     * Retrieve all valid maps to be merged - Referencing to CreateMaps::MAP_SPECS for map specifications
     */
    private function getMapsToMerge(array $args): array
    {
        if (!$args) {
            $this->writeln('No arguments given.');
            exit();
        }
        if ($args[0] === '-a' || $args[0] === '--all') {
            // Merge all maps into complete.map one by one
            return CreateMaps::MAP_SPECS;
        }
        if ($key = array_search('-u', $args, true) || $key = array_search('--update', $args, true)) {
            $this->update = true;
            unset($args[$key]);
        }
        $mapsToMerge = [];
        // Extract the map names / ids
        $mapIds = array_column(CreateMaps::MAP_SPECS, 'name');
        foreach ($args as $map) {
            $key = array_search($map, $mapIds, true);
            if ($key === false) {
                $this->writeln('Map ' . $map . ' is no valid map ID!');
            } else {
                // Validated, add map
                $mapsToMerge[] = CreateMaps::MAP_SPECS[$key];
            }
        }
        return $mapsToMerge;
    }

}

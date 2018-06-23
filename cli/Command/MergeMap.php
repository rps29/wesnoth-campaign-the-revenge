<?php
namespace Command;

use Source\{
    AbstractCommand,
    Input,
    Output
};

class MergeMap extends AbstractCommand
{

    const COMPLETE_MAP = BASE . '/maps/complete.map';

    public $command = 'map-merge';

    public $help = 'Merge one specified map ID into the `maps/complete.map`.';

    public $expectedArgs = [
        '--all' => [
            'aliases' => [
                '-a'
            ],
            'description' => ''
        ],
        '--update' => [
            'aliases' => [
                '-u'
            ],
            'description' => ''
        ]
    ];

    private $createMaps;

    /**
     * Whether to update all other maps after having merged the desired map into complete.map
     */
    private $update = false;

    public function __construct(
        Output $output,
        Input $input,
        CreateMaps $createMaps
    ) {
        parent::__construct($output, $input);
        $this->createMaps = $createMaps;
    }

    /**
     * This command merges the given maps into the complete.map
     * This allows editing a single map and merging it into the complete.map
     * The cause for this idea is the tiredness of lags when editing the big 200x200 complete.map
     */
    public function run(array $args)
    {
        try {
            $maps = $this->getMapsToMerge($args);
            if ($this->validateMaps($maps)) {
                $this->mergeAll($maps);
                $this->update();
                return true;
            } else {
                $this->error('Failed to merge your desired maps.');
                return false;
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return false;
        }
    }

    /**
     * Retrieve all valid maps to be merged - Referencing to CreateMaps::MAP_SPECS for map specifications
     */
    private function getMapsToMerge(array $args): array
    {
        if (!$args) {
            $this->writeln('No arguments given.');
            $this->info('The following arguments are valid:');
            foreach (CreateMaps::MAP_SPECS as $validArg) {
                $this->writeln($validArg['name']);
            }
            throw new \Exception('No arguments given.');
        }

        $all = false;
        $update = false;
        $commandArguments = [
            '-a' => 'all',
            '--all' => 'all',
            '-u' => 'update',
            '--update' => 'update',
        ];

        foreach ($commandArguments as $argument => $exec) {
            if (($key = array_search($argument, $args)) !== false) {
                $this->debug('Argument passed: ' . $argument);
                if ($exec === 'all') {
                    $all = true;
                } elseif ($exec === 'update') {
                    $update = true;
                }
                unset($args[$key]);
            }
        }

        if ($update) {
            $this->debug('Argument passed for updating respectively splitting maps after complete.map has been merged.');
            $this->update = true;
        }
        if ($all) {
            // Merge all maps into complete.map one by one
            $this->debug('Argument passed for merging all maps one by one.');
            return CreateMaps::MAP_SPECS;
        }


        $mapsToMerge = [];
        foreach ($args as $map) {
            $key = array_search($map, array_column(CreateMaps::MAP_SPECS, 'name'), true);
            if ($key === false) {
                throw new \Exception('Map ' . $map . ' is no valid map ID!');
            } else {
                // Validated, add map
                $this->debug('Valid map ID, adding ' . $map);
                $key = array_keys(CreateMaps::MAP_SPECS)[$key];
                $mapsToMerge[] = CreateMaps::MAP_SPECS[$key];
            }
        }
        return $mapsToMerge;
    }

    /**
     * Validate map files exist and map size fits the size defined at CreateMaps::MAP_SPECS
     */
    private function validateMaps(array $maps): bool
    {
        foreach ($maps as $mapSpec) {
            $this->debug('Validating ' . $mapSpec['name']);
            $failedMsg = 'Map invalid! Please ensure that "' . $mapSpec['name'] . '" is sized ' . ($mapSpec['x2'] - $mapSpec['x1']) . ' x ' . ($mapSpec['y2'] - $mapSpec['y1']) . ' hexes!';
            $file = BASE . '/maps/' . $mapSpec['name'] . '.map';
            $complete = self::COMPLETE_MAP;

            // Files must exist!
            $this->debug('Checking whether required files exist.');
            if (!is_file($file)) {
                $this->error('Map not found: ' . $file);
                return false;
            } elseif (!is_file($complete)) {
                $this->error('Map not found: ' . $complete);
                return false;
            }

            // Ensure the map has correct size
            $this->debug('The map you want to merge must fit in size into the complete.map, as specified by MAP_SPECS.');
            $map = file($file);
            foreach ($map as $line) {
                $newCodes = explode(', ', $line);
                // Width
                if ($mapSpec['x2'] - $mapSpec['x1'] !== count($newCodes)) {
                    $this->error($failedMsg);
                    return false;
                }
                // Height
                if ($mapSpec['y2'] - $mapSpec['y1'] !== count($map)) {
                    $this->error($failedMsg);
                    return false;
                }
            }
        }

        $this->debug('All checks passed, maps can be merged.');
        return true;
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
            $this->debug('Generated complete.map structure.');

            // Replace the codes in complete.map
            $map = file($file);
            foreach ($map as $line => $lineStr) {
                $this->debug('Merge row: ' . $line);
                $line += $mapSpec['y1'];
                $newCodes = explode(', ', $lineStr);
                foreach ($newCodes as $row => $code) {
                    // Set proper keys for merge
                    $row += $mapSpec['x1'];
                    // Merge
                    $completeCodes[$line][$row] = trim($code);
                }
                $this->debug('Merged row successfully!');
            }

            $completeLines = [];
            // Generate map string
            $this->debug('Regenerating complete.map');
            foreach ($completeCodes as $lineCodes) {
                $completeLines[] = implode(', ', $lineCodes);
            }

            $this->info('Merging ' . $file . ' into complete.map');
            file_put_contents($completeMapFile, $completeLines);
        }
    }

    private function update()
    {
        if ($this->update) {
            $this->output->nl()->info('Updating all maps.')->nl(2);
            // Do not pass any arguments in order to update every map
            $this->createMaps->run([]);
            $this->output->nl(2)->writeln('Updated all single maps out of the updated complete.map.');
        } else {
            $this->info('No update parameter given. Update each single map by passing "-u" or "--update" as parameter.');
        }
    }

}

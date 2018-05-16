<?php
namespace Command;

use Source\AbstractCommand;

/**
 * todo @papa:
 * Den gesamten `cli/` Ordner refactoren !
 */
class CreateMacroDocumentation extends AbstractCommand
{

    public $command = 'macro-doc';

    public $help = 'Scan all files located at `utils/*.cfg` and generate the `doku/macros.md` documentation file.';

    private $docs = [];

    private $documentation = '';

    public function run(array $args)
    {
        // Path to macro files
        $path = BASE . '/utils';
        $dirs = scandir($path);
        foreach ($dirs as $fileName) {
            $file = $path . "\\" . $fileName;
            if (is_file($file)) {
                $this->importFileDoc($file);
            }
        }
        $this->writeDoc();
    }

    private function importFileDoc(string $filePath)
    {
        $this->writeln('Importing macros from ' . basename($filePath));
        $macroDefinitionKeyword = '#define';
        $keywordLength = strlen($macroDefinitionKeyword);
        $parsedMacroData = [];
        $prevMacroEnd = 1;
        $fileContent = file_get_contents($filePath);
        if (!$fileContent) {
            $this->writeln("No file content given!");
            return;
        }
        while (true) {
            $macroPos = strpos($fileContent, $macroDefinitionKeyword, $prevMacroEnd + $keywordLength);
            if (!$macroPos) {
                break;
            }
            $macroPos = $macroPos + $keywordLength;
            // Parse for macro
            $eol = strpos($fileContent, "\n", $macroPos);
            $length = $eol - $macroPos;
            $macro = trim(substr($fileContent, $macroPos, $length));
            // Parse for macro description / doc
            $description = 'No documentation found.';
            $docTag = '# @doc';
            $docTagLength = strlen($docTag);
            $descrStart = strrpos($fileContent, $docTag, -(strlen($fileContent) - $macroPos));
            if ($descrStart && $descrStart > $prevMacroEnd) {
                $description = substr($fileContent, $descrStart + $docTagLength, $macroPos - $descrStart - $keywordLength - $docTagLength);
                $description = str_replace('#', '-', $description);
            }
            // Add data
            $parsedMacroData[$macro] = $description;
            $prevMacroEnd = $eol;
        }
        $this->docs[basename($filePath)] = $parsedMacroData;
    }

    private function writeDoc()
    {
        $docFile = BASE . '/doku/macros.md';
        $this->append('# Macro documentation for The Revenge');
        $this->append('***This documentation is automatically generated. Manual edits to this file will be overridden!***');
        foreach ($this->docs as $macroFile => $macros) {
            $this->append('## From file ' . $macroFile);
            foreach ($macros as $macro => $description) {
                $macro = trim($macro);
                $pos = strpos($macro, ' ');
                if ($pos) {
                    $parameters = substr($macro, $pos);
                    $macro = '**' . substr($macro, 0, $pos) . '**' . ' *' . trim($parameters) . '*';
                } else {
                    $macro = '**' . $macro . '**';
                }
                $this->append($macro . PHP_EOL . $description);
            }
        }
        $success = file_put_contents($docFile, $this->documentation);
        if ($success === false) {
            throw new \Exception('Failed to create documentation!');
        }
    }

    private function append(string $str)
    {
        $this->documentation .= $str . PHP_EOL . PHP_EOL;
    }

}

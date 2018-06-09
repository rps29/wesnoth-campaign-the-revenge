<?php
namespace Command;

use Source\AbstractCommand;

class PotFileTranslations extends AbstractCommand
{

    const POT_FILE = BASE . '/translations/en/LC_MESSAGES/wesnoth-The_Revenge.pot';

    public $command = 'pot-translations';

    public $help = 'Generate the `translations/en/LC_MESSAGES/wesnoth-The_Revenge.pot` file.';

    private $paths = [
        'scenarios',
        'units',
        'units/dwarves',
        'units/humans',
        'units/trolls',
        'units/undead',
        'utils'
    ];

    private $files = [
        '_main.cfg'
    ];

    private $header = 'msgid ""
msgstr ""
"Project-Id-Version: The Revenge\n"
"POT-Creation-Date: 2018-06-09 14:20+0200\n"
"PO-Revision-Date: 2018-06-09 14:20+0200\n"
"Last-Translator: \n"
"Language-Team: \n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"
"X-Generator: Poedit 2.0.8\n"
"X-Poedit-Basepath: .\n"
"Plural-Forms: nplurals=2; plural=(n != 1);\n"
"Language: en\n"
"X-Poedit-KeywordsList: _\n"
' . "\n";

    public function run(array $args)
    {
        $files = $this->getAllTranslatableFiles();
        $translations = array_unique($this->scanFiles($files));
        $translations = $this->prepareTranslations($translations);
        $this->createPot($translations);
    }

    /**
     * Create the .pot file
     */
    private function createPot(array $translations)
    {
        $content = $this->header;
        foreach ($translations as $trans) {
            $content .= "\n";
            $content .= 'msgid ' . $trans;
            $content .= 'msgstr ""' . "\n";
        }
        file_put_contents(self::POT_FILE, $content);
    }

    /**
     * Prepare the format for .pot file
     */
    private function prepareTranslations(array $translations): array
    {
        foreach ($translations as $key => $trans) {
            if (strpos($trans, "\n")) {
                $tmp = explode("\n", $trans);
                $trans = '""' . "\n";
                foreach ($tmp as $line) {
                    $trans .= '"' . trim($line) . '"' . "\n";
                }
            } else {
                $trans = '"' . $trans . '"' . "\n";
            }
            $translations[$key] = $trans;
        }
        return $translations;
    }

    /**
     * Scan and return all translatable string contained in each file
     */
    private function scanFiles(array $files): array
    {
        $translations = [];
        foreach ($files as $file) {
            echo PHP_EOL . 'Scanning file ' . $file . '...';
            $content = file_get_contents($file);
            $_ = '_';
            $transOffset = 0;
            $done = [];
            while (true) {
                $transPos = strpos($content, $_, $transOffset);
                if (!$transPos || $transOffset >= strlen($content) || isset($done[$transPos])) {
                    break;
                }
                $done[] = $transPos;
                $startStr = strpos($content, '"', $transPos);
                if (($startStr - $transPos) >= 3) {
                    $transOffset = ++$transPos;
                    continue;
                } elseif (!$startStr) {
                    break;
                }
                $endStr = strpos($content, '"', ++$startStr);
                if (!$endStr) {
                    break;
                }
                $transPos = $transPos + ($startStr - $transPos);

                // the string to translate
                $string =  substr($content, $transPos, $endStr - $startStr);
                $transOffset = ++$endStr;
                $translations[] = $string;
            }
            $done = [];
        }
        return $translations;
    }

    /**
     * Get all files that need to be translated
     */
    private function getAllTranslatableFiles(): array
    {
        $files = [];
        foreach ($this->paths as $path) {
            $found = scandir(BASE . '/' . $path);
            foreach ($found as $file) {
                $file = BASE . '/' . $path . '/'. $file;
                if (is_file($file)) {
                    $files[] = $file;
                } else {
                    echo $file . ' is not a file!' . PHP_EOL;
                }
            }
        }
        foreach ($this->files as $file) {
            $files[] = BASE . '/'. $file;
        }
        return $files;
    }

}

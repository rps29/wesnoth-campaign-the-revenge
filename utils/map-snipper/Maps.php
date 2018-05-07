<?php
/**
 * Funktionen zum Wesnoth-Karten-Zuschneiden
 */

class Maps {

  /**
   * Alle Maps neu erzeugen!
   * @param $path Pfad zum maps-Verzeichnis
   * @throws Exception
   */
  public function createAllMaps($path) {
    $complete = $path.'/complete.map';
    if (!is_file($complete)) {
      throw new Exception ($complete.' not found');
    }

    // Hier sind die Eckpunkte aller Maps definiert
    $mapspecs = [
      1 => ['name' => '01_beginning', 'x1' => 50, 'y1' => 15, 'x2' => 84, 'y2' => 52],
      2 => ['name' => '02_resurrection', 'x1' => 12, 'y1' => 0, 'x2' => 44, 'y2' => 32],
      3 => ['name' => '03_undead_life', 'x1' => 0, 'y1' => 25, 'x2' => 42, 'y2' => 54],
      4 => ['name' => '04_elves_war', 'x1' => 0, 'y1' => 40, 'x2' => 34, 'y2' => 74],
      5 => ['name' => '05_taking_sides', 'x1' => 4, 'y1' => 32, 'x2' => 54, 'y2' => 64],
      6 => ['name' => '06_reconquest', 'x1' => 40, 'y1' => 12, 'x2' => 82, 'y2' => 43],
      // Nr 7 identisch mit complete
    ];

    // Gesamtmap einlesen
    $aLines = file($complete);

    // nur eine
    //$this->createMap($path, $aLines, $mapspecs[6]);

    // oder alle
    foreach ($mapspecs as $mapspec) {
      $this->createMap($path, $aLines, $mapspec);
    }
  }


  /**
   * Genau eine map neu erzeugen
   * @param string $path Pfad zum maps-Verzeichnis
   * @param array $aLines bereits eingelesene Zeilen von complete.map
   * @param array $mapspec mit map-infos
   * @throws Exception
   */
  private function createMap($path, $aLines, $mapspec) {
    // checks
    $this->checkMapSpec($mapspec);

    // erste Zeilen einfach kopieren
    $firstLinesToCopy = 3;
    $newLines = array_slice($aLines, 0, $firstLinesToCopy);

    // gewünschte Zeilen und Spalten der alten Map hinzufügen
    $selectedLines = array_slice($aLines, $firstLinesToCopy + $mapspec['y1'], $mapspec['y2'] - $mapspec['y1']);
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
   * Eine Mapspec überprüfen
   * @param $mapspec
   * @throws Exception
   */
  private function checkMapSpec($mapspec) {
    if ($mapspec['x1'] >= $mapspec['x2']) {
      throw new Exception('x1 >= x2');
    }
    if ($mapspec['y1'] >= $mapspec['y2']) {
      throw new Exception('y1 >= y2');
    }
    // x1 muss gerade sein, weil sich sonst die Spalten gegeneinander verschieben!
    if ($mapspec['x1'] % 2 == 1) {
      throw new Exception('x1 ist ungerade: '.$mapspec['x1']);
    }
    foreach (['x1','x2','y1','y2'] as $index) {
      if ($mapspec[$index] > 84) {
        throw new Exception($index.' > 84: '.$mapspec[$index]);
      }
    }
  }

}

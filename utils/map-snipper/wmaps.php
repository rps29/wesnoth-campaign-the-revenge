#!/usr/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: fs
 * Date: 06.05.18
 * Time: 20:58
 */

require_once 'Maps.php';

try {
  $m = new Maps();
  $path = '/home/fs/.local/share/wesnoth/1.12/data/add-ons/The_Revenge/maps';
  $m->createAllMaps($path);

} catch (Exception $e) {

  echo $e->getMessage();
  exit;
}

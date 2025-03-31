<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2019 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

include(dirname(__FILE__).'/../../../config/config.inc.php');
include(dirname(__FILE__).'/../kldeliverynppro.php');

set_time_limit(0);
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
if (!Module::isInstalled('kldeliverynppro')) {
    die("Module kldeliverynppro is not install");
} else {
    $np = new KlDeliveryNPPro();
    if (!$argv[1] || !is_string($argv[1]) || ($argv[1] != 'city' && $argv[1] != 'warehouse')) {
        die("Incorrect value");
    }
    if ($argv[1] === 'city') {
        if ($np->updateCities()) {
            echo $np->l('Successful update of city data').PHP_EOL;
        } else {
            echo $np->l('Failed to update city data').PHP_EOL;
        }
    } elseif ($argv[1] === 'warehouse') {
        if ($np->updateWarehouses()) {
            echo $np->l('Successful update of warehouse data').PHP_EOL;
        } else {
            echo $np->l('Failed to update warehouse data').PHP_EOL;
        }
    }
}

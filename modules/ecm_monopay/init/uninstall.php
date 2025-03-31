<?php

$sql = array();
$sql[] ='DROP TABLE `'._DB_PREFIX_.'ecm_monopay`';

foreach ($sql as $s) {
			try {
				Db::getInstance()->Execute($s);
			} catch (Exception $e) {

			}
		}

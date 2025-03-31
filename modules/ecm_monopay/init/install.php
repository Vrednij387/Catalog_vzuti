<?php
$sql = array();
$sql[] = 'CREATE TABLE `' . _DB_PREFIX_ . 'ecm_monopay` (
			id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
			id_cart INT (20) NOT NULL,
			id_order INT (20) NOT NULL,
			invoice varchar(64),
			invoice_date_add datetime
			) CHARACTER SET utf8 COLLATE utf8_general_ci';

foreach ($sql as $s) {
    try {
        Db::getInstance()->Execute($s);
    } catch (Exception $e) {
    }
}

<?php
require('lib/phpqrcode.php');

if (!preg_match('/^([13]{1}[a-km-zA-HJ-NP-Z1-9]{26,33}|bc1[a-z0-9]{39,59})$/', $_GET['address'])) {
	die();
}
$address = $_GET['address'];

$plain = !empty($_GET['plain']) && $_GET['plain'] = 1;

$amount = '';
if (!empty($_GET['amount'])) {
	if (!is_numeric($_GET['amount'])) {
		die();
	} else {
		$amount = '?amount=' . $_GET['amount'];
	}
}

QRcode::png((!$plain ? 'bitcoin:' : '') . $address . $amount, false, QR_ECLEVEL_L, 5);

<?php

function is_decimal($val)
{
	return is_numeric($val) && floor($val) != $val;
}

function btc_decimals($number) {
	if ($number > 100 && !is_decimal($number)) {
		return 0;
	} if (!is_decimal($number * 100)) {
		return 2;
	} if (!is_decimal($number * 10000)) {
		return 4;
	} else {
		return 8;
	}
}

function amount_decimals($number) {
	if ($number >= 100 && !is_decimal($number)) {
		return 0;
	} else {
		return 2;
	}
}

// DEFINE ENV
if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$env = 'local';
	define('BASE_PATH', '/giveabit');
} elseif ($_SERVER['HTTP_HOST'] == 'u5633nw3xacdijij5co7kcl3jxp7qef2f24un7f6bmjfvdxsvwgcaead.onion') {
	$env = 'dark';
	define('BASE_PATH', '');
} else {
	$env = 'www';
	define('BASE_PATH', '');
}

// REDIRECT IF ADDRESS OR AMOUNT NOT VALID
if (!empty($_GET['address']) && !preg_match('/^([13]{1}[a-km-zA-HJ-NP-Z1-9]{26,33}|bc1[a-z0-9]{39,59})$/', $_GET['address'])) {
	header('Location: ' . BASE_PATH);
}
if (!empty($_GET['amount']) && !preg_match('/^([0-9]+)(\.[0-9]+)?([seug]?)$/', $_GET['amount'])) {
	header('Location: ' . BASE_PATH);
}

// STATS
if (!is_dir('stats')) {
	mkdir('stats');
}
$stats_filename = 'stats/visit_' . $env . '.json';
if (is_file($stats_filename)) {
	$stats = json_decode(file_get_contents($stats_filename), true);
} else {
	$stats = [];
}
if (empty($stats[date('Y-m-d')])) {
	$stats[date('Y-m-d')] = [
		'home' => 0,
		'address' => 0
	];
}

$stat_page = empty($_GET['address']) ? 'home' : 'address';
$stats[date('Y-m-d')][$stat_page]++;
file_put_contents($stats_filename, json_encode($stats));


// INCLUDE VIEWS
switch ($env) {
	case 'dark':
		require('dark/front.php');
		break;

	case 'local':
	default:
		require('www/front.php');
}

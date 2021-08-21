<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH ?>/assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH ?>/assets/css/fontawesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH ?>/assets/css/cover.css">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_PATH ?>/assets/css/custom.css">

	<title>Give a bit - Create your Bitcoin payment link in a minute</title>
	<meta name="description" content="With Give a bit you can create and share a payment link in a blink. Enter your wallet address and get paid easily. We care about your privacy.">
	<meta name="robots" content="<?php echo !empty($_GET['address']) ? 'noindex' : 'index' ?>, nofollow">

</head>

<body class="text-center">

	<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
		<header class="masthead mb-auto">
			<div class="inner">
				<h3 class="masthead-brand"><a href="<?php echo BASE_PATH ?>/">Give a <i class="fab fa-bitcoin"></i>it</a></h3>
				<nav class="nav nav-masthead justify-content-center">
					<a class="nav-link <?php echo (empty($_GET['address']) ? 'active' : '') ?>" href="<?php echo BASE_PATH ?>">Home</a>
					<a class="nav-link" href="javascript:;" data-toggle="modal" data-target="#createModal">Create your link</a>
				</nav>
			</div>
		</header>

		<main role="main" class="inner cover">
			<?php
			$orig_address = '';
			$orig_amount = '';
			$orig_currency = '';
			if (!empty($_GET['address'])) {
				/**** ADDRESS ****/
				$orig_address = $address = $_GET['address'];

				$len_dsk = 36;
				$len_mob = 18;
				$count_dsk = ceil(strlen($address) / $len_dsk);
				$count_mob = ceil(strlen($address) / $len_mob);
				$br_pos_dsk = ceil(strlen($address) / $count_dsk);
				$br_pos_mob = ceil(strlen($address) / $count_mob);

				$address_mob = $address_dsk = $address;
				for ($i = $count_dsk - 1; $i > 0; $i--) {
					$address_dsk = substr_replace($address_dsk, '<br>', $i * $br_pos_dsk, 0);
				}
				for ($i = $count_mob - 1; $i > 0; $i--) {
					$address_mob = substr_replace($address_mob, '<br>', $i * $br_pos_mob, 0);
				}

				/**** AMOUNT ****/
				$amount = '';
				$amount_str = '<i class="fab fa-bitcoin"></i>';
				$amount_warn = false;

				if (!empty($_GET['amount'])) {
					switch ($orig_currency = substr($_GET['amount'], -1)) {
						case 's':
							$orig_amount = intval(substr($_GET['amount'], 0, -1));
							$amount = $orig_amount / 100000000;
							$amount_str = '<nobr>' . number_format(intval($orig_amount)) . ' satoshi</nobr>';
							break;

						case 'e':
						case 'u':
						case 'g':
							$convS = file_get_contents('https://api.coindesk.com/v1/bpi/currentprice.json');
							if (empty($convS)) {
								$amount_warn = true;
								break;
							}
							$convJ = json_decode($convS, true);
							if (empty($convJ)) {
								$amount_warn = true;
								break;
							}

							$orig_amount = substr($_GET['amount'], 0, -1);
							$amount = floatval($orig_amount);
							$currency = substr($_GET['amount'], -1);
							switch ($currency) {
								case 'e':
									$amount_curr = number_format($amount, amount_decimals($amount), ',', '.') . ' EUR';
									$conv_rate = $convJ['bpi']['EUR']['rate_float'];
									$conv_rate_str = number_format($conv_rate, 2, ',', '.') . ' EUR / 1 BTC';
									break;
								case 'g':
									$amount_curr = number_format($amount, amount_decimals($amount), '.', ',') . ' GBP';
									$conv_rate = $convJ['bpi']['GBP']['rate_float'];
									$conv_rate_str = number_format($conv_rate, 2, '.', ',') . ' GBP / 1 BTC';
									break;
								case 'u':
									$amount_curr = number_format($amount, amount_decimals($amount), '.', ',') . ' USD';
									$conv_rate = $convJ['bpi']['USD']['rate_float'];
									$conv_rate_str = number_format($conv_rate, 2, '.', ',') . ' USD / 1 BTC';
									break;
								default:
									$amount_warn = true;
									break 2;
							}

							$amount = round($amount / $conv_rate, 8);
							$amount_str = '<nobr>' . number_format($amount, btc_decimals($amount)) . ' BTC</nobr>
							<nobr class="conv-rate-cont">(<span data-toggle="tooltip" data-placement="top" data-html="true" title="' . $conv_rate_str . ' Conversion rate at coindesk.com" class="conv-rate">' . $amount_curr . '</span>)</nobr>';
							break;

						default:
							$orig_amount = $amount = round(floatval($_GET['amount']), 8);
							$amount_str = '<nobr>' . number_format($amount, btc_decimals($amount)) . ' BTC</nobr>';
					}
				}
				echo '<h2>Send ' . $amount_str . ' to<br><pre class="mt-2 bit-address"><a class="nav-link" href="javascript:;" data-toggle="modal" data-target="#qrModal"><span class="d-none d-sm-block">' . $address_dsk . '</span><span class="d-block d-sm-none">' . $address_mob . '</span></a></pre></h2>';
				echo '<img src="' . BASE_PATH . '/generator.php?address=' . $address . (!empty($amount) ? '&amount=' . $amount : '') . '" class="qrPay" />';

				echo '<p class="lead">
				<a href="bitcoin:' . $address . (!empty($amount) ? '?amount=' . $amount : '') . '" class="btn btn-lg btn-secondary mt-3">Open your app</a>
				<a href="javascript:;" class="btn btn-lg btn-secondary mt-3" id="copy-paylink" data-clipboard-text="https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '">Copy link to clipboard</a>
			</p>';
			} else {
			?>
				<h1 class="cover-heading mb-3">Create your<br>Bitcoin payment link</h1>
				<p class="lead">Setup your payment link, share it and get paid easily.</p>
				<p class="lead">It's absolutely free, no registration is required and it's privacy friendly.</p>
				<p class="lead">
					<a href="javascript:;" data-toggle="modal" data-target="#createModal" class="btn btn-lg btn-secondary mt-4">Create your link</a>
				</p>
			<?php } ?>
		</main>

		<footer class="mastfoot mt-auto">
			<div class="inner">
				<p>
					<a href="javascript:;" data-toggle="modal" data-target="#privacyModal">Privacy &amp; ToS</a> |
					<?php if ($env == 'dark') { ?>
						<a href="javascript:;" data-toggle="modal" data-target="#versionModal">Clear web version</a> |
					<?php } else { ?>
						<a href="javascript:;" data-toggle="modal" data-target="#versionModal">Dark web version</a> |
					<?php } ?>
					<a href="<?php echo BASE_PATH ?>/<?php echo file_get_contents('coffee_address.php') ?>">Buy me a <i class="fas fa-coffee"></i></a>
				</p>
			</div>
		</footer>
	</div>

	<?php require('modals/privacy_' . ($env == 'local' ? 'www' : $env) . '.php'); ?>
	<?php require('modals/version_' . ($env == 'local' ? 'www' : $env) . '.php'); ?>
	<?php require('modals/create.php'); ?>
	<?php require('modals/qrcode.php'); ?>

	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="<?php echo BASE_PATH ?>/assets/js/jquery-3.4.1.slim.min.js"></script>
	<script src="<?php echo BASE_PATH ?>/assets/js/popper.min.js"></script>
	<script src="<?php echo BASE_PATH ?>/assets/js/bootstrap.min.js"></script>
	<script src="<?php echo BASE_PATH ?>/assets/js/clipboard.min.js"></script>
	<script>
		var BASE_PATH = '<?php echo BASE_PATH ?>';

		$(function() {
			$('[data-toggle="tooltip"]').tooltip();

			$('#copy-paylink').tooltip({
				trigger: 'manual',
				title: 'Copied!'
			});

			var clipboard = new ClipboardJS('#copy-paylink');
			clipboard.on('success', function(e) {
				$('#copy-paylink').tooltip('show');
				setTimeout(function() {
					$('#copy-paylink').tooltip('hide');
				}, 2000);
			});
		})
	</script>
</body>

</html>
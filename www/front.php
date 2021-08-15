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

	<title>Give a bit</title>
	<style>

	</style>
</head>

<body class="text-center">

	<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
		<header class="masthead mb-auto">
			<div class="inner">
				<h3 class="masthead-brand">Give a <i class="fab fa-bitcoin"></i>it</h3>
				<nav class="nav nav-masthead justify-content-center">
					<a class="nav-link <?php echo (empty($_GET['address']) ? 'active' : '') ?>" href="<?php echo BASE_PATH ?>">Home</a>
					<a class="nav-link" href="javascript:;" data-toggle="modal" data-target="#createModal">Create your link</a>
				</nav>
			</div>
		</header>

		<main role="main" class="inner cover">
			<?php
			if (!empty($_GET['address'])) {
				/**** ADDRESS ****/
				$address = $_GET['address'];

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
					switch (substr($_GET['amount'], -1)) {
						case 's':
							$amount = intval(substr($_GET['amount'], 0, -1)) / 100000000;
							$amount_str = '<nobr>' . number_format(intval(substr($_GET['amount'], 0, -1))) . ' satoshi</nobr>';
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

							$amount = floatval(substr($_GET['amount'], 0, -1));
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
							<nobr class="conv-rate-cont">(<span data-toggle="tooltip" data-placement="top" data-html="true" title="' . $conv_rate_str . ' Conversion rate" class="conv-rate">' . $amount_curr . '</span>)</nobr>';
							break;

						default:
							$amount = round(floatval($_GET['amount']), 8);
							$amount_str = '<nobr>' . number_format($amount, btc_decimals($amount)) . ' BTC</nobr>';
					}
				}
				echo '<h2>Send ' . $amount_str . ' to<br><pre class="mt-2" style="font-size:0.8em"><span class="d-none d-sm-block">' . $address_dsk . '</span><span class="d-block d-sm-none">' . $address_mob . '</span></pre></h2>';
				echo '<img src="' . BASE_PATH . '/generator.php?address=' . $address . (!empty($amount) ? '&amount=' . $amount : '') . '" />';

				echo '<p class="lead">
				<a href="bitcoin:' . $address . (!empty($amount) ? '?amount=' . $amount : '') . '" class="btn btn-lg btn-secondary mt-3">Send through your app</a>
				<a href="#" class="btn btn-lg btn-secondary mt-3">Share this link</a>
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
					<a href="javascript:;" data-toggle="modal" data-target="#versionModal">Dark web version</a> |
					<a href="<?php echo BASE_PATH ?>/bc1qs5pwuvnt38g5aqxgyr2snr9twxsqglaptfwqzyplqerqx0v44a6qhrhfla">Buy me a <i class="fas fa-coffee"></i></a>
				</p>
			</div>
		</footer>
	</div>

	<!-- Button trigger modal -->
	<!-- Modal -->
	<div class="modal fade" id="privacyModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Give a bit - Privacy and Terms of Service</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-left">
					<p>This information is related to the standard version of <i>Give a bit</i>. For more information related to the dark web version, <a href="javascript:;" data-dismiss="modal" data-toggle="modal" data-target="#versionModal">click here</a>.</p>
					<h4>In short...</h4>

					<table class="table specs">
						<tbody>
							<tr>
								<td style="width:60px"><span class="fa-stack" style="vertical-align: top;">
										<i class="fas fa-eye fa-stack-1x"></i>
										<i class="fas fa-ban fa-stack-2x" style="color:lightgray"></i>
									</span></td>
								<td style="width:50%">No tracking codes</td>
								<td style="width:60px"><span class="fa-stack" style="vertical-align: top;">
										<i class="fas fa-database fa-stack-1x"></i>
										<i class="fas fa-ban fa-stack-2x" style="color:lightgray"></i>
									</span></td>
								<td style="width:50%">No stored data</td>
							</tr>
							<tr>
								<td><span class="fa-stack" style="vertical-align: top;">
										<i class="fas fa-globe fa-stack-1x"></i>
										<i class="fas fa-ban fa-stack-2x" style="color:lightgray"></i>
									</span></td>
								<td>No ads, no user profilation</td>
								<td><span class="fa-stack" style="vertical-align: top;">
										<i class="fas fa-list fa-stack-1x"></i>
										<i class="fas fa-ban fa-stack-2x" style="color:lightgray"></i>
									</span></td>
								<td>No logs</td>
							</tr>
							<tr>
								<td><span class="fa-stack" style="vertical-align: top;">
										<i class="fas fa-cookie-bite fa-stack-1x"></i>
										<i class="fas fa-circle-notch fa-stack-2x" style="color:lightgray"></i>
									</span></td>
								<td>Only technical cookies</td>
								<td><span class="fa-stack" style="vertical-align: top;">
										<i class="fas fa-code fa-stack-1x"></i>
										<i class="fas fa-circle-notch fa-stack-2x" style="color:lightgray"></i>
									</span></td>
								<td>Open source (see on GitHub)</td>
							</tr>
							<tr>
								<td><span class="fa-stack" style="vertical-align: top;">
										<i class="fab fa-cloudflare fa-stack-1x"></i></i>
										<i class="fas fa-circle-notch fa-stack-2x" style="color:lightgray"></i>
									</span></td>
								<td>All the traffic go through CloudFlare, that protects (or at least tries to protect) the website against DDoS and malicious users.</td>
								<td><span class="fa-stack" style="vertical-align: top;">
										<i class="fas fa-code fa-stack-1x"></i>
										<i class="fas fa-circle-notch fa-stack-2x" style="color:lightgray"></i>
									</span></td>
								<td>...</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
					<h4>Full policy</h4>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="versionModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Give a bit - Dark web version</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-left">
					<p>I'm working on a dark web version of the website, completely anonymous. Stay tuned!</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Create a new payment link</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-left">
					<table class="table specs">
						<tbody>
							<tr>
								<td><span class="fa-stack" style="vertical-align: top;">
										<i class="fas fa-database fa-stack-1x"></i>
										<i class="fas fa-ban fa-stack-2x" style="color:lightgray"></i>
									</span></td>
								<td>Feel free to create any payment link you want: nothing of what you write here will be stored</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
					<form>
						<fieldset>
							<div class="form-group">
								<label for="btcAddress">Address: *</label>
								<input type="text" autocomplete="off" class="form-control" id="btcAddress" aria-describedby="btcAddress" placeholder="Your wallet address (required)">
							</div>
							<div class="form-group">
								<label for="btcAmount">Amount:</label>
								<div class="row">
									<div class="col-7 col-sm-9"><input type="text" autocomplete="off" class="form-control" id="btcAmount" aria-describedby="btcAmount" placeholder="not specified" onkeyup="amountFilter()"></div>
									<div class="col-5 col-sm-3">
										<select class="form-control" id="btcCurrency">
											<option value="">BTC</option>
											<option value="s">sat</option>
											<option value="e">EUR</option>
											<option value="u">USD</option>
											<option value="g">GBP</option>
										</select>
									</div>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary">Create it!</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		function amountFilter() {
			$('#btcAmount').val($('#btcAmount').val().replace(/([^0-9\.,])/g, '').replace(/,/g, '.').replace(/\.([0-9]*)\./g, '.$1').replace(/\.([0-9]{8})([0-9]*)/g, '.$1'));
		}
	</script>


	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="<?php echo BASE_PATH ?>/assets/js/jquery-3.4.1.slim.min.js"></script>
	<script src="<?php echo BASE_PATH ?>/assets/js/popper.min.js"></script>
	<script src="<?php echo BASE_PATH ?>/assets/js/bootstrap.min.js"></script>
	<script>
		$(function() {
			$('[data-toggle="tooltip"]').tooltip()
		})
	</script>
</body>

</html>
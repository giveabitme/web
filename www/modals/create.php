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
							<td style="width:60px"><span class="fa-stack" style="vertical-align: top;">
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
				<form action="javascript:create()">
					<fieldset>
						<div class="form-group">
							<label for="btcAddress">Address: *</label>
							<input type="text" value="<?php echo $orig_address ?>" autocomplete="off" class="form-control" id="btcAddress" aria-describedby="btcAddress" onkeyup="feedbackReset()" placeholder="Your wallet address (required)">
						</div>
						<div class="form-group">
							<label for="btcAmount">Amount:</label>
							<div class="row">
								<div class="col-7 col-sm-9"><input type="text" value="<?php echo $orig_amount ?>" autocomplete="off" class="form-control" id="btcAmount" aria-describedby="btcAmount" placeholder="not specified" onkeyup="amountFilter()"></div>
								<div class="col-5 col-sm-3">
									<select class="form-control" id="btcCurrency">
										<option value="">BTC</option>
										<option value="s" <?php if ($orig_currency == "s") echo 'selected'; ?>>sat</option>
										<option value="e" <?php if ($orig_currency == "e") echo 'selected'; ?>>EUR</option>
										<option value="u" <?php if ($orig_currency == "u") echo 'selected'; ?>>USD</option>
										<option value="g" <?php if ($orig_currency == "g") echo 'selected'; ?>>GBP</option>
									</select>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="create()" class="btn btn-primary">Create it!</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	function amountFilter() {
		$('#btcAmount').val($('#btcAmount').val().replace(/([^0-9\.,])/g, '').replace(/,/g, '.').replace(/\.([0-9]*)\./g, '.$1').replace(/\.([0-9]{8})([0-9]*)/g, '.$1'));
	}

	function feedbackReset() {
		$('.invalid-feedback').remove();
		$('.is-invalid').removeClass('is-invalid');
		$('.has-danger').removeClass('has-danger');
	}

	function create() {
		feedbackReset();

		var address = $('#btcAddress').val();
		var addressRegex = /([13]{1}[a-km-zA-HJ-NP-Z1-9]{26,33}|bc1[a-z0-9]{39,59})/;
		if (!addressRegex.test(address)) {
			$('#btcAddress').after('<div class="invalid-feedback">Please enter a valid address</div>');
			$('#btcAddress').parent().addClass('has-danger');
			$('#btcAddress').addClass('is-invalid');
			return;
		}

		var url = BASE_PATH + '/' + $('#btcAddress').val();
		if ($('#btcAmount').val().length > 0) {
			url += '/' + $('#btcAmount').val() + $('#btcCurrency').val();
		}
		document.location = url;
	}
</script>
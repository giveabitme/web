<!-- Modal -->
<div class="modal fade" id="qrBinanceModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Give a bit - Binance users</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body text-center">
				<table class="table specs">
					<tbody>
						<tr>
							<td style="width:60px">
								<i class="fas fa-exclamation-triangle fa-std"></i>
							</td>
							<td>
								Binance app's QR code reader doesn't support <code>bitcoin:</code> links, so the QR code on the main page does not work.<br>
								Use the following QR code to scan the wallet address to send the amount manually.
							</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>
				<?php
				echo '<img src="' . BASE_PATH . '/generator.php?address=' . $address . '&plain=1" />';
				echo '<pre class="mt-1 mb-0">' . $address_dsk . '</pre>';
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?php
/*
 * Helper class for mod_countrypal
 */
 class ModCountryPalHelper
 {
	 public function placeDonateButton()
	 {
	 ?>
	 <div class="paypal_pay">
		<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" >
			<input type="hidden" name="cmd" value="_s-xclick">
			<table>
			<tr><th><input type="hidden" name="on0" value="Help This Site Grow">Help This Site Grow</th></tr><tr><td><select name="os0">
			<option value="1.00">Donate $1.00 USD</option>
			<option value="2.00">Donate $2.00 USD</option>
			<option value="5.00">Donate $5.00 USD</option>
			<option value="10.00">Donate $10.00 USD</option>
			</select> </td></tr>
			</table>
			<input type="hidden" name="currency_code" value="USD">
			<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
			">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
		</form>
	 </div>	 
	 <?php
			
	 }
 }
?>

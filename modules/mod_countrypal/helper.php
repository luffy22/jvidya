<?php
/*
 * Helper class for mod_countrypal
 */
class ModCountryPalHelper
{
     public function placeDonateButton()
     {
         $getip = self::getClientIP($ipaddress);
         $continent = geoip_continent_code_by_name($getip);
         $country   = geoip_country_code_by_name($getip);
         $text      = '';
         $logo      = '';
         $value     = array();
         if($continent=='NA' && $country=='US')
         {
             $this->text    = 'USD';
             $this->logo    = '$';
             $this->value   = array('1', '2.50', '5.0', '10.00');
         }
         else if($continent=='NA' && $country== 'CA')
         {
             $this->text    = 'CAD';
             $this->logo    = '$';
             $this->value   = array('1', '2.50', '5.0', '10.00');
         }
         //echo $country.' '.$continent;
     ?>
     <div class="paypal_pay">
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <table>
            <tr><td><input type="hidden" name="on0" value="Help This Site Grow">Help This Site Grow</td></tr><tr><td><select name="os0">
                    <option value="Donate">Donate <?php echo trim($logo).$value['0'].trim($text); ?></option>
                    <option value="Donate">Donate <?php echo trim($logo).$value['1'].trim($text); ?></option>
                    <option value="Donate">Donate <?php echo trim($logo).$value['2'].trim($text); ?></option>
                    <option value="Donate">Donate <?php echo trim($logo).$value['3'].trim($text); ?></option>
            </select> </td></tr>
            </table>
            <input type="hidden" name="currency_code" value="<?php echo $text; ?>">
            <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
            ">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
            </form>
     </div>	 
     <?php
     }
     public function getClientIP(&$ipaddress)
     {
        $ipaddress = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(!empty($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(!empty($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(!empty($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(!empty($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

         return $ipaddress;
    }
 }
?>

<?php
	
/*
 * Helper class for Menu1 Module
 */
class modSocialHelper
{

	public function getFollow()
	{
	?>
        <table class="table-condensed">
            <tr>
                <td><div class="fb-follow" data-href="https://www.facebook.com/jyotishividya" data-width="20px" data-height="20px" data-colorscheme="light" data-layout="button" data-show-faces="false"></div></td>
                <td><div class="g-follow" data-annotation="none" data-height="20" data-href="https://plus.google.com/+JyotishividyaCoInplus" data-rel="publisher"></div></td>
                <td><a href="https://twitter.com/JyotishiVidya" class="twitter-follow-button" data-show-count="false" data-size="medium" data-show-screen-name="false">Follow</a></td>
            </tr>
        </table>
	<?php
           
	}
        
        
}

?>

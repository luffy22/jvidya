	<?php
defined('_JEXEC') or die;


class plgContentSocialnetwork extends JPlugin
{
	protected $autoloadLanguage = true;
	
	public function onContentAfterDisplay($context, &$article, &$params, $page=0)
	{
                $view = JFactory::getApplication()->input->getString('view', '');
		$parts = explode(".", $context);
		if($parts[0] != "com_content")
		{
			return false;
		}
		if($view == 'article')
		{
                    $uri = JUri::getInstance();
                ?> 
                    <div class="visible-desktop row">
                        <div class="plugin_adjust1">
                            <div class="fb-like" data-size="small" data-href="<?php echo $uri; ?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
                            <div class="fb-share-button" data-size="small" data-href="<?php echo $uri; ?>" data-type="button_count"></div>
                        </div>
                        <div class="plugin_adjust2">
                            <a href="https://twitter.com/share" class="twitter-share-button" data-href="<?php echo $uri; ?>">Tweet</a>
                            <div class="g-plus" data-size="medium" data-href="<?php echo $uri; ?>" data-action="share" data-annotation="bubble"></div>
                            <div class="g-plusone" data-size="medium" data-href="<?php echo $uri; ?>"></div>
                        </div>
                    </div>
               <?php 
                    return;
		}
		
	}
}
?>

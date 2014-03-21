	<?php
defined('_JEXEC') or die;


class plgContentSocialnetwork extends JPlugin
{
	protected $autoloadLanguage = true;
	
	public function onContentAfterTitle($context, &$article, &$params, $page=0)
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
                    <div class="visible-desktop">
                        <div class="fb-like" data-href="<?php echo $uri; ?>" data-layout="box_count" data-action="like" data-show-faces="false" data-share="false"></div>
                    </div>
               <?php 
                    return;
		}
		
	}
}
?>

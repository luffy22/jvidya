<?php 
    //error_reporting(0);
    defined( '_JEXEC' ) or die( 'Restricted access' );
    JHtml::_('bootstrap.framework'); // Loads the bootstrap framework in noConflict Mode
    JHtml::_('jquery.framework');       // Loads the Jquery framework in noConflict Mode

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" 
   xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<jdoc:include type="head" />
<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/style.css" type="text/css" />
<script type="text/javascript" language="javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/common.js"></script>
</head>
<body>
<!--The main header container -->
<div class="main-header">
    <div class="header-inner">
        <a href="index.php"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/jv_logo.png" alt="Jyotishi Vidya" title="Navigate to Home Page" height="150" width="400" /></a>    
    </div>
    <div class="header-menu visible-desktop">
        <ul class="nav nav-pills visible-desktop">
            <li class="home_icon">
                <a href="index.php"><img src="<?php echo $this->baseurl; ?>/images/home_logo.png" alt="Jyotishi Vidya" title="Navigate to Home Page" width="35px" height="35px" /></a>
            </li>
            <li class="dropdown">
                <jdoc:include type="modules" name="menu" style="none" />
            </li>
            <li class="dropdown">
                <jdoc:include type="modules" name="menu1" style="none" />
            </li>
            <li class="dropdown">
                <jdoc:include type="modules" name="menu2" style="none" />
            </li>
            <li class="dropdown">
                <jdoc:include type="modules" name="menu3" style="none" />
            </li>
            <li class="dropdown">
                <jdoc:include type="modules" name="menu4" style="none" />
            </li>
        </ul>
    </div>
</div>
<!-- Gives some space between header and body-->
<div class="spacer"></div>
<!-- The main body container -->
<div class="main-section">
    <div class ="container-fluid">
    <div class="span3 visible-desktop">
        <jdoc:include type="modules" name="userlogin" style="none" />
    </div>
    <div class="span6">
        <jdoc:include type="modules" name="breadcrumbs" style="none" />
        <jdoc:include type="component" />
        <jdoc:include type="message" />
    </div>
     <div class="span3 visible-desktop">
        <jdoc:include type="modules" name="socialplugins" style="none" />
    </div>
</div>
</div>
<div class="spacer"></div>
<div class="footer">
    <div class="footer-text">
        <jdoc:include type="modules" name="footer" />
    </div>
</div>
<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<!-- Place this tag after the last widget tag. -->
			<script type="text/javascript">
			(function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/platform.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			})();
			</script>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</body>
</html>

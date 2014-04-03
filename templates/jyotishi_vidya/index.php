<?php 
    error_reporting(0);
    defined( '_JEXEC' ) or die( 'Restricted access' );
    //JHtml::_('jquery.framework');       // Loads the Jquery framework in noConflict Mode
    //JHtml::_('bootstrap.framework'); // Loads the bootstrap framework in noConflict Mode
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" 
   xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap-responsive.min.css"/>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/style.css" type="text/css" />
<script async src="<?php echo $this->baseurl?>/media/jui/js/jquery.min.js" type="text/javascript" language="javascript"></script>
<script async src="<?php echo $this->baseurl?>/media/jui/js/jquery-noconflict.js"></script>
<script async src="<?php echo $this->baseurl?>/media/jui/js/jquery-migrate.js"></script>
<script async src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/common.js" type="text/javascript" language="javascript"></script>
<script>
  (function() {
    var cx = '006812877761787834600:kranbsbb5p8';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.async = true;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=220390744824296";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- Place this tag after the last widget tag. -->
<script type="text/javascript">
(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
po.src = 'https://apis.google.com/js/platform.js';
po.async = true;
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
</head>
<body>
<div id="fb-root"></div>
<!--The main header container -->
<div class="main-header">
    <div class="header-logo">
        <div class="header-inner">
            <a href="index.php"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/jv_logo.png" alt="Jyotishi Vidya" title="Navigate to Home Page" height="150" width="400" /></a>    
        </div>
        <div class="login-module">
            <jdoc:include type="modules" name="userlogin" style="none" />
        </div>
    </div>
    <div class="header-menu visible-desktop">
        <div class="home_icon">
            <a href="index.php"><img src="<?php echo $this->baseurl; ?>/images/home_logo.png" alt="Jyotishi Vidya" title="Navigate to Home Page" width="35px" height="35px" /></a>
        </div>
        <div class="navigation_menu">
            <ul class="nav nav-pills visible-desktop">
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
</div>
<!-- Gives some space between header and body-->
<div class="spacer"></div>
<div class="webbanner"><jdoc:include type="modules" name="jbanner" style="none" /></div>
<!-- The main body container -->
<div class="main-section">
    <div class ="container-fluid">
    <div class="span3 visible-desktop" id="sidemenu_left">
        <jdoc:include type="modules" name="sidebar" style="none" />
    </div>
    <div class="span6">
	<!--Google Search Engine Code  -->
        <gcse:search></gcse:search>
    </div>
    <div class="spacermini"></div>
    <div class="span6">
        <div class ="hidden-desktop"><a href="#" id="left_menu" text="Tap Twice for menu"><img src="<?php echo $this->baseurl; ?>/images/menu.png" alt="Show Menu" title="Show Menu" height="20px" width="20px" /></a></div>
        <jdoc:include type="modules" name="breadcrumbs" style="none" />
        <div class="main-content" id="main-content">
            <jdoc:include type="modules" name="articleslider" style="none" />
        </div>
        <div class="mini-spacer"></div>
        <div class="main-content" id="main-content2">
            <jdoc:include type="modules" name="articleslider2" style="none" />
        </div>
        <jdoc:include type="component" />
        <jdoc:include type="message" />
        <jdoc:include type="modules" name="relatedarticles" style="none" />
    </div>
    <div class="span3 visible-desktop">
        <div class="plugin">
            <jdoc:include type="modules" name="fblikeplugin" style="none" />
        </div>
        <div class="spacer"></div>
        <div class="plugin">
            <jdoc:include type="modules" name="socialplugins" style="none" />
        </div>
        <div class="spacer"></div>
        <div class="plugin">
            <jdoc:include type="modules" name="paypaldonate" style="none" />
        </div>
    </div>
</div>
</div>
<div class="spacer"></div>
<div class="footer">
    <div class="footer-text">
        <jdoc:include type="modules" name="footer" />
    </div>
</div>
</body>
</html>

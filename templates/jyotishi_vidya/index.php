<?php 
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
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/style.css" type="text/css" />
</head>
<body>
<div class="main-header">
    <div class="container">
        <h1><a href="index.php"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/jv_logo.png" alt="Jyotishi Vidya" title="Navigate to Home Page" height="150" width="400" /></a></h1>
    </div>
</div>
<div class="spacer"></div>
<div class="main-section">
    <div class ="container-fluid">
    <div class="span3">
        <jdoc:include type="modules" name="sidebar" style="none" />
    </div>
    <div class="span6">
        <jdoc:include type="modules" name="breadcrumbs" style="none" />
        <jdoc:include type="component" />
    </div>
     <div class="span3">
        <jdoc:include type="modules" name="userlogin" style="none" />
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
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$cookieLogin = $this->user->get('cookieLogin');

if($this->user->get('guest')|| (!empty($cookieLogin)))
{
    // The user is logged in or needs a password
    echo $this->loadTemplate('login');
}
else
{
    // load the logout template
    echo $this->loadTemplate('logout');
}
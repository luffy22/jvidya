<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Registration controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.6
 */
class AstrologinControllerProcess extends UsersController
{

if(isset($_POST['register']))
{
   echo "success";
}
else if(isset($_POST['fblogin']))
{
    echo "fb login successful";
}
else
{
    echo "fail";
}
?>

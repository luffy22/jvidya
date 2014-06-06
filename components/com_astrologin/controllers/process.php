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
class AstrologinControllerProcess extends AstroLoginController
{
    public function userregister()
    {
        if(isset($_POST['register']))
        {
            include_once $_SERVER['DOCUMENT_ROOT'] . '/jvidya/securimage/securimage.php';
            $securimage = new Securimage();

            if ($securimage->check($_POST['captcha_code']) == false)
            {
                // the code was incorrect
                // you should handle the error so that the form processor doesn't continue

                // or you can use the following code if there is no validation or you do not know how
                echo "The security code entered was incorrect.<br /><br />";
                echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
                exit;
            }
            else 
            {
                $model          = $this->getModel('Process', 'AstroLoginModel');
                $username       = $_POST['username'];
                $password       = sha1($_POST['password']);
                $email          = $_POST['email'];
                $logintype      = '1';
                $joindate       = date("Y/m/d-G:i:s");
                $webauthcode    = 'webauth_'.rand(1000000,9999999);

                $user_details   = array('username'=>$username,'password'=> $password, 'email'=> $email,'logintype'=> $logintype,'joindate'=>$joindate,'webauthcode'=>$webauthcode);
                $model          = &$this->getModel('process');  // Add the array to model
                $model->registerUser($user_details);
            }
        }
        else if(isset($_POST['fblogin']))
        {
            echo "fb login successful";
        }
        else
        {
            echo "fail";
        }
    }
}
?>

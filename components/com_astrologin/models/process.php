<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class AstroLoginModelProcess extends JModelItem
{
    public $username;
    public $email;
    public function registerUser($user_details)
    {
        $this->username     = $user_details['username'];
        $password           = sha1($user_details['password']);
        $this->email        = $user_details['email'];
        $logintype          = $user_details['logintype'];
        $joindate           = $user_details['joindate'];
        $webauthcode        = $user_details['webauthcode'];

        //$session            = JFactory::getSession();
        //$session            ->set('cusername', $username);
        //$session            ->set('cemail', $email);
        if($this->checkUsername()==false)
        {
            echo "Username already exists in database";

        }
        else if($this->checkEmail()==false)
        {
            echo "Email already exists in the database";
        }
        else
        {
            $db             = JFactory::getDbo();  // Get db connection
            $query          = $db->getQuery(true);

            //$query    = $db->getQuery(true);
            $columns        = array('username','password','email', 'logintype', 'joindate', 'webauthcode');
            $values         = array($db->quote($this->username), $db->quote($password), $db->quote($this->email),
                                    $db->quote($logintype), $db->quote($joindate), $db->quote($webauthcode));
            // Prepare the insert query
            $query    ->insert($db->quoteName('#__webusers'))
                            ->columns($db->quoteName($columns))
                            ->values(implode(',', $values));
            // Set the query using our newly populated query object and execute it
            $db             ->setQuery($query);
            $result          = $db->query();

            if($result)
            {
               header('Location: index.php');
            }
            else
            {
                echo "Fail to add data";
            }
        }
    }
    public function checkUsername()
    {
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $username       = $this->username;
        $query          -> select('COUNT(*)');
        $query          -> from($db->quoteName('#__webusers'));
        $query          -> where($db->quoteName('username').'='.$db->quote($username));
        $db             ->setQuery($query);
        $count          = count($db->loadResult());
   
        if($count>0)
        {
           return false;
        }
        else
        {
            return true;
        }
    }
     public function checkEmail()
    {
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $email          = $this->email;
        $query          -> select('COUNT(*)');
        $query          -> from($db->quoteName('#__webusers'));
        $query          -> where($db->quoteName('email').'='.$db->quote($email));
        $db             ->setQuery($query);
        $count          = count($db->loadResult());
        
        if($count>0)
        {
           return false;
        }
        else
        {
            return true;
        }
    }
    function getDetails($login_details)
    {
        $loginuname     = $login_details['username'];
        $loginpwd       = sha1($login_details['password']);

        $remember       = $login_details['rememberme'];
        
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          ->select($db->quoteName(array('username', 'password', 'email','verification')), COUNT(1));
        $query          ->from($db->quoteName('#__webusers'));
        $query          ->where(($db->quoteName('username').'='.$db->quote($loginuname))AND($db->quoteName('password').'='.$db->quote($loginpwd)));
        $db             ->setQuery($query);
        $count          = count($db->loadResult());
        $row            =$db->loadAssoc();

        if($count>0&&$row['verification']!= '0')
        {
            $lastlogin  = date("Y/m/d-G:i:s");
            $query      ->clear();
            
            $query      ->getQuery(true);
            $query      ->update($db->quoteName('#__webusers'))
                        ->set($db->quoteName('lastlogin').'='.$db->quote($lastlogin))
                        ->where($db->quoteName('username').'='.$db->quote($loginuname));
            $db         ->setQuery($query);
            $insertlogin   =$db->query();

            $session    =& JFactory::getSession();
            $session    ->set( 'username', $row['username'] );
            $session    ->set('email',$row['email']);
            $app        =&JFactory::getApplication();
            $app        ->redirect('index.php');        
           
        }
        else if($count>0&&$row['verification']=='0')
        {
            $email      = $row['email'];
            $app        =&JFactory::getApplication();
            $app        ->redirect("index.php?option=com_astrologin&view=validateuser&email='$email'"); 
        }
        else
        {
            echo "<br/>Invalid Login Credentials";
        }
    }
   public function logoutuser($user)
   {
       $session         =& JFactory::getSession();
       $sessuser        = $session->get('username');

       if((!empty($sessuser))&&($sessuser==$user))
       {
           $session->clear('username');
           $session->clear('email');
           $app        =&JFactory::getApplication();
           $app        ->redirect('index.php'); 
       }
   }
}
?>

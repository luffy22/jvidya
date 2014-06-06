<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class AstroLoginModelProcess extends JModelItem
{
    public function registerUser($user_details)
    {
        $username       = $user_details['username'];
        $password       = $user_details['password'];
        $email          = $user_details['email'];
        $logintype      = $user_details['logintype'];
        $joindate       = $user_details['joindate'];
        $webauthcode    = $user_details['webauthcode'];
       
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        
        /*$query         -> select($db->quoteName(array('username', 'email')));
        $query         -> from($db->quoteName('#__webusers'));
        $query          -> where($db->quoteName('username').'LIKE'.$db->quote($username)||$db->quoteName('email').'LIKE'.$db->quote($email));
        $db             ->setQuery($query);
        $row            = $db->loadRow();
        
        if($results>0)
        {
            echo "Email or Username already exists. Please try alternate Username/Email";
        }
        else
        {*/
            //$query    = $db->getQuery(true);
            $columns        = array('username','password','email', 'logintype', 'joindate', 'webauthcode');
            $values         = array($db->quote($username), $db->quote($password), $db->quote($email),
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
        //}
    }
}
?>

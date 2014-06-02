<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//jimport('joomla.user.helper');

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

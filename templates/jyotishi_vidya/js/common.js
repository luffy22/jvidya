/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// Dropdown menu is closed & opened using this function.
function toggleMenu(id)
{
    var menu_id = id;
    jQuery('#menu_id').dropdown();
}

// Simple function to show the login box
function showLogin()
{
   jQuery('#login-link').css('visibility','hidden');
   jQuery('#login-form2').css('visibility', 'visible');
   
   jQuery('#login-form2').modal('toggle');
}
// Simple function to hide the login box
function hideLogin()
{
   jQuery('#login-link').css('visibility','visible');
   jQuery('#login-form2').css('visibility', 'hidden');
}

// The below function validates the login form
function validateLoginForm()
{
    var uname   = document.getElementById("modlgn-uname");
    var upass    = document.getElementById("modlgn-passwd");    
    var uid1    = document.getElementById("uid1");
    var uid2    = document.getElementById("uid2");
    
    
    uid1.style.visibility   = "hidden";
    uid2.style.visibility   = "hidden";
    uname.style.background  = "none";
    upass.style.background   = "none";
    
    if(uname.value == ""||uname.value == null)
    {
        jQuery("#uid1").css('visibility','visible');
        jQuery("#modlgn-uname").css('background', '#FF0000');
        return false;
    }
    else if(upass.value == ""||upass.value == null)
    {
        jQuery("#uid2").css('visibility','visible');
        jQuery("#modlgn-passwd").css('background', '#FF0000');
        return false;
    }
    else
    {
        alert("Done...");
    }
}
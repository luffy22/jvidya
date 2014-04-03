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
   jQuery('#login-form').css('visibility', 'visible');
   
   jQuery('#login-form').modal('toggle');
   return false;
}
// Simple function to hide the login box
function hideLogin()
{
   jQuery('#login-link').css('visibility','visible');
   jQuery('#login-form').css('visibility', 'hidden');
   return false;
   /*jQuery('#uid1').css('visibility', 'hidden');
   jQuery('#uid2').css('visibility', 'hidden');
   jQuery("#modlgn-uname").css('background', '#FFFFFF');
   jQuery("#modlgn-passwd").css('background', '#FFFFFF');*/
}

function hideLoginField()
{
    jQuery('#login-link').css('visibility','hidden');
    jQuery('.login-form').css('visibility','visible');
    return false;
}


jQuery('#left_menu').on('tap', function()
{
    alert("works");
    /*jQuery('#sidemenu_left').removeClass('visible-desktop');
    jQuery('#sidemenu_left').toggle();
    jQuery('#main-content').hide();
    jQuery('#sidemenu_left').css('position', 'absolute');
    jQuery('#sidemenu_left').css('zindex', '10010')
    jQuery('#sidemenu_left').css('width', '180px');
    jQuery('#sidemenu_left').css('height','100%');
    jQuery('#sidemenu_left').css('left', '0' );
    jQuery('#sidemenu_left').css('top', '350px');*/
})

// The below function validates the login form
/*function validateLoginForm()
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
        form.submit();
        return true;
    }
}*/

jQuery("#slideshow > div:gt(0)").hide();

setInterval(function() { 
jQuery('#slideshow > div:first')
    .fadeOut(1000)
    .next()
    .fadeIn(1000)
    .end()
    .appendTo('#slideshow');
},  3000);

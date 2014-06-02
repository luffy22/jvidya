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


function showSideMenu()
{
    jQuery('#sidemenu_left').removeClass('visible-desktop');
    jQuery('#sidemenu_left').toggle();
    jQuery('#main-content').hide();
    jQuery('#sidemenu_left').css('position', 'absolute');
    jQuery('#sidemenu_left').css('zindex', '10010')
    jQuery('#sidemenu_left').css('width', '180px');
    jQuery('#sidemenu_left').css('height','100%');
    jQuery('#sidemenu_left').css('left', '0' );
    jQuery('#sidemenu_left').css('top', '350px');
}
function validateLogin()
{
    var uname = document.getElementById("al_uname").value;
    var passwd = document.getElementById("al_passwd").value;
    var dirname = '/jvidya/components/com_astrologin/controllers/process.php';
    var data    = "data";
    jQuery.ajax({
        type: "POST",
        url : dirname,
        data: "uname="+uname+"&passwd="+passwd+"&data="+data,
        dataType: 'text',
        
    }).done(function(data){alert(data)}).fail(function(){alert("fail")});
}
function validateRegister()
{
    var uname   = document.getElementById("ar_uname");
    var passwd  = document.getElementById("ar_passwd");
    var cpasswd = document.getElementById("ar_cpasswd");
    var email   = document.getElementById("ar_email");
    
    if(uname.value==""||uname.value.length<5||uname.value.length>14)
    {
        alert("Enter 5-14 length alpha-numeric username");
        return false;
    }
    else if(passwd.value==""||passwd.value.length<5||passwd.value.length>14)
    {
        alert("Enter 5-14 length alpha-numeric username");
        return false;
    }
    else if(passwd.value != cpasswd.value)
    {
        alert("Passwords do not match");
        return false;
    }
    else if(email.value=="")
    {
        alert("Please enter a valid email");
        return false;
    }
    else
    {
        form.submit();
        return true;
    }
}
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

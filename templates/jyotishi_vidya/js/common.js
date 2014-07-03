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
   
   form.submit();
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

jQuery("#slideshow > div:gt(0)").hide();

setInterval(function() { 
jQuery('#slideshow > div:first')
    .fadeOut(1000)
    .next()
    .fadeIn(1000)
    .end()
    .appendTo('#slideshow');
},  3000);

function LoginUser()
{
    document.getElementById("loadergif").style.display = 'block';
    var moduname        = document.getElementById("mod-uname");
    var modpwd          = document.getElementById("mod-pwd"); 
    //var modcred         = jQuery.parseJSON('{"uname":moduname.value,"pwd":modpwd.value}');
    
    var request = jQuery.ajax({
    url: "index.php?option=com_ajax&module=droplogin&format=raw&method=LoginUser",
    data: "uname="+moduname.value+"&pwd="+modpwd.value,
    dataType: "text"
});
   request.done(function(msg)
   {
       if(msg=='invalid')
       {
           document.getElementById('error-msg').style.visibility = "visible";
           document.getElementById("error-msg").innerHTML = "Invalid Login Credentials";
           document.getElementById("loadergif").style.display = 'none';
       }
       else if(msg=='no-auth')
       {
           document.getElementById('error-msg').style.visibility = "visible";
           document.getElementById("error-msg").innerHTML = "Please confirm email to register";
           document.getElementById("loadergif").style.display = 'none';
       }
       else
       {
           document.getElementById("login-cred").innerHTML = msg;
           document.getElementById("loadergif").style.display = 'none';
       }
   });
   request.fail(function()
   {
       document.getElementById("login-cred").innerHTML  = "Unable to Fetch Login Credentials...";
       document.getElementById("loadergif").style.display = 'none';
   }); 
}
function getGirlsNakshatra()
{
    document.getElementById("loadergif").style.display = 'block';
    var g_rashi  = document.getElementById("g_rashi");
    g_rashi.style.background = "none";
    if(g_rashi.value=="")
    {
        g_rashi.style.background = "#FF0000";
        document.getElementById("g_rashi_notice").innerHTML = "Please select a rashi for girl";
        document.getElementById("loadergif").style.display = 'none';
    }
    else
    {
        var request = jQuery.ajax({
         url: "index.php?option=com_ajax&module=nakshatracompat&format=raw&method=GirlNakshatra",
        data: "g_rashi="+g_rashi.value,
        dataType: "text"
        });
        request.done(function(msg)
        {
            if(msg=="error")
            {
                document.getElementById('g_nakshatra_notice').innerHTML = "Failed to fetch data";
                document.getElementById("loadergif").style.display = 'none';
            }
            else
            {
                document.getElementById('g_nakshatra').innerHTML = msg;
                document.getElementById("loadergif").style.display = 'none';
            }
        });
        request.fail(function()
        {
            alert("Fail to get data");
        });
    }
}
function getGirlsPada()
{
    var g_nakshatra  = document.getElementById("g_nakshatra");
    var g_rashi      = document.getElementById("g_rashi");
    document.getElementById("loadergif").style.display = 'block';
    g_nakshatra.style.background = "none";
    if(g_nakshatra.value=="")
    {
        g_nakshatra.style.background = "#FF0000";
        document.getElementById("g_nakshatra_notice").innerHTML = "Please select a nakshatra for girl";
        document.getElementById("loadergif").style.display = 'none';
    }
    else if(g_rashi.value=="")
    {
        g_rashi.style.background = "#FF0000";
        document.getElementById("g_rashi_notice").innerHTML = "Please select a rashi for girl";
        document.getElementById("loadergif").style.display = 'none';
    }
    else
    {
        var request = jQuery.ajax({
         url: "index.php?option=com_ajax&module=nakshatracompat&format=raw&method=GirlPada",
        data: "g_nakshatra="+g_nakshatra.value+'&g_rashi='+g_rashi.value,
        dataType: "text"
        });
        request.done(function(msg)
        {
            document.getElementById('g_pada').innerHTML = msg;
            document.getElementById("loadergif").style.display = 'none';
        });
        request.fail(function()
        {
            alert("Fail to get data");
            document.getElementById("loadergif").style.display = 'none';
        });
    }
}
function getBoysNakshatra()
{
    document.getElementById("loadergif").style.display = 'block';
    var b_rashi  = document.getElementById("b_rashi");
    b_rashi.style.background = "none";
    if(b_rashi.value=="")
    {
        b_rashi.style.background = "#FF0000";
        document.getElementById("b_rashi_notice").innerHTML = "Please select a rashi for girl";
        document.getElementById("loadergif").style.display = 'none';
    }
    else
    {
        var request = jQuery.ajax({
         url: "index.php?option=com_ajax&module=nakshatracompat&format=raw&method=BoyNakshatra",
        data: "b_rashi="+b_rashi.value,
        dataType: "text"
        });
        request.done(function(msg)
        {
            if(msg=="error")
            {
                document.getElementById('b_nakshatra_notice').innerHTML = "Failed to fetch data";
                document.getElementById("loadergif").style.display = 'none';
            }
            else
            {
                document.getElementById('b_nakshatra').innerHTML = msg;
                document.getElementById("loadergif").style.display = 'none';
            }
        });
        request.fail(function()
        {
            alert("Fail to get data");
        });
    }
}
function getBoysPada()
{
    var b_nakshatra  = document.getElementById("b_nakshatra");
    var b_rashi      = document.getElementById("b_rashi");
    document.getElementById("loadergif").style.display = 'block';
    b_nakshatra.style.background = "none";
    if(b_nakshatra.value=="")
    {
        b_nakshatra.style.background = "#FF0000";
        document.getElementById("b_nakshatra_notice").innerHTML = "Please select a nakshatra for girl";
        document.getElementById("loadergif").style.display = 'none';
    }
    else if(b_rashi.value=="")
    {
        b_rashi.style.background = "#FF0000";
        document.getElementById("b_rashi_notice").innerHTML = "Please select a rashi for girl";
        document.getElementById("loadergif").style.display = 'none';
    }
    else
    {
        var request = jQuery.ajax({
         url: "index.php?option=com_ajax&module=nakshatracompat&format=raw&method=BoyPada",
        data: "b_nakshatra="+b_nakshatra.value+'&b_rashi='+b_rashi.value,
        dataType: "text"
        });
        request.done(function(msg)
        {
            document.getElementById('b_pada').innerHTML = msg;
            document.getElementById("loadergif").style.display = 'none';
        });
        request.fail(function()
        {
            alert("Fail to get data");
            document.getElementById("loadergif").style.display = 'none';
        });
    }
}
function checkCompatibility()
{
    var g_1     = document.getElementById("g_rashi");
    var g_2         = document.getElementById("g_nakshatra");
    var g_3          = document.getElementById("g_pada"); 
    var b_1     = document.getElementById("b_rashi");
    var b_2         = document.getElementById("b_nakshatra");
    var b_3          = document.getElementById("b_pada");
    document.getElementById("loadergif").style.display = 'block';
    if(g_1.value==""||g_2.value==""||g_3.value=="")
    {
        alert("One of the values is missing for girl");
        document.getElementById("loadergif").style.display = 'none';
    }
    else if(b_1.value==""||b_2.value==""||b_3.value=="")
    {
        alert("One of the values is missing for boy");
        document.getElementById("loadergif").style.display = 'none';
    }
    else
    {
        var request = jQuery.ajax({
         url: "index.php?option=com_ajax&module=nakshatracompat&format=raw&method=GetPoints",
        data: "g_1="+g_1.value+'&g_2='+g_2.value+'&g_3='+g_3.value+"&b_1="+b_1.value+'&b_2='+b_2.value+'&b_3='+b_3.value,
        dataType: "text"
        });
        request.done(function(msg)
        {
              if(msg=="error")
              {
                  alert("Fail to get data");
                  document.getElementById("loadergif").style.display = 'none';
              }
              else
              {
                var pts = parseInt(msg);
                document.getElementById("loadergif").style.display = 'none';
                if(pts<18)
                {
                    document.getElementById('match_message').innerHTML = "Match Points are "+msg+" (Not Good Match)";
                    document.getElementById("match_message").style.background = "#FF0000";
                }
                else if(pts>18&&pts<28)
                {
                    document.getElementById('match_message').innerHTML = "Match Points are "+msg+" (Decent Match)";
                    document.getElementById("match_message").style.background = "#FFFF00";
                }
                else if(pts>28)
                {
                    document.getElementById('match_message').innerHTML = "Match Points are "+msg+" (Good Match)";
                    document.getElementById("match_message").style.background = "#00FF00";
                }
              }
        });
        request.fail(function()
        {
            alert("Fail to get data");
            document.getElementById("loadergif").style.display = 'none';
        });
    }
}
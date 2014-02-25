/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function toggleMenu(id)
{
    var menu_id = id;
    jQuery('#menu_id').dropdown();
}

function showLogin()
{
   jQuery('#login-link').css('visibility','hidden');
   jQuery('#login-form').css('visibility', 'visible');
   
   jQuery('#login-form').modal('toggle');
}
function hideLogin()
{
   jQuery('#login-link').css('visibility','visible');
   jQuery('#login-form').css('visibility', 'hidden');
}
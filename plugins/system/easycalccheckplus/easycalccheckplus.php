<?php
/**
 *  @Copyright
 *  @package        EasyCalcCheck Plus
 *  @author         Viktor Vogel {@link http://www.kubik-rubik.de}
 *  @version        3-1 - 2013-11-17 (Based on 2.5-8 for Joomla! 2.5)
 *  @link           Project Site {@link http://joomla-extensions.kubik-rubik.de/ecc-easycalccheck-plus}
 *
 *  @license GNU/GPL
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Restricted access');

class PlgSystemEasyCalcCheckPlus extends JPlugin
{
    protected $_load_ecc;
    protected $_load_ecc_check;
    protected $_session;
    protected $_extension_info;
    protected $_redirect_url;
    protected $_custom_call;
    protected $_warning_shown;
    protected $_debug_plugin;
    protected $_request;
    protected $_user;
    protected $_app;

    function __construct(&$subject, $config)
    {
        $this->loadLanguage('plg_system_easycalccheckplus', JPATH_ADMINISTRATOR);

        // Check Joomla version
        $version = new JVersion();

        if($version->PRODUCT == 'Joomla!' AND $version->RELEASE < '3.0')
        {
            JError::raiseWarning(100, JText::_('PLG_ECC_WRONGJOOMLAVERSION'));
            return;
        }

        parent::__construct($subject, $config);

        $this->_load_ecc = false;
        $this->_load_ecc_check = false;
        $this->_session = JFactory::getSession();
        $this->_extension_info = array();

        $this->_redirect_url = $this->_session->get('redirect_url', null, 'easycalccheck');
        $this->_session->clear('redirect_url', 'easycalccheck');

        if(empty($this->_redirect_url))
        {
            $this->_redirect_url = JFactory::getURI()->toString();
        }

        // Set the input object
        $this->_request = JFactory::getApplication()->input;

        // Global JUser object
        $this->_user = JFactory::getUser();

        // Global JApplication object
        $this->_app = JFactory::getApplication();

        // Check whether the debug plugin is activated - important workaround for Joomla! 3.x
        // This is important because if the plugin is activated, then the input request variables are set before ECC+
        // can decode them back. This means that the components do not use the correct request variables if the option
        // "Encode all fields" is used in ECC+. If the plugin is activated, then we can not use the encode functionality!
        if(JPluginHelper::isEnabled('system', 'debug'))
        {
            $this->_debug_plugin = true;
        }
        else
        {
            $this->_debug_plugin = false;
        }
    }

    // Purge Cache, Bot-Trap, SQL Injection Protection & Backend Token
    function onAfterInitialise()
    {
        // Okay, if we use the encoding option, then we have to decode all fields as soon as possible to avoid errors
        // Decode all input fields but only if debug plugin is not used
        if($this->params->get('encode') AND empty($this->_debug_plugin))
        {
            $this->decodeFields();
        }

        // Clean page cache if System Cache plugin is enabled
        if(JPluginHelper::isEnabled('system', 'cache'))
        {
            $cache = JFactory::getCache();
            $cache->remove($cache->makeId(), 'page');
        }

        // Bot-Trap
        // Further informations: http://www.bot-trap.de
        // File has to be named page.restrictor.php and be saved in plugins/system/easycalccheckplus/bottrap/
        if($this->params->get('bottrap'))
        {
            // Set correct path to the helper PHP file
            if($this->_app->isAdmin())
            {
                $path = '../plugins/system/easycalccheckplus/bottrap/';
            }
            else
            {
                $path = 'plugins/system/easycalccheckplus/bottrap/';
            }

            // File exists, then set the white / black lists and do the global check
            if(file_exists($path.'page.restrictor.php'))
            {
                if($this->params->get('btWhitelistIP'))
                {
                    $btWhitelistIP = str_replace(',', '|', $this->params->get('btWhitelistIP'));
                    define('PRES_WHITELIST_IP', $btWhitelistIP);
                }

                if($this->params->get('btWhitelistIPRange'))
                {
                    $btWhitelistIPRange = str_replace(',', '|', $this->params->get('btWhitelistIPRange'));
                    define('PRES_WHITELIST_IPR', $btWhitelistIPRange);
                }

                if($this->params->get('btWhitelistUA'))
                {
                    $btWhitelistUA = str_replace(',', '|', $this->params->get('btWhitelistUA'));
                    define('PRES_WHITELIST_UA', $btWhitelistUA);
                }

                if($this->params->get('btBlacklistIP'))
                {
                    $btBlacklistIP = str_replace(',', '|', $this->params->get('btBlacklistIP'));
                    define('PRES_BLACKLIST_IP', $btBlacklistIP);
                }

                if($this->params->get('btBlacklistIPRange'))
                {
                    $btBlacklistIPRange = str_replace(',', '|', $this->params->get('btBlacklistIPRange'));
                    define('PRES_BLACKLIST_IPR', $btBlacklistIPRange);
                }

                if($this->params->get('btBlacklistUA'))
                {
                    $btBlacklistUA = str_replace(',', '|', $this->params->get('btBlacklistUA'));
                    define('PRES_BLACKLIST_UA', $btBlacklistUA);
                }

                include_once($path.'page.restrictor.php');
            }
            else
            {
                JError::raiseWarning(100, JText::_('PLG_ECC_ERRORBOTTRAP'));
            }
        }

        // Based on Marco's SQL Injection Plugin
        // Further informations: http://www.mmleoni.net/sql-iniection-lfi-protection-plugin-for-joomla
        if($this->params->get('sqlinjection-lfi'))
        {
            foreach(explode(',', 'GET,POST,REQUEST') as $name_space)
            {
                if($name_space == 'GET')
                {
                    $name_space = $this->_request->getArray($_GET);
                }
                elseif($name_space == 'POST')
                {
                    $name_space = $this->_request->getArray($_POST);
                }
                elseif($name_space == 'REQUEST')
                {
                    $name_space = $this->_request->getArray($_REQUEST);
                }

                if(!empty($name_space))
                {
                    foreach($name_space as $k => $v)
                    {
                        if(is_numeric($v) OR is_array($v))
                        {
                            continue;
                        }

                        $a = preg_replace('@/\*.*?\*/@s', ' ', $v);

                        if(preg_match('@UNION(?:\s+ALL)?\s+SELECT@i', $a))
                        {
                            JError::raiseError(500, JText::_('PLG_ECC_INTERNALERRORSQLINJECTION'));
                            return;
                        }

                        $p_dbprefix = $this->_app->getCfg('dbprefix');
                        $ta = array('/(\s+|\.|,)`?(#__)/', '/(\s+|\.|,)`?(jos_)/i', "/(\s+|\.|,)`?({$p_dbprefix}_)/i");

                        foreach($ta as $t)
                        {
                            if(preg_match($t, $v))
                            {
                                JError::raiseError(500, JText::_('PLG_ECC_INTERNALERRORSQLINJECTION'));
                                return;
                            }
                        }

                        if(in_array($k, array('controller', 'view', 'model', 'template')))
                        {
                            $recurse = str_repeat('\.\.\/', 2);

                            while(preg_match('@'.$recurse.'@', $v))
                            {
                                JError::raiseError(500, JText::_('PLG_ECC_INTERNALERRORSQLINJECTION'));
                                return;
                            }
                        }

                        unset($v);
                    }
                }
            }
        }

        // Backend protection
        if($this->params->get('backendprotection'))
        {
            if($this->_app->isAdmin())
            {
                if($this->_user->guest)
                {
                    $token = $this->params->get('token');
                    $request_token = $this->_request->get('token', 0, 'RAW');
                    $tokensession = $this->_session->get('token', null, 'easycalccheck');

                    if(!isset($tokensession))
                    {
                        $this->_session->set('token', 0, 'easycalccheck');
                    }

                    if(utf8_encode($request_token) == $token) // Conversion to UTF8 (german umlauts)
                    {
                        $this->_session->set('token', 1, 'easycalccheck');
                    }
                    elseif(utf8_encode($request_token) != $token)
                    {
                        if(empty($tokensession))
                        {
                            $url = $this->params->get('urlfalsetoken');

                            if(empty($url))
                            {
                                $url = JURI::root();
                            }

                            $this->_session->clear('token', 'easycalccheck');
                            $this->redirect($url);
                        }
                    }
                }
            }
        }
    }

    // Detect whether the plugin routine has to be loaded and call the checks
    function onAfterRoute()
    {
        // Check whether ECC has to be loaded
        $option = $this->_request->get('option', '', 'WORD');
        $view = $this->_request->get('view', '', 'WORD');
        $task = $this->_request->get('task', '', 'CMD');
        $func = $this->_request->get('func', '', 'WORD');
        $layout = $this->_request->get('layout', '', 'WORD');

        $this->loadEcc($option, $task, $view, $func, $layout);

        // If the custom call is activated, then the input has to be checked here to intercept the process handling of the extension
        if($this->params->get('custom_call') AND $this->loadEccCustom())
        {
            // Load error notice if needed
            $this->raiseErrorWarning($option, true);

            // Determine whether the check was already loaded and the data have to be validated
            $check_custom_call = (array)$this->_session->get('check_custom_call', null, 'easycalccheck');
            $this->_session->clear('check_custom_call', 'easycalccheck');

            if(!empty($check_custom_call))
            {
                // Get all request variables for the check
                $request = $this->_request->getArray($_REQUEST);

                // Go through all request variable until one hit to check whether the form was submitted by the user
                foreach($check_custom_call as $request_variable)
                {
                    if(array_key_exists($request_variable, $request))
                    {
                        // Clean cache
                        $this->cleanCache();

                        // Save entered values in session for autofill
                        if($this->params->get('autofill_values'))
                        {
                            $this->saveData();
                        }

                        // Do the checks to protect the custom form
                        if(!$this->performChecks())
                        {
                            // Set error session variable for the message output
                            $this->_session->set('error_output', 'check_failed_custom', 'easycalccheck');
                            $this->redirect($this->_redirect_url);
                        }

                        break;
                    }
                }
            }
        }

        // Clean cache of component if ECC+ has to be loaded
        if($this->_load_ecc_check == true OR $this->_load_ecc == true)
        {
            $this->cleanCache();
        }

        if($this->_load_ecc_check == true)
        {
            // Save entered values in session for autofill
            if($this->params->get('autofill_values'))
            {
                $this->saveData();
            }

            // Call checks for forms
            $this->callChecks($option, $task);
        }

        if($this->_load_ecc == true)
        {
            // Raise error warning if needed - do the check
            $this->raiseErrorWarning($option);

            // Write head data
            $head = array();
            $head[] = '<style type="text/css">#easycalccheckplus {margin: 8px 0 !important; padding: 2px !important;}</style>';

            if($this->params->get('flexicontactplus') AND $option == "com_flexicontactplus")
            {
                $head[] = '<style type="text/css">#easycalccheckplus label {width: auto;}</style>';
            }

            if($this->params->get('poweredby'))
            {
                $head[] = '<style type="text/css">.protectedby {font-size: x-small !important; text-align: right !important;}</style>';
            }

            if($this->params->get('type_hidden'))
            {
                $this->_session->set('hidden_class', $this->random(), 'easycalccheck');
                $head[] = '<style type="text/css">.'.$this->_session->get('hidden_class', null, 'easycalccheck').' {display: none !important;}</style>';

                if($this->params->get('foxcontact') AND $option == "com_foxcontact")
                {
                    $head[] = '<style type="text/css">label.'.$this->_session->get('hidden_class', null, 'easycalccheck').' {margin: 0 !important; padding: 0 !important;}</style>';
                }
            }

            if($this->params->get('kunena') AND $this->params->get('recaptcha') AND $option == "com_kunena")
            {
                $head[] = '<style type="text/css">div#recaptcha_area{margin: auto !important;}</style>';
            }

            if($this->params->get('recaptcha_theme'))
            {
                if($this->params->get('recaptcha_theme') == 1)
                {
                    $theme = 'white';
                }
                elseif($this->params->get('recaptcha_theme') == 2)
                {
                    $theme = 'blackglass';
                }
                elseif($this->params->get('recaptcha_theme') == 3)
                {
                    $theme = 'clean';
                }

                $head[] = '<script type="text/javascript">var RecaptchaOptions = { theme : "'.$theme.'" };</script>';
            }

            $head = "\n".implode("\n", $head)."\n";
            $document = JFactory::getDocument();

            if($document->getType() == 'html')
            {
                $document->addCustomTag($head);
            }
        }
    }

    // Manipulate the output in the function onAfterRender, Set all form checks
    public function onAfterRender()
    {
        // Custom call check - call it here because we need access to the output - since 2.5-8
        if($this->_load_ecc == false)
        {
            if($this->params->get('custom_call') AND $this->loadEccCustom())
            {
                $this->customCall();
            }
        }

        if($this->_load_ecc == true)
        {
            $option = $this->_request->get('option', '', 'WORD');

            // Google Translator Fix
            $this->loadLanguage('', JPATH_ADMINISTRATOR);

            // Read in content of the output
            $body = JResponse::getBody();

            // Get form of extension
            preg_match('@'.$this->_extension_info[1].'@isU', $body, $match_extension);

            // Form was not found, the template probably uses overrides, try it with the detection of the task or set error message for debug mode
            if(empty($match_extension))
            {
                // Try to find the form by the task if provided
                if(!empty($this->_extension_info[4]))
                {
                    // Get all forms on the loaded page and find the correct form by the task value
                    preg_match_all('@<form[^>]*>.*</form>@isU', $body, $match_extension_forms);

                    if(!empty($match_extension_forms))
                    {
                        foreach($match_extension_forms[0] as $match_extension_form)
                        {
                            if(preg_match('@<form[^>]*>.*value=["|\']'.$this->_extension_info[4].'["|\'].*</form>@isU', $match_extension_form, $match_extension))
                            {
                                break;
                            }
                        }
                    }
                }

                if(empty($match_extension))
                {
                    JError::raiseWarning(100, JText::_('PLG_ECC_WARNING_FORMNOTFOUND'));
                }
            }

            // Fill in form input values if the check failed previously (_warning_shown is set)
            if($this->params->get('autofill_values') AND !empty($this->_warning_shown))
            {
                $this->fillForm($body, $match_extension);
            }

            // Hidden field
            if($this->params->get('type_hidden') AND !empty($match_extension))
            {
                $pattern_search_string = '@'.$this->_extension_info[2].'@isU';
                preg_match_all($pattern_search_string, $match_extension[0], $matches);

                if(empty($matches[0]))
                {
                    JError::raiseWarning(100, JText::_('PLG_ECC_WARNING_NOHIDDENFIELD'));
                }
                else
                {
                    $count = mt_rand(0, count($matches[0]) - 1);
                    $search_string_hidden = $matches[0][$count];

                    // Generate random variable
                    $this->_session->set('hidden_field', $this->random(), 'easycalccheck');
                    $this->_session->set('hidden_field_label', $this->random(), 'easycalccheck');

                    // Line width for obfuscation
                    $input_size = 30;

                    $add_string = '<label class="'.$this->_session->get('hidden_class', null, 'easycalccheck').'" for="'.$this->_session->get('hidden_field_label', null, 'easycalccheck').'"></label><input type="text" id="'.$this->_session->get('hidden_field_label', null, 'easycalccheck').'" name="'.$this->_session->get('hidden_field', null, 'easycalccheck').'" size="'.$input_size.'" class="inputbox '.$this->_session->get('hidden_class', null, 'easycalccheck').'" />';

                    // Yootheme Fix - Put the hidden field in an own div container to avoid displacement of other fields
                    if(preg_match('@<div[^>]*>\s*'.$search_string_hidden.'@isU', $match_extension[0], $matches_div))
                    {
                        $search_string_hidden = $matches_div[0];
                    }

                    if(isset($search_string_hidden))
                    {
                        $body = str_replace($search_string_hidden, $add_string.$search_string_hidden, $body);
                    }
                }
            }

            // Calc check
            if(($this->params->get('type_calc') OR $this->params->get('recaptcha') OR $this->params->get('question')) AND !empty($match_extension))
            {
                // Without overrides
                $pattern_output = '@'.$this->_extension_info[3].'@isU';

                if(preg_match($pattern_output, $match_extension[0], $matches))
                {
                    $search_string_output = $matches[0];
                }
                else
                {
                    // Alternative search string from settings
                    $string_alternative = $this->params->get('string_alternative');

                    if(!empty($string_alternative))
                    {
                        $pattern = '@'.$string_alternative.'@isU';

                        if(preg_match($pattern, $match_extension[0], $matches))
                        {
                            $search_string_output = $matches[0];
                        }
                    }

                    // With overrides
                    if(!isset($search_string_output))
                    {
                        // Artisteer Template
                        if(preg_match('@<span class=".*-button-wrapper">@isU', $match_extension[0], $matches))
                        {
                            $search_string_output = $matches[0];
                        }

                        // Rockettheme Template
                        if(preg_match('@<div class="readon">@isU', $match_extension[0], $matches))
                        {
                            $search_string_output = $matches[0];
                        }

                        // String was still not found - take the submit attribute
                        if(!isset($search_string_output))
                        {
                            if(preg_match('@<[^>]*type="submit".*>@isU', $match_extension[0], $matches))
                            {
                                $search_string_output = $matches[0];
                            }
                        }
                    }
                }

                $add_string = '<!-- EasyCalcCheck Plus - Kubik-Rubik Joomla! Extensions --><div id="easycalccheckplus">';

                if($this->params->get('type_calc'))
                {
                    $this->_session->set('spamcheck', $this->random(), 'easycalccheck');
                    $this->_session->set('rot13', mt_rand(0, 1), 'easycalccheck');

                    // Determine operator
                    if($this->params->get('operator') == 2)
                    {
                        $tcalc = mt_rand(1, 2);
                    }
                    elseif($this->params->get('operator') == 1)
                    {
                        $tcalc = 2;
                    }
                    else
                    {
                        $tcalc = 1;
                    }

                    // Determine max. operand
                    $max_value = $this->params->get('max_value', 20);

                    if(($this->params->get('negativ') == 0) AND ($tcalc == 2))
                    {
                        $spam_check_1 = mt_rand($max_value / 2, $max_value);
                        $spam_check_2 = mt_rand(1, $max_value / 2);

                        if($this->params->get('operand') == 3)
                        {
                            $spam_check_3 = mt_rand(0, $spam_check_1 - $spam_check_2);
                        }
                    }
                    else
                    {
                        $spam_check_1 = mt_rand(1, $max_value);
                        $spam_check_2 = mt_rand(1, $max_value);

                        if($this->params->get('operand') == 3)
                        {
                            $spam_check_3 = mt_rand(0, $max_value);
                        }
                    }

                    if($tcalc == 1) // Addition
                    {
                        if($this->_session->get('rot13', null, 'easycalccheck') == 1) // ROT13 coding
                        {
                            if($this->params->get('operand') == 2)
                            {
                                $this->_session->set('spamcheckresult', str_rot13(base64_encode($spam_check_1 + $spam_check_2)), 'easycalccheck');
                            }
                            elseif($this->params->get('operand') == 3)
                            {
                                $this->_session->set('spamcheckresult', str_rot13(base64_encode($spam_check_1 + $spam_check_2 + $spam_check_3)), 'easycalccheck');
                            }
                        }
                        else
                        {
                            if($this->params->get('operand') == 2)
                            {
                                $this->_session->set('spamcheckresult', base64_encode($spam_check_1 + $spam_check_2), 'easycalccheck');
                            }
                            elseif($this->params->get('operand') == 3)
                            {
                                $this->_session->set('spamcheckresult', base64_encode($spam_check_1 + $spam_check_2 + $spam_check_3), 'easycalccheck');
                            }
                        }
                    }
                    elseif($tcalc == 2) // Subtraction
                    {
                        if($this->_session->get('rot13', null, 'easycalccheck') == 1)
                        {
                            if($this->params->get('operand') == 2)
                            {
                                $this->_session->set('spamcheckresult', str_rot13(base64_encode($spam_check_1 - $spam_check_2)), 'easycalccheck');
                            }
                            elseif($this->params->get('operand') == 3)
                            {
                                $this->_session->set('spamcheckresult', str_rot13(base64_encode($spam_check_1 - $spam_check_2 - $spam_check_3)), 'easycalccheck');
                            }
                        }
                        else
                        {
                            if($this->params->get('operand') == 2)
                            {
                                $this->_session->set('spamcheckresult', base64_encode($spam_check_1 - $spam_check_2), 'easycalccheck');
                            }
                            elseif($this->params->get('operand') == 3)
                            {
                                $this->_session->set('spamcheckresult', base64_encode($spam_check_1 - $spam_check_2 - $spam_check_3), 'easycalccheck');
                            }
                        }
                    }

                    $add_string .= '<div><label for="'.$this->_session->get('spamcheck', null, 'easycalccheck').'">'.JText::_('PLG_ECC_SPAMCHECK');

                    if($tcalc == 1)
                    {
                        if($this->params->get('converttostring'))
                        {
                            if($this->params->get('operand') == 2)
                            {
                                $add_string .= $this->converttostring($spam_check_1).' '.JText::_('PLG_ECC_PLUS').' '.$this->converttostring($spam_check_2).' '.JText::_('PLG_ECC_EQUALS').' ';
                            }
                            elseif($this->params->get('operand') == 3)
                            {
                                $add_string .= $this->converttostring($spam_check_1).' '.JText::_('PLG_ECC_PLUS').' '.$this->converttostring($spam_check_2).' '.JText::_('PLG_ECC_PLUS').' '.$this->converttostring($spam_check_3).' '.JText::_('PLG_ECC_EQUALS').' ';
                            }
                        }
                        else
                        {
                            if($this->params->get('operand') == 2)
                            {
                                $add_string .= $spam_check_1.' '.JText::_('PLG_ECC_PLUS').' '.$spam_check_2.' '.JText::_('PLG_ECC_EQUALS').' ';
                            }
                            elseif($this->params->get('operand') == 3)
                            {
                                $add_string .= $spam_check_1.' '.JText::_('PLG_ECC_PLUS').' '.$spam_check_2.' '.JText::_('PLG_ECC_PLUS').' '.$spam_check_3.' '.JText::_('PLG_ECC_EQUALS').' ';
                            }
                        }
                    }
                    elseif($tcalc == 2)
                    {
                        if($this->params->get('converttostring'))
                        {
                            if($this->params->get('operand') == 2)
                            {
                                $add_string .= $this->converttostring($spam_check_1).' '.JText::_('PLG_ECC_MINUS').' '.$this->converttostring($spam_check_2).' '.JText::_('PLG_ECC_EQUALS').' ';
                            }
                            elseif($this->params->get('operand') == 3)
                            {
                                $add_string .= $this->converttostring($spam_check_1).' '.JText::_('PLG_ECC_MINUS').' '.$this->converttostring($spam_check_2).' '.JText::_('PLG_ECC_MINUS').' '.$this->converttostring($spam_check_3).' '.JText::_('PLG_ECC_EQUALS').' ';
                            }
                        }
                        else
                        {
                            if($this->params->get('operand') == 2)
                            {
                                $add_string .= $spam_check_1.' '.JText::_('PLG_ECC_MINUS').' '.$spam_check_2.' '.JText::_('PLG_ECC_EQUALS').' ';
                            }
                            elseif($this->params->get('operand') == 3)
                            {
                                $add_string .= $spam_check_1.' '.JText::_('PLG_ECC_MINUS').' '.$spam_check_2.' '.JText::_('PLG_ECC_MINUS').' '.$spam_check_3.' '.JText::_('PLG_ECC_EQUALS').' ';
                            }
                        }
                    }

                    $add_string .= '</label>';
                    $add_string .= '<input type="text" name="'.$this->_session->get('spamcheck', null, 'easycalccheck').'" id="'.$this->_session->get('spamcheck', null, 'easycalccheck').'" size="3" class="inputbox '.$this->random().' validate-numeric required" value="" required="required" />';
                    $add_string .= '</div>';

                    // Show warnings
                    if($this->params->get('warn_ref') AND !$this->params->get('autofill_values'))
                    {
                        $add_string .= '<p><img src="'.JURI::root().'plugins/system/easycalccheckplus/easycalccheckplus/warning.png" alt="'.JText::_('PLG_ECC_WARNING').'" /> ';
                        $add_string .= '<strong>'.JText::_('PLG_ECC_WARNING').'</strong><br /><small>'.JText::_('PLG_ECC_WARNINGDESC').'</small>';

                        if($this->params->get('converttostring'))
                        {
                            $add_string .= '<br /><small>'.JText::_('PLG_ECC_CONVERTWARNING').'</small><br />';
                        }

                        $add_string .= '</p>';
                    }
                    elseif($this->params->get('converttostring'))
                    {
                        $add_string .= '<p><small>'.JText::_('PLG_ECC_CONVERTWARNING').'</small></p>';
                    }
                }

                // ReCaptcha
                if($this->params->get('recaptcha') AND $this->params->get('recaptcha_publickey') AND $this->params->get('recaptcha_privatekey'))
                {
                    require_once(dirname(__FILE__).DS.'easycalccheckplus'.DS.'recaptchalib.php');
                    $publickey = $this->params->get('recaptcha_publickey');

                    $add_string .= recaptcha_get_html($publickey).'<br />';
                }

                // Own Question
                if($this->params->get('question') AND $this->params->get('question_q') AND $this->params->get('question_a'))
                {
                    $this->_session->set('question', $this->random(), 'easycalccheck');

                    $size = strlen($this->params->get('question_a')) + mt_rand(0, 2);

                    $add_string .= '<p>'.$this->params->get('question_q').' <input type="text" name="'.$this->_session->get('question', null, 'easycalccheck').'" id="'.$this->_session->get('question', null, 'easycalccheck').'" size="'.$size.'" class="inputbox '.$this->random().'" value="" /> *</p>';
                }

                if($this->params->get('poweredby') == 1)
                {
                    $add_string .= '<div class="protectedby"><a href="http://joomla-extensions.kubik-rubik.de/" title="EasyCalcCheck Plus for Joomla! - Kubik-Rubik Joomla! Extensions" target="_blank">'.JText::_('PLG_ECC_PROTECTEDBY').'</a></div>';
                }

                $add_string .= '</div>';

                if(isset($search_string_output))
                {
                    if(empty($this->_custom_call))
                    {
                        $body = str_replace($search_string_output, $add_string.$search_string_output, $body);
                    }
                    else
                    {
                        $body = str_replace($search_string_output, $add_string, $body);
                    }
                }
            }

            // Encode fields - since 2.5-8 in all forms where ECC+ is loaded
            if($this->params->get('encode') AND empty($this->_debug_plugin))
            {
                $pattern_encode = '@<[^>]+(name=("|\')([^>]*)("|\'))[^>]*>@isU';
                preg_match_all($pattern_encode, $match_extension[0], $matches_encode);

                $match_encode_replacement = array();

                // Add global exceptions - this fields should not be renamed to avoid execution errors
                $replace_not = array('option', 'view', 'task', 'func', 'layout', 'controller');

                // Add exceptions from extension if provided
                if(!empty($this->_extension_info[5]))
                {
                    $replace_not = array_merge($replace_not, array_map('trim', explode(',', $this->_extension_info[5])));
                }

                foreach($matches_encode[3] as $key => $match)
                {
                    if(!in_array($match, $replace_not))
                    {
                        $random = $this->random();
                        $this->_session->set($match, $random, 'easycalccheck_encode');
                        $match_encode_replacement[$key] = str_replace($matches_encode[1][$key], 'name="'.$random.'"', $matches_encode[0][$key]);
                    }
                    else
                    {
                        unset($matches_encode[0][$key]);
                    }
                }

                if(!empty($match_encode_replacement))
                {
                    $body = str_replace($matches_encode[0], $match_encode_replacement, $body);
                }
            }

            // Set body content after all modifications have been applied
            JResponse::setBody($body);

            // Time Lock
            if($this->params->get('type_time'))
            {
                $this->_session->set('time', time(), 'easycalccheck');
            }

            // Get IP Address
            $this->_session->set('ip', getenv('REMOTE_ADDR'), 'easycalccheck');

            // Set session variable for error output - Phoca Guestbook / Easybook Reloaded
            if($option == 'com_phocaguestbook')
            {
                $this->_session->set('phocaguestbook', 0, 'easycalccheck');
            }
            elseif($option == 'com_easybookreloaded')
            {
                $this->_session->set('easybookreloaded', 0, 'easycalccheck');
            }

            // Set redirect url
            $this->_session->set('redirect_url', JFactory::getURI()->toString(), 'easycalccheck');
        }
    }

    // Check the result of the calc check submitted by the contact form
    public function onValidateContact($contact, $post)
    {
        if($this->_load_ecc_check == true)
        {
            $option = $this->_request->get('option', '', 'WORD');

            if($this->params->get('contact') AND $option == 'com_contact')
            {
                if(!$this->performChecks())
                {
                    // Set error session variable for the message output
                    $this->_session->set('error_output', 'check_failed', 'easycalccheck');
                    $this->redirect($this->_redirect_url);
                }
            }

            return true;
        }
    }

    // Check the result of the checks submittet by the registration form
    public function onUserBeforeSave($user, $isnew, $new)
    {
        if($this->_load_ecc_check == true)
        {
            if(!empty($isnew))
            {
                $option = $this->_request->get('option', '', 'WORD');

                if(($this->params->get('user_reg') AND $option == 'com_users') OR ($this->params->get('communitybuilder') AND $option == 'com_comprofiler'))
                {
                    if(!$this->performChecks())
                    {
                        // Set error session variable for the message output
                        $this->_session->set('error_output', 'check_failed', 'easycalccheck');
                        $this->redirect($this->_redirect_url);
                    }
                }
            }
        }
    }

    // Increase the failed_login_attempts counter if the login was not successful
    public function onUserLoginFailure()
    {
        $failed_login_attempts = $this->_session->get('failed_login_attempts', null, 'easycalccheck');
        $this->_session->set('failed_login_attempts', $failed_login_attempts + 1, 'easycalccheck');
    }

    // Successful login, clear sessions variable
    public function onUserLogin()
    {
        $this->_session->clear('failed_login_attempts', 'easycalccheck');
    }

    // Do the checks, great Joomla! plugin!
    private function performChecks()
    {
        $request = $this->_request->getArray($_REQUEST);

        // Calc check
        if($this->params->get('type_calc'))
        {
            if($this->_session->get('rot13', null, 'easycalccheck') == 1)
            {
                $spamcheckresult = base64_decode(str_rot13($this->_session->get('spamcheckresult', null, 'easycalccheck')));
            }
            else
            {
                $spamcheckresult = base64_decode($this->_session->get('spamcheckresult', null, 'easycalccheck'));
            }

            $spamcheck = $request[$this->_session->get('spamcheck', null, 'easycalccheck')];

            $this->_session->clear('rot13', 'easycalccheck');
            $this->_session->clear('spamcheck', 'easycalccheck');
            $this->_session->clear('spamcheckresult', 'easycalccheck');

            if(!is_numeric($spamcheckresult) || $spamcheckresult != $spamcheck)
            {
                return false; // Failed
            }
        }

        // Hidden field
        if($this->params->get('type_hidden'))
        {
            $hidden_field = $request[$this->_session->get('hidden_field', null, 'easycalccheck')];
            $this->_session->clear('hidden_field', 'easycalccheck');

            if(!empty($hidden_field))
            {
                return false; // Hidden field was filled out - failed
            }
        }

        // Time lock
        if($this->params->get('type_time'))
        {
            $time = $this->_session->get('time', null, 'easycalccheck');
            $this->_session->clear('time', 'easycalccheck');

            if(time() - $this->params->get('type_time_sec') <= $time)
            {
                return false; // Submitted too fast - failed
            }
        }

        // Own Question
        // Conversion to lower case
        if($this->params->get('question'))
        {
            $answer = strtolower($request[$this->_session->get('question', null, 'easycalccheck')]);
            $this->_session->clear('question', 'easycalccheck');

            if($answer != strtolower($this->params->get('question_a')))
            {
                return false; // Question wasn't answered - failed
            }
        }

        // StopForumSpam - Check the IP Address
        // Further informations: http://www.stopforumspam.com
        if($this->params->get('stopforumspam'))
        {
            $url = 'http://www.stopforumspam.com/api?ip='.$this->_session->get('ip', null, 'easycalccheck');

            // Function test - Comment out to test - Important: Enter a active Spam-IP
            // $ip = '88.180.52.46';
            // $url = 'http://www.stopforumspam.com/api?ip='.$ip;

            $response = false;
            $is_spam = false;

            // TODO - use Joomla! API for request
            if(function_exists('curl_init'))
            {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POST, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);
                curl_close($ch);
            }

            if($response)
            {
                preg_match('#<appears>(.*)</appears>#', $response, $out);
                $is_spam = $out[1];
            }
            else
            {
                $response = @ fopen($url, 'r');

                if($response)
                {
                    while(!feof($response))
                    {
                        $line = fgets($response, 1024);

                        if(preg_match('#<appears>(.*)</appears>#', $line, $out))
                        {
                            $is_spam = $out[1];
                            break;
                        }
                    }
                    fclose($response);
                }
            }

            if($is_spam == 'yes' AND $response == true)
            {
                return false; // Spam-IP - failed
            }
        }

        // Honeypot Project
        // Further informations: http://www.projecthoneypot.org/home.php
        // BL ACCESS KEY - http://www.projecthoneypot.org/httpbl_configure.php
        if($this->params->get('honeypot'))
        {
            require_once(dirname(__FILE__).DS.'easycalccheckplus'.DS.'honeypot.php');
            $http_blKey = $this->params->get('honeypot_key');

            if($http_blKey)
            {
                $http_bl = new http_bl($http_blKey);
                $result = $http_bl->query($this->_session->get('ip', null, 'easycalccheck'));

                // Function test - Comment out to test - Important: Enter an active Spam-IP
                // $ip = '117.21.224.251';
                // $result = $http_bl->query($ip);

                if($result == 2)
                {
                    return false;
                }
            }
        }

        // Akismet
        // Further informations: http://akismet.com/
        if($this->params->get('akismet'))
        {
            require_once(dirname(__FILE__).DS.'easycalccheckplus'.DS.'akismet.php');
            $akismet_key = $this->params->get('akismet_key');

            if($akismet_key)
            {
                $akismet_url = JURI::getInstance()->toString();

                $name = '';
                $email = '';
                $url = '';
                $comment = '';

                if($request['option'] == 'com_contact')
                {
                    $name = $request['jform']['contact_name'];
                    $email = $request['jform']['contact_email'];
                    $comment = $request['jform']['contact_message'];
                }
                elseif($request['option'] == 'com_users')
                {
                    $name = $request['jform']['name'];
                    $email = $request['jform']['email1'];

                    if(isset($request['jform']['email']))
                    {
                        $email = $request['jform']['email'];
                    }
                }
                elseif($request['option'] == 'com_comprofiler')
                {
                    $name = $request['name'];
                    $email = $request['email'];

                    if(isset($request['checkusername']))
                    {
                        $name = $request['checkusername'];
                    }

                    if(isset($request['checkemail']))
                    {
                        $email = $request['checkemail'];
                    }
                }
                elseif($request['option'] == 'com_easybookreloaded')
                {
                    $name = $request['gbname'];
                    $email = $request['gbmail'];
                    $comment = $request['gbtext'];

                    if(isset($request['gbpage']))
                    {
                        $url = $request['gbpage'];
                    }
                }
                elseif($request['option'] == 'com_phocaguestbook')
                {
                    $name = $request['pgusername'];
                    $email = $request['email'];
                    $comment = $request['pgbcontent'];
                }
                elseif($request['option'] == 'com_dfcontact')
                {
                    $name = $request['name'];
                    $email = $request['email'];
                    $comment = $request['message'];
                }
                elseif($request['option'] == 'com_flexicontact' OR $request['option'] == 'com_flexicontactplus')
                {
                    $name = $request['from_name'];
                    $email = $request['from_email'];
                    $comment = $request['area_data'];
                }
                elseif($request['option'] == 'com_alfcontact')
                {
                    $name = $request['name'];
                    $email = $request['email'];
                    $comment = $request['message'];
                }
                elseif($request['option'] == 'com_community')
                {
                    $name = $request['usernamepass'];
                    $email = $request['emailpass'];
                }
                elseif($request['option'] == 'com_virtuemart')
                {
                    $name = $request['name'];
                    $email = $request['email'];
                    $comment = $request['comment'];
                }
                elseif($request['option'] == 'com_aicontactsafe')
                {
                    $name = $request['aics_name'];
                    $email = $request['aics_email'];
                    $comment = $request['aics_message'];
                }

                $akismet = new Akismet($akismet_url, $akismet_key);
                $akismet->setCommentAuthor($name);
                $akismet->setCommentAuthorEmail($email);
                $akismet->setCommentAuthorURL($url);
                $akismet->setCommentContent($comment);

                if($akismet->isCommentSpam())
                {
                    return false;
                }
            }
        }

        // ReCaptcha
        // Further informations: http://www.google.com/recaptcha
        if($this->params->get('recaptcha') AND $this->params->get('recaptcha_publickey') AND $this->params->get('recaptcha_privatekey'))
        {
            require_once(dirname(__FILE__).DS.'easycalccheckplus'.DS.'recaptchalib.php');
            $privatekey = $this->params->get('recaptcha_privatekey');

            $resp = recaptcha_check_answer($privatekey, $this->_session->get('ip', null, 'easycalccheck'), $request['recaptcha_challenge_field'], $request['recaptcha_response_field']);

            if(!$resp->is_valid)
            {
                return false;
            }
        }

        // Botscout - Check the IP Address
        // Further informations: http://botscout.com/
        if($this->params->get('botscout') AND $this->params->get('botscout_key'))
        {
            $url = 'http://botscout.com/test/?ip='.$this->_session->get('ip', null, 'easycalccheck').'&key='.$this->params->get('botscout_key');

            // Function test - Comment out to test - Important: Enter a active Spam-IP
            // $ip = '87.103.128.199';
            // $url = 'http://botscout.com/test/?ip='.$ip.'&key='.$this->params->get('botscout_key');

            $response = false;
            $is_spam = false;

            // TODO - use Joomla! API for request
            if(function_exists('curl_init'))
            {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POST, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);
                curl_close($ch);
            }

            if($response)
            {
                $is_spam = substr($response, 0, 1);
            }
            else
            {
                $response = @ fopen($url, 'r');

                if($response)
                {
                    while(!feof($response))
                    {
                        $line = fgets($response, 1024);

                        $is_spam = substr($line, 0, 1);
                    }
                    fclose($response);
                }
            }

            if($is_spam == 'Y' AND $response == true)
            {
                // Spam-IP - failed
                return false;
            }
        }

        // Mollom
        // Further informations: http://mollom.com/
        if($this->params->get('mollom') AND $this->params->get('mollom_publickey') AND $this->params->get('mollom_privatekey'))
        {
            require_once(dirname(__FILE__).DS.'easycalccheckplus'.DS.'mollom.php');

            Mollom::setPublicKey($this->params->get('mollom_publickey'));
            Mollom::setPrivateKey($this->params->get('mollom_privatekey'));

            $servers = Mollom::getServerList();

            $name = '';
            $email = '';
            $url = '';
            $comment = '';

            if($request['option'] == 'com_contact')
            {
                $name = $request['jform']['contact_name'];
                $email = $request['jform']['contact_email'];
                $comment = $request['jform']['contact_message'];
            }
            elseif($request['option'] == 'com_users')
            {
                $name = $request['jform']['name'];
                $email = $request['jform']['email1'];

                if(isset($request['jform']['email']))
                {
                    $email = $request['jform']['email'];
                }
            }
            elseif($request['option'] == 'com_comprofiler')
            {
                $name = $request['name'];
                $email = $request['email'];

                if(isset($request['checkusername']))
                {
                    $name = $request['checkusername'];
                }

                if(isset($request['checkemail']))
                {
                    $email = $request['checkemail'];
                }
            }
            elseif($request['option'] == 'com_easybookreloaded')
            {
                $name = $request['gbname'];
                $email = $request['gbmail'];
                $comment = $request['gbtext'];

                if(isset($request['gbpage']))
                {
                    $url = $request['gbpage'];
                }
            }
            elseif($request['option'] == 'com_phocaguestbook')
            {
                $name = $request['pgusername'];
                $email = $request['email'];
                $comment = $request['pgbcontent'];
            }
            elseif($request['option'] == 'com_dfcontact')
            {
                $name = $request['name'];
                $email = $request['email'];
                $comment = $request['message'];
            }
            elseif($request['option'] == 'com_flexicontact' OR $request['option'] == 'com_flexicontactplus')
            {
                $name = $request['from_name'];
                $email = $request['from_email'];
                $comment = $request['area_data'];
            }
            elseif($request['option'] == 'com_alfcontact')
            {
                $name = $request['name'];
                $email = $request['email'];
                $comment = $request['message'];
            }
            elseif($request['option'] == 'com_community')
            {
                $name = $request['usernamepass'];
                $email = $request['emailpass'];
            }
            elseif($request['option'] == 'com_virtuemart')
            {
                $name = $request['name'];
                $email = $request['email'];
                $comment = $request['comment'];
            }
            elseif($request['option'] == 'com_aicontactsafe')
            {
                $name = $request['aics_name'];
                $email = $request['aics_email'];
                $comment = $request['aics_message'];
            }

            $feedback = Mollom::checkContent(null, null, $comment, $name, $url, $email);

            if($feedback['spam'] == 'spam')
            {
                return false;
            }
        }

        $this->_session->clear('ip', 'easycalccheck');
        $this->_session->clear('saved_data', 'easycalccheck');

        // Yeeeha, no spam detected!
        return true;
    }

    // Check whether ECC+ has to be loaded in normal call
    private function loadEcc($option, $task, $view, $func, $layout)
    {
        $user = JFactory::getUser();
        $app = JFactory::getApplication();

        if($app->isAdmin() OR ($this->params->get('onlyguests') AND !$user->guest))
        {
            $this->_load_ecc = false;
            $this->_load_ecc_check = false;
        }
        else
        {
            // Find out if ECC+ has to be loaded depending on the called component
            if($option == 'com_contact')
            {
                // Array -> (name, form, regex for hidden field, regex for output, task, request exception for encode option);
                $this->_extension_info = array('com_contact', '<form[^>]+id="contact-form".+</form>', '<label id="jform_contact.+>', '<button class="btn btn-primary validate" type="submit">', 'contact.submit');

                if($this->params->get('contact') AND $view == 'contact')
                {
                    $this->_load_ecc = true;
                }

                if($this->params->get('contact') AND $task == 'contact.submit')
                {
                    $this->_load_ecc_check = true;
                }
            }
            elseif($option == 'com_users')
            {
                if($layout != 'confirm' AND $layout != 'complete')
                {
                    if($view == 'registration')
                    {
                        $this->_extension_info = array('com_users', '<form[^>]+id="member-registration".+</form>', '<label id="jform.+>', '<button type="submit" class="validate">', 'registration.register');
                    }
                    elseif($view == 'reset' OR $view == 'remind')
                    {
                        $this->_extension_info = array('com_users', '<form[^>]+id="user-registration".+</form>', '<label id="jform_email-lbl"', '<button type="submit">', 'registration.register');
                    }
                    elseif($view == 'login' OR $view == '')
                    {
                        $this->_extension_info = array('com_users', '<form[^>]+task=user.login.+</form>', '<label id=".+"', '<button type="submit" class=".*">', 'registration.register');
                    }

                    if($this->params->get('user_reg') AND ($view == 'registration' OR $view == 'reset' OR $view == 'remind'))
                    {
                        $this->_load_ecc = true;
                    }
                    elseif($this->params->get('user_reg') AND ($task == 'registration.register' OR $task == 'reset.request' OR $task == 'remind.remind'))
                    {
                        $this->_load_ecc_check = true;
                    }

                    if($this->params->get('user_login') AND ($view == 'login' OR $view == '' ) AND ($task == ''))
                    {
                        $this->_load_ecc = true;
                        $this->_session->set('user_login', 1, 'easycalccheck');
                    }
                    elseif($this->params->get('user_login') AND ($task == 'user.login'))
                    {
                        $user_login_check = $this->_session->get('user_login', null, 'easycalccheck');

                        if(!empty($user_login_check))
                        {
                            $this->_load_ecc_check = true;
                        }
                        else
                        {
                            $failed_login_attempts = $this->_session->get('failed_login_attempts', null, 'easycalccheck');

                            if(empty($failed_login_attempts))
                            {
                                $failed_login_attempts = 0;
                            }

                            if($failed_login_attempts >= $this->params->get('user_login_attempts'))
                            {
                                $this->redirect(JRoute::_('index.php?option=com_users&view=login&eccp_err=login_attempts', false));
                            }
                        }
                    }
                }
            }
            elseif($option == 'com_easybookreloaded') // Easybook Reloaded - tested with version 2.5-6 - compatibility with older versions
            {
                $this->_extension_info = array('com_easybookreloaded', '<form[^>]+name=("|\')gbookForm("|\').+</form>', '<input type=.+>', '<p id="easysubmit">', 'save', 'gbookForm, gbvote, gbtext');

                if($this->params->get('easybookreloaded') AND ($task == 'add'))
                {
                    $this->_load_ecc = true;
                }
                elseif($this->params->get('easybookreloaded') AND ($task == 'save'))
                {
                    $this->_load_ecc_check = true;
                }
            }
            elseif($option == 'com_phocaguestbook') // Phoca Guestbook - tested with version 2.0.6
            {
                $this->_extension_info = array('com_phocaguestbook', '<form[^>]+id="pgbSaveForm".+</form>', '<input type=.+>', '<input type="submit" name="save" value=".+" />', 'submit');

                if($this->params->get('phocaguestbook') AND $view == 'guestbook' AND $task != 'submit')
                {
                    $this->_load_ecc = true;
                }
                elseif($this->params->get('phocaguestbook') AND $task == 'submit')
                {
                    $this->_load_ecc_check = true;
                }
            }
            elseif($option == 'com_comprofiler') // Community Builder - tested with version 1.9
            {
                if($task == 'registers')
                {
                    $this->_extension_info = array('com_comprofiler', '<form[^>]+id="cbcheckedadminForm".+</form>', '<label for=".+>', '<input type="submit" value=".+" class="button" />', 'saveregisters');
                }
                elseif($task == 'lostpassword')
                {
                    $this->_extension_info = array('com_comprofiler', '<form[^>]+id="adminForm".+</form>', '<label for=".+>', '<input type="submit" class="button" id="cbsendnewuspass" value=".+" />', 'sendNewPass');
                }

                if($this->params->get('communitybuilder') AND ($task == 'registers' OR $task == 'lostpassword'))
                {
                    $this->_load_ecc = true;
                }
                elseif($this->params->get('communitybuilder') AND ($task == 'saveregisters' OR $task == 'sendNewPass'))
                {
                    $this->_load_ecc_check = true;
                }
            }
            elseif($option == 'com_dfcontact') // DFContact - tested with version 1.6.6
            {
                $this->_extension_info = array('com_dfcontact', '<form[^>]+id="dfContactForm".+</form>', '<label for="dfContactField.+>', '<input type="submit" value=".+" class="button" />');

                if($this->params->get('dfcontact') AND $view == 'dfcontact' AND empty($_REQUEST["submit"]))
                {
                    $this->_load_ecc = true;
                }
                elseif($this->params->get('dfcontact') AND $view == 'dfcontact' AND !empty($_REQUEST["submit"]))
                {
                    $this->_load_ecc_check = true;
                }
            }
            elseif($option == 'com_foxcontact') // FoxContact - tested with version 2.0.15
            {
                $this->_extension_info = array('com_foxcontact', '<form[^>]+id="FoxForm".+</form>', '<input class=.+>', '<input class="foxbutton" type="submit" style=".+" name=".+" value=".+"/>');

                $Itemid = $this->_request->get('Itemid', '', 'CMD');

                if($this->params->get('foxcontact') AND $view == 'foxcontact' AND !isset($_REQUEST['cid_'.$Itemid]))
                {
                    $this->_load_ecc = true;
                }
                elseif($this->params->get('foxcontact') AND $view == 'foxcontact' AND isset($_REQUEST['cid_'.$Itemid]))
                {
                    $this->_load_ecc_check = true;
                }
            }
            elseif($option == 'com_flexicontact' OR $option == 'com_flexicontactplus') // FlexiContact - tested with version 5.12 / FlexiContact Plus - tested with version 6.07
            {
                if($option == 'com_flexicontact')
                {
                    $regex_output = '<input type="submit" class=".+" name="send_button".+/>';
                }
                elseif($option == 'com_flexicontactplus')
                {
                    $regex_output = '<div class="fcp_sendrow">';
                }

                $this->_extension_info = array($option, '<form[^>]+name="fc.?_form".+</form>', '<input type=.+>', $regex_output, 'send');

                if((($this->params->get('flexicontact') AND $option == 'com_flexicontact') OR ($this->params->get('flexicontactplus') AND $option == 'com_flexicontactplus')) AND $view == 'contact' AND empty($task))
                {
                    $this->_load_ecc = true;
                }
                elseif((($this->params->get('flexicontact') AND $option == 'com_flexicontact') OR ($this->params->get('flexicontactplus') AND $option == 'com_flexicontactplus')) AND $view == 'contact' AND $task == 'send')
                {
                    $this->_load_ecc_check = true;
                }
            }
            elseif($option == 'com_kunena') // Kunena Forum - tested with version 1.7.2, 2.0.2 and 3.0.3
            {
                $this->_extension_info = array('com_kunena', '<form[^>]+id="postform".+</form>', '<input type=.+>', '<input type="submit" name="ksubmit" class="kbutton".+/>', 'post');

                if($this->params->get('kunena') AND ($func == 'post' OR ($view == 'topic' AND ($layout == 'reply' OR $layout == 'create'))) AND empty($_REQUEST["ksubmit"]))
                {
                    $this->_load_ecc = true;
                }
                elseif($this->params->get('kunena') AND ($func == 'post' OR $task == 'post') AND !empty($_REQUEST["ksubmit"]))
                {
                    $this->_load_ecc_check = true;
                }
            }
            elseif($option == 'com_alfcontact') // ALFContact - tested with version 2.0.3
            {
                $this->_extension_info = array('com_alfcontact', '<form[^>]+id="contact-form".+</form>', '<label for=".+>', '<button class="button">', 'sendemail');

                if($this->params->get('alfcontact') AND $view == 'alfcontact' AND empty($task))
                {
                    $this->_load_ecc = true;
                }
                elseif($this->params->get('alfcontact') AND $task == 'sendemail')
                {
                    $this->_load_ecc_check = true;
                }
            }
            elseif($option == 'com_aicontactsafe') // aiContactSafe - tested with version 2.0.19
            {
                $this->_extension_info = array('com_aicontactsafe', '<form[^>]+id="adminForm_.+</form>', '<label for=".+>', '<input type="submit" id="aiContactSafeSendButton"', 'display');

                $sTask = $this->_request->get('sTask', '', 'STRING');

                if($this->params->get('aicontactsafe') AND empty($sTask))
                {
                    $this->_load_ecc = true;
                }
                elseif($this->params->get('aicontactsafe') AND $sTask == 'message')
                {
                    $this->_load_ecc_check = true;
                }
            }
            elseif($option == 'com_community') // JomSocial - tested with version 2.6 RC2
            {
                $this->_extension_info = array('com_community', '<form[^>]+id="jomsForm".+</form>', '<label id=".+>', '<div[^>]+cwin-wait.*></div>', 'register_save');

                if($this->params->get('jomsocial') AND $view == 'register' AND ($task == '' OR $task == 'register'))
                {
                    $this->_load_ecc = true;
                }
                elseif($this->params->get('jomsocial') AND $view == 'register' AND $task == 'register_save')
                {
                    $this->_load_ecc_check = true;
                }
            }
            elseif($option == 'com_virtuemart') // Virtuemart - tested with version 2.0.24a
            {
                if($task == 'askquestion' OR $task == 'mailAskquestion')
                {
                    $this->_extension_info = array('com_virtuemart', '<form[^>]+id="askform".+</form>', '<label>', '<input[^>]*type="submit" name="submit_ask"[^>]*/>', 'mailAskquestion');

                    if($this->params->get('virtuemart') AND $view == 'productdetails' AND $task == 'askquestion')
                    {
                        $this->_load_ecc = true;
                    }
                    elseif($this->params->get('virtuemart') AND $view == 'productdetails' AND $task == 'mailAskquestion')
                    {
                        $this->_load_ecc_check = true;
                    }
                }
                elseif($task == 'editaddresscheckout' OR $task == 'registercheckoutuser' OR $task == 'savecheckoutuser')
                {
                    $this->_extension_info = array('com_virtuemart', '<form[^>]+id="userForm".+</form>', '<label.+>', '<button[^>]*type="submit"[^>]*>', 'savecheckoutuser');

                    if($this->params->get('virtuemart') AND $view == 'user' AND $task == 'editaddresscheckout')
                    {
                        $this->_load_ecc = true;
                    }
                    elseif($this->params->get('virtuemart') AND $view == 'user' AND ($task == 'registercheckoutuser' OR $task == 'savecheckoutuser'))
                    {
                        $this->_load_ecc_check = true;
                    }
                }
                elseif($task == 'editaddresscart' OR $task == 'registercartuser' OR $task == 'savecartuser')
                {
                    $this->_extension_info = array('com_virtuemart', '<form[^>]+id="userForm".+</form>', '<label.+>', '<button[^>]*type="submit"[^>]*>', 'savecartuser');

                    if($this->params->get('virtuemart') AND $view == 'user' AND $task == 'editaddresscart')
                    {
                        $this->_load_ecc = true;
                    }
                    elseif($this->params->get('virtuemart') AND $view == 'user' AND ($task == 'registercartuser' OR $task == 'savecartuser'))
                    {
                        $this->_load_ecc_check = true;
                    }
                }
                elseif($view == 'user' AND ($layout == 'edit' OR $layout == 'default' OR $task == 'saveUser' OR $task == 'register'))
                {
                    $this->_extension_info = array('com_virtuemart', '<form[^>]+name="userForm".+</form>', '<label.+>', '<button[^>]*type="submit"[^>]*>', 'saveUser');

                    if($this->params->get('virtuemart') AND ($layout == 'edit' OR $layout == 'default' OR $task == 'register') AND $task != 'saveUser')
                    {
                        $this->_load_ecc = true;
                    }
                    elseif($this->params->get('virtuemart') AND $task == 'saveUser')
                    {
                        $this->_load_ecc_check = true;
                    }
                }
            }
        }

        // Clear user_login session variable to avoid errors if a user logs in via the module
        if($this->params->get('user_login') AND $this->_session->get('user_login', null, 'easycalccheck'))
        {
            if($option == 'com_users')
            {
                if($this->_load_ecc == false)
                {
                    $this->_session->clear('user_login', 'easycalccheck');
                }
            }
            else
            {
                $this->_session->clear('user_login', 'easycalccheck');
            }
        }
    }

    /**
     * Checks whether ECC+ has to be loaded in a custom call
     *
     * @return boolean
     */
    private function loadEccCustom()
    {
        // Do not execute the custom call in the administration or if the check is disabled for guests
        if($this->_app->isAdmin() OR ($this->params->get('onlyguests') AND !$this->_user->guest))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Saves entered data into the session
     */
    private function saveData()
    {
        $request = $this->_request->getArray($_REQUEST);
        $data_array = array();
        $keys_exclude = array('option', 'view', 'layout', 'id', 'Itemid', 'task', 'controller', 'func');

        foreach($request as $key => $value)
        {
            if(!in_array($key, $keys_exclude))
            {
                if(is_array($value))
                {
                    foreach($value as $key2 => $value2)
                    {
                        // Need second request for user profile plugin
                        if(is_array($value2))
                        {
                            foreach($value2 as $key3 => $value3)
                            {
                                $key4 = $key.'['.$key2.']['.$key3.']';
                                $data_array[$key4] = $value3;
                            }
                        }
                        else
                        {
                            $key3 = $key.'['.$key2.']';
                            $data_array[$key3] = $value2;
                        }
                    }
                }
                else
                {
                    $data_array[$key] = $value;
                }
            }
        }

        $this->_session->set('saved_data', $data_array, 'easycalccheck');
    }

    /**
     * Fills the form with the entered data from the user - autofill function
     *
     * @param string $body
     * @param array $match_extension_main
     */
    private function fillForm(&$body, &$match_extension_main)
    {
        $autofill = $this->_session->get('saved_data', null, 'easycalccheck');

        if(!empty($autofill))
        {
            // Get form of extension
            $pattern_form = '@'.$this->_extension_info[1].'@isU';
            preg_match($pattern_form, $body, $match_extension);

            $pattern_input = '@<input[^>].*/?>@isU';
            preg_match_all($pattern_input, $match_extension[0], $matches_input);

            foreach($matches_input[0] as $input_value)
            {
                foreach($autofill as $key => $autofill_value)
                {
                    if($autofill_value != '')
                    {
                        $value = '@name=("|\')'.preg_quote($key).'("|\')@isU';

                        if(preg_match($value, $input_value))
                        {
                            $value = '@value=("|\').*("|\')@isU';

                            if(preg_match($value, $input_value, $match))
                            {
                                $pattern_value = '/'.preg_quote($match[0], '/').'/isU';
                                $input_value_replaced = preg_replace($pattern_value, 'value="'.$autofill_value.'"', $input_value);

                                // Set the value to the body and the extension form for further modifications
                                $body = str_replace($input_value, $input_value_replaced, $body);
                                $match_extension_main[0] = str_replace($input_value, $input_value_replaced, $match_extension_main[0]);
                                unset($autofill[$key]);
                                break;
                            }
                        }
                    }
                }
            }

            $pattern_textarea = '@<textarea[^>].*>(.*</textarea>)@isU';
            preg_match_all($pattern_textarea, $match_extension[0], $matches_textarea);

            $count = 0;

            foreach($matches_textarea[0] as $textarea_value)
            {
                foreach($autofill as $key => $autofill_value)
                {
                    $value = '@name=("|\')'.preg_quote($key).'("|\')@';

                    if(preg_match($value, $textarea_value))
                    {
                        $pattern_value = '@'.preg_quote($matches_textarea[1][$count]).'@isU';
                        $textarea_value_replaced = preg_replace($pattern_value, $autofill_value.'</textarea>', $textarea_value);

                        // Set the value to the body and the extension form for further modifications
                        $body = str_replace($textarea_value, $textarea_value_replaced, $body);
                        $match_extension_main[0] = str_replace($textarea_value, $textarea_value_replaced, $match_extension_main[0]);
                        unset($autofill[$key]);
                        break;
                    }
                }

                $count++;
            }

            $this->_session->clear('saved_data', 'easycalccheck');
        }
    }

    /**
     * Calls checks for supported extensions
     *
     * @param string $option
     * @param string $task
     */
    private function callChecks($option, $task)
    {
        $check_failed = false;
        $Itemid = $this->_request->get('Itemid', '', 'CMD');

        if($option == 'com_users' AND ($task == 'reset.request' OR $task == 'remind.remind'))
        {
            if(!$this->performChecks())
            {
                $check_failed = true;
            }
        }
        elseif($option == 'com_users' AND $task == 'user.login')
        {
            if(!$this->performChecks())
            {
                $check_failed = true;
            }
            else
            {
                $this->_session->clear('failed_login_attempts', 'easycalccheck');
            }
        }
        elseif($option == 'com_easybookreloaded' AND $task == 'save')
        {
            if(!$this->performChecks())
            {
                $this->_session->set('easybookreloaded', 1, 'easycalccheck');
                $check_failed = true;
            }
        }
        elseif($option == 'com_phocaguestbook' AND $task == 'submit')
        {
            if(!$this->performChecks())
            {
                $this->_session->set('phocaguestbook', 1, 'easycalccheck');
                $check_failed = true;
            }
        }
        elseif($option == 'com_comprofiler' AND $task == 'sendNewPass')
        {
            if(!$this->performChecks())
            {
                $check_failed = true;
            }
        }
        elseif($option == 'com_dfcontact' AND !empty($_REQUEST["submit"]))
        {
            if(!$this->performChecks())
            {
                $check_failed = true;
            }
        }
        elseif($option == 'com_foxcontact' AND isset($_REQUEST['cid_'.$Itemid]))
        {
            if(!$this->performChecks())
            {
                $check_failed = true;
            }
        }
        elseif(($option == 'com_flexicontact' OR $option == 'com_flexicontactplus') AND $task == 'send')
        {
            if(!$this->performChecks())
            {
                $check_failed = true;
            }
        }
        elseif($option == 'com_kunena' AND !empty($_REQUEST["ksubmit"]))
        {
            if(!$this->performChecks())
            {
                $check_failed = true;
            }
        }
        elseif($option == 'com_alfcontact' AND $task == 'sendemail')
        {
            if(!$this->performChecks())
            {
                $check_failed = true;
            }
        }
        elseif($option == 'com_community' AND $task == 'register_save')
        {
            if(!$this->performChecks())
            {
                $check_failed = true;
            }
        }
        elseif($option == 'com_virtuemart' AND ($task == 'mailAskquestion' OR $task == 'registercheckoutuser' OR $task == 'savecheckoutuser' OR $task == 'registercartuser' OR $task == 'savecartuser' OR $task == 'saveUser'))
        {
            if(!$this->performChecks())
            {
                $check_failed = true;
            }
        }
        elseif($option == 'com_aicontactsafe')
        {
            $sTask = $this->_request->get('sTask', '', 'STRING');

            if($sTask == 'message')
            {
                if(!$this->performChecks())
                {
                    $check_failed = true;
                }
            }
        }

        if($check_failed == true)
        {
            // Set error session variable for the message output
            $this->_session->set('error_output', 'check_failed', 'easycalccheck');
            $this->redirect($this->_redirect_url);
        }
    }

    /**
     * Decode encoded fields
     *
     * @since 2.5-8
     */
    private function decodeFields()
    {
        // Get all saved session variables in the namespace easycalccheck_encode
        // Workaround needed because the API doesn't support to get all variables
        // at once without knowing the name of them
        if(!empty($_SESSION['__easycalccheck_encode']))
        {
            $encoded_variables = $_SESSION['__easycalccheck_encode'];
        }

        if(!empty($encoded_variables))
        {
            $form = array();

            foreach($encoded_variables as $key => $value)
            {
                $value_request = $this->_request->get($value, null, 'STRING');

                // Decode variable only if it is set!
                if(isset($value_request))
                {
                    // Is this decoded variable trasmitted in the request?
                    if(!empty($value_request))
                    {
                        // If key is an array, then handle it correctly
                        if(preg_match('@(.*)\[(.+)\]@isU', $key, $matches))
                        {
                            $form[$matches[1]][$matches[2]] = $value_request;
                        }
                        else
                        {
                            $form[$key] = $value_request;
                        }
                    }
                    else
                    {
                        // If key is an array, then handle it correctly
                        if(preg_match('@(.*)\[(.+)\]@isU', $key, $matches))
                        {
                            $form[$matches[1]][$matches[2]] = '';
                        }
                        else
                        {
                            $form[$key] = '';
                        }
                    }

                    // Remove the decoded variable from the request - JInput does not provide an unset function - access directly
                    unset($_REQUEST[$value]);
                }
            }

            // Set the decoded fields back to the request variable
            foreach($form as $key => $value)
            {
                $this->_request->set($key, $value);

                // We also need to set the variable to the global $_POST variable - needed for the token check of the components
                // Do not use the API because we need first to gather all information - set variables directly
                $_POST[$key] = $value;
            }

            unset($_SESSION['__easycalccheck_encode']);
        }
    }

    /**
     * Redirects if spamcheck was not passed successfully
     *
     * @param string $redirect_url
     */
    private function redirect($redirect_url)
    {
        // PHP Redirection
        header('Location: '.$redirect_url);

        // JS Redirection - as fallback
        ?>
        <script type="text/javascript">window.location = '<?php echo $redirect_url; ?>'</script>
        <?php
        // White page - if redirection doesn't work
        echo JText::_('PLG_ECC_YOUHAVENOTRESOLVEDOURSPAMCHECK');
        jexit();
    }

    /**
     * Creates one or an array of random strings
     *
     * @param integer $count
     * @return mixed - if $count is 1, then string else array
     */
    private function random($count = 1)
    {
        $characters = range('a', 'z');
        $numbers = range(0, 9);
        $pw_array = array();

        for($i = 0; $i < $count; $i++)
        {
            $pw = '';

            // first character has to be a letter
            $pw .= $characters[mt_rand(0, 25)];

            // other characters arbitrarily
            $characters = array_merge($characters, $numbers);

            $pw_length = mt_rand(4, 12);

            for($a = 0; $a < $pw_length; $a++)
            {
                $pw .= $characters[mt_rand(0, 35)];
            }

            $pw_array[] = $pw;
        }

        if($count == 1)
        {
            $pw_array = $pw_array[0];
        }

        return $pw_array;
    }

    /**
     * Converts numbers into strings
     *
     * @param int $x
     * @return string
     */
    private function converttostring($x)
    {
        // Probability 2/3 for conversion
        $random = mt_rand(1, 3);

        if($random != 1)
        {
            if($x > 20)
            {
                return $x;
            }
            else
            {
                // Names of the numbers are read from language file
                $names = array(JText::_('PLG_ECC_NULL'), JText::_('PLG_ECC_ONE'), JText::_('PLG_ECC_TWO'), JText::_('PLG_ECC_THREE'), JText::_('PLG_ECC_FOUR'), JText::_('PLG_ECC_FIVE'), JText::_('PLG_ECC_SIX'), JText::_('PLG_ECC_SEVEN'), JText::_('PLG_ECC_EIGHT'), JText::_('PLG_ECC_NINE'), JText::_('PLG_ECC_TEN'), JText::_('PLG_ECC_ELEVEN'), JText::_('PLG_ECC_TWELVE'), JText::_('PLG_ECC_THIRTEEN'), JText::_('PLG_ECC_FOURTEEN'), JText::_('PLG_ECC_FIFTEEN'), JText::_('PLG_ECC_SIXTEEN'), JText::_('PLG_ECC_SEVENTEEN'), JText::_('PLG_ECC_EIGHTEEN'), JText::_('PLG_ECC_NINETEEN'), JText::_('PLG_ECC_TWENTY'));
                return $names[$x];
            }
        }
        else
        {
            return $x;
        }
    }

    /**
     * This function prepares the custom call for the correct output
     *
     * @return type
     */
    private function customCall()
    {
        // Read in content of the output
        $body = JResponse::getBody();

        if(preg_match("@(<form[^>]*>)(.*)({easycalccheckplus})(.*</form>)@Us", $body, $matches))
        {
            // Workaround to get the correct form if several form attributes are provided on the loaded page
            if(strripos($matches[2], '<form') !== false)
            {
                $matches[0] = substr($matches[2], strripos($matches[2], '<form')).$matches[3].$matches[4];

                // Set a new matches array with the correct form
                preg_match("@(<form[^>]*>)(.*)({easycalccheckplus})(.*)(</form>)@Us", $matches[0], $matches);
            }

            if(!empty($matches))
            {
                // Custom call string was found, set needed class attribute
                $this->_custom_call = true;

                // Clean the cache of the component first
                $this->cleanCache();

                // The request does not have to be validated, so get all information for the output of the checks
                $custom_call_form = $matches[0];
                $custom_call_form_content = $matches[2].$matches[4];

                // Do some general checks to get needed information from the form of the unknown extension
                // Hidden field - check whether labels are used if not take the input tags
                if(strripos($custom_call_form_content, '<label') !== false)
                {
                    $custom_call_hidden_regex = '<label.+>';
                }
                else
                {
                    $custom_call_hidden_regex = '<input.+>';
                }

                // Get task value of the form
                if(strripos($custom_call_form_content, 'name="task"') !== false)
                {
                    preg_match('@<input.+name="task".+>@U', $custom_call_form_content, $match_task);

                    if(preg_match('@value="(.+)"@', $match_task[0], $match_value))
                    {
                        $custom_call_form_task = $match_value[1];
                    }
                    else
                    {
                        $custom_call_form_task = '';
                    }
                }
                else
                {
                    $custom_call_form_task = '';
                }

                // Set the extension info array for the further execution with the collected information
                // Array -> (name, form, regex for hidden field, regex for output, task, request exception for encode option);
                $this->_load_ecc = true;
                $this->_extension_info = array($this->_request->get('option', '', 'WORD'), preg_quote($custom_call_form), $custom_call_hidden_regex, '{easycalccheckplus}', $custom_call_form_task);

                // Set the needed CSS instructions - since we are already in the trigger onAfterRender, we have to manipulate the output manually
                $head = array();
                $head[] = '<style type="text/css">#easycalccheckplus {margin: 8px 0 !important; padding: 2px !important;}</style>';

                if($this->params->get('poweredby'))
                {
                    $head[] = '<style type="text/css">.protectedby {font-size: x-small !important; text-align: right !important;}</style>';
                }

                if($this->params->get('type_hidden'))
                {
                    $this->_session->set('hidden_class', $this->random(), 'easycalccheck');
                    $head[] = '<style type="text/css">.'.$this->_session->get('hidden_class', null, 'easycalccheck').' {display: none !important;}</style>';
                }

                if($this->params->get('recaptcha_theme'))
                {
                    if($this->params->get('recaptcha_theme') == 1)
                    {
                        $theme = 'white';
                    }
                    elseif($this->params->get('recaptcha_theme') == 2)
                    {
                        $theme = 'blackglass';
                    }
                    elseif($this->params->get('recaptcha_theme') == 3)
                    {
                        $theme = 'clean';
                    }

                    $head[] = '<script type="text/javascript">var RecaptchaOptions = { theme : "'.$theme.'" };</script>';
                }

                $head = implode("\n", $head)."\n";

                // Set body after the modifications have been applied
                $body = str_replace('</head>', $head.'</head>', $body);
                JResponse::setBody($body);

                // Set the custom call session variable - Get all possible request variable of the loaded form
                preg_match_all('@name=["|\'](.*)["|\']@Us', $matches[0], $matches_request_variables);
                $this->_session->set('check_custom_call', $matches_request_variables[1], 'easycalccheck');
            }
        }

        return;
    }

    /**
     * Cleans cache to avoid inconsistent output
     */
    private function cleanCache()
    {
        $config = JFactory::getConfig();

        if($config->get('caching') != 0)
        {
            $cache = JFactory::getCache($this->_request->get('option', '', 'WORD'));
            $cache->clean();
        }
    }

    /**
     * Loads error notice if needed only once per process
     *
     * @param type $custom
     * @return type
     */
    private function raiseErrorWarning($option, $custom = false)
    {
        if(empty($this->_warning_shown))
        {
            // Load error session variable for the message output
            $error_output = $this->_session->get('error_output', NULL, 'easycalccheck');

            if(!empty($error_output))
            {
                if($error_output == 'check_failed')
                {
                    if(($option == 'com_phocaguestbook' AND $this->_session->get('phocaguestbook', null, 'easycalccheck') == 0) OR ($option == 'com_easybookreloaded' AND $this->_session->get('easybookreloaded', null, 'easycalccheck') == 0))
                    {
                        // No message output needed - message is raised by components
                    }
                    else
                    {
                        JError::raiseWarning(100, JText::_('PLG_ECC_YOUHAVENOTRESOLVEDOURSPAMCHECK'));
                    }
                }
                elseif($error_output == 'check_failed_custom' AND $custom == true)
                {
                    // Only raise general error message if the custom call is used
                    JError::raiseWarning(100, JText::_('PLG_ECC_YOUHAVENOTRESOLVEDOURSPAMCHECK'));
                }
                elseif($error_output == 'login_attempts')
                {
                    JError::raiseWarning(100, JText::_('PLG_ECC_TOOMANYFAILEDLOGINATTEMPTS'));
                }

                $this->_session->clear('error_output', 'easycalccheck');
                $this->_warning_shown = true;
            }
        }

        return;
    }

}

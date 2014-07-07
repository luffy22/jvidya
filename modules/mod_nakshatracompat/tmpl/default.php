<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');
define('_JEXEC',1);
?>
<h3>Nakshatra Compatibility Calculator</h3>
<div class="nakshatratr">
    <div class="nakshatratdl">
        <div id="g_rashi_notice"><strong>Choose Girls Rashi</strong></div>
        <select id="g_rashi" name="girls_rashi" class="selectcomp" onclick="javascript:getGirlsNakshatra();">
            <option value="" default="default">Girls Rashi</option>
            <option value="aries">Mesha(Aries)</option>
            <option value="taurus">Vrisabha(Taurus)</option>
            <option value="gemini">Mithuna(Gemini)</option>
            <option value="cancer">Karka(Cancer)</option>
            <option value="leo">Simha(Leo)</option>
            <option value="virgo">Kanya(Virgo)</option>
            <option value="libra">Tula(Libra)</option>
            <option value="scorpio">Vrischika(Scorpio)</option>
            <option value="saggitarius">Dhana(Saggitarius)</option>
            <option value="capricorn">Makara(Capricorn)</option>
            <option value="aquarius">Kumbha(Aquarius)</option>
            <option value="pisces">Meena(Pisces)</option>
        </select>
    </div>
    <div class="nakshatratdr">
        <div id="b_rashi_notice"><strong>Choose Boys Rashi</strong></div>
        <select id="b_rashi" name="boys_rashi" class="selectcomp" onclick="javascript:getBoysNakshatra();">
            <option value="" default="default">Boys Rashi</option>
            <option value="aries">Mesha(Aries)</option>
            <option value="taurus">Vrisabha(Taurus)</option>
            <option value="gemini">Mithuna(Gemini)</option>
            <option value="cancer">Karka(Cancer)</option>
            <option value="leo">Simha(Leo)</option>
            <option value="virgo">Kanya(Virgo)</option>
            <option value="libra">Tula(Libra)</option>
            <option value="scorpio">Vrischika(Scorpio)</option>
            <option value="saggitarius">Dhana(Saggitarius)</option>
            <option value="capricorn">Makara(Capricorn)</option>
            <option value="aquarius">Kumbha(Aquarius)</option>
            <option value="pisces">Meena(Pisces)</option>
        </select>
    </div>
</div>
<div class="nakshatratr">
    <div class="nakshatratdl">
        <div id="nakshatra_calc">
            <div id="g_nakshtra_notice"><strong>Choose Girls Nakshatra</strong></div>
            <select id="g_nakshatra" name="girls_nakshatra" class="selectcomp" onclick="getGirlsPada();">
                <option value="" default="default">Select Default</option>
            </select>
        </div>
    </div>
    <div class="nakshatratdr">
        <div id="nakshatra_b_calc">
            <div id="b_nakshtra_notice"><strong>Choose Boys Nakshatra</strong></div>
            <select id="b_nakshatra" name="boys_nakshatra" class="selectcomp" onclick="getBoysPada();">
                <option value="" default="default">Select Default</option>
            </select>
        </div>
    </div>           
</div>
<div class="nakshatratr">
    <div class="nakshatratdl">
        <div id="g_pada_notice"><strong>Choose Girls Pada</strong></div>
        <select id="g_pada" name="girls_pada" class="selectcomp">
            <option value="" default="default">Select Default</option>
        </select>
    </div>
    <div class="nakshatratdr">
        <div id="b_pada_notice"><strong>Choose Boys Pada</strong></div>
        <select id="b_pada" name="girls_pada" class="selectcomp">
            <option value="" default="default">Select Default</option>
        </select>
    </div>
</div>
<div class="nakshatratr">
    <div class="controls"><button class="btn btn-primary" id="compat-submit" onclick="checkCompatibility()">Check Compatibility</button></div>
</div>
<div class="nakshatratr">
<div class="error" id="match_message"></div>
</div>
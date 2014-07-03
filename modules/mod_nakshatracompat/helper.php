<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Helper for mod_nakshatracompat
 *
 * @package     Joomla.Site
 * @subpackage  mod_login
 * @since       1.5
 */
class modNakshatraCompatHelper
{
    public function showGirlsMenu()
    {
?>
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
<?php
    }
    public function showBoysMenu()
    {
?>
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
<?php
    }
    public function showGirlsNakshatra()
    {
?>
        <div id="nakshatra_calc">
            <div id="g_nakshtra_notice"><strong>Choose Girls Nakshatra</strong></div>
            <select id="g_nakshatra" name="girls_nakshatra" class="selectcomp" onclick="getGirlsPada();">
                <option value="" default="default">Select Default</option>
            </select>
        </div>
<?php
    }
    public function showBoysNakshatra()
    {
?>
        <div id="nakshatra_b_calc">
            <div id="b_nakshtra_notice"><strong>Choose Boys Nakshatra</strong></div>
            <select id="b_nakshatra" name="boys_nakshatra" class="selectcomp" onclick="getBoysPada();">
                <option value="" default="default">Select Default</option>
            </select>
        </div>
<?php
    }
    public function GirlNakshatraAjax()
    {
        if(isset($_GET['g_rashi']))
        {
            $girlsrashi = $_GET['g_rashi'];
            
            $db             = JFactory::getDbo();  // Get db connection
            $query          = $db->getQuery(true);
            $query          = "SELECT DISTINCT girls_nakshatra "."FROM jv_nakshatra_compatibility"." where girls_rashi='$girlsrashi'";
            $db             ->setQuery($query);
            $row            =$db->loadColumn();
            $count          = count($db->loadColumn());
    ?>
        <option value="" default="default">Select Nakshatra</option>
    <?php
            for($i=0;$i<$count;$i++)
            { 
        ?>
            <option value="<?php echo $row[$i]; ?>"><?php echo $row[$i]; ?></option>
        <?php
            }
        }
        else
        {
            echo "error";
        }
    }
    public function BoyNakshatraAjax()
    {
        if(isset($_GET['b_rashi']))
        {
            $boysrashi = $_GET['b_rashi'];
            
            $db             = JFactory::getDbo();  // Get db connection
            $query          = $db->getQuery(true);
            $query          = "SELECT DISTINCT boys_nakshatra "."FROM jv_nakshatra_compatibility"." where boys_rashi='$boysrashi'";
            $db             ->setQuery($query);
            $row            =$db->loadColumn();
            $count          = count($db->loadColumn());
    ?>
        <option value="" default="default">Select Nakshatra</option>
    <?php
            for($i=0;$i<$count;$i++)
            { 
        ?>
            <option value="<?php echo $row[$i]; ?>"><?php echo $row[$i]; ?></option>
        <?php
            }
        }
        else
        {
            echo "error";
        }
    }
    public function showGirlsPada()
    {
?>
        <div id="g_pada_notice"><strong>Choose Girls Pada</strong></div>
        <select id="g_pada" name="girls_pada" class="selectcomp">
            <option value="" default="default">Select Default</option>
        </select>
<?php 
    }
    public function GirlPadaAjax()
    {
        if((isset($_GET['g_rashi']))&&(isset($_GET['g_nakshatra'])))
        {
            $girlsrashi     = $_GET['g_rashi'];
            $girlsnakshatra = $_GET['g_nakshatra'];
            $db             = JFactory::getDbo();  // Get db connection
            $query          = $db->getQuery(true);
            $query          = "SELECT DISTINCT girls_pada "."FROM jv_nakshatra_compatibility"." where girls_rashi='$girlsrashi' AND girls_nakshatra='$girlsnakshatra'";
            $db             ->setQuery($query);
            $row            = $db->loadAssoc();
?>
            <option value="" default="default">Select Pada</option>
    <?php
            $padas         = explode(", ", $row['girls_pada']);
            $count         = count($padas);
            for($i=0;$i<$count;$i++)
            {
        ?>
            <option value="<?php echo $padas[$i]; ?>"><?php echo $padas[$i]; ?></option>
        <?php
            }
        }
        else
        {
            echo "error";
        }
    }
     public function showBoysPada()
    {
?>
        <div id="b_pada_notice"><strong>Choose Boys Pada</strong></div>
        <select id="b_pada" name="girls_pada" class="selectcomp">
            <option value="" default="default">Select Default</option>
        </select>
<?php 
    }
    public function BoyPadaAjax()
    {
        if((isset($_GET['b_rashi']))&&(isset($_GET['b_nakshatra'])))
        {
            $boyrashi     = $_GET['b_rashi'];
            $boynakshatra = $_GET['b_nakshatra'];
            $db             = JFactory::getDbo();  // Get db connection
            $query          = $db->getQuery(true);
            $query          = "SELECT DISTINCT boys_pada "."FROM jv_nakshatra_compatibility"." where boys_rashi='$boyrashi' AND boys_nakshatra='$boynakshatra'";
            $db             ->setQuery($query);
            $row            = $db->loadAssoc();
?>
            <option value="" default="default">Select Pada</option>
    <?php
            $padas         = explode(", ", $row['boys_pada']);
            $count         = count($padas);
            for($i=0;$i<$count;$i++)
            {
        ?>
            <option value="<?php echo $padas[$i]; ?>"><?php echo $padas[$i]; ?></option>
        <?php
            }
        }
        else
        {
            echo "error";
        }
    }
    public function GetPointsAjax()
    {
        if((isset($_GET['g_1']))&&(isset($_GET['g_2']))&&(isset($_GET['g_3']))
         &&(isset($_GET['b_1']))&&(isset($_GET['b_2']))&&(isset($_GET['b_3'])))
        {
            $g1             = $_GET['g_1'];
            $g2             = $_GET['g_2'];
            $g3             = $_GET['g_3'];
            $b1             = $_GET['b_1'];
            $b2             = $_GET['b_2'];
            $b3             = $_GET['b_3'];
            $db             = JFactory::getDbo();  // Get db connection
            $query          = $db->getQuery(true);
            $query          = "SELECT points "."FROM jv_nakshatra_compatibility"." where girls_rashi='$g1' AND girls_nakshatra='$g2'
                               AND girls_pada LIKE '%$g3%' AND boys_rashi='$b1' AND boys_nakshatra='$b2'
                               AND boys_pada LIKE '%$b3%'";
            $db             ->setQuery($query);
            $row            = $db->loadAssoc();
            $count          = count($db->loadAssoc());
            if($count>0)
            {
                echo $row['points'];
            }
            else
            {
                echo "error";
            }
        }
        else
        {
            echo "error";
        }
    }
}

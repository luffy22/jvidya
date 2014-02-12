<?php

/*
 * The default file for mod_menudrop
 * Prints the navigation with css
 */
 defined('_JEXEC') or die;
?>
<html>
    <body>
        <a class="dropdown-toggle btn-primary" id="<?php echo "menu_".$menuActiveId; ?>" data-toggle="dropdown" href="#"
           onclick="javascript:toggleMenu(<?php echo "menu_".$menuActiveId; ?>)">
            <?php
                    echo $menuTitle;
            ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu btn-primary">
        <?php 
            foreach($list as $key=>$value)
            {
                $url   = JRoute::_($value->link . "&Itemid=" . $value->id); // use JRoute to make link from object
            ?>
                <li>
                <a href="<?php echo $url; ?>">
                    <?php
                        echo $value->title;
                    ?>
                </a>
                </li>
           <?php  
            }
        ?>
        </ul>
    </body>
</html>
 


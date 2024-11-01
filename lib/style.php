<?php
class CSSDynamic
{


    private $css_string = '';
    public function __construct()
    {


    }

    public function save()
    {
        $this->build();
        $this->save_css_string_to_file();

    }

    function build()
    {

        //build heading
        $this->build_css_head();
        //build css for floating bar
        $this->build_floating_bar_css();


    }

    public function build_css_head()
    {
        ob_start();
?>
/* -------------------------------------------------------------
Do not change this CSS FIle.This file is dynamically generated 
 -------------------------------------------------------------*/

<?php
        $this->css_string .= ob_get_clean();
    }
    public function build_floating_bar_css()
    {

        global $option;
        /* $floating_top = !empty($option['global_floating_box_options']['floating_box_top_percent']) ?
        $option['global_floating_box_options']['floating_box_top_percent'] : "2";
        $floating_left = !empty($option['global_floating_box_options']['floating_box_left_percent']) ?
        $option['global_floating_box_options']['floating_box_left_percent'] : "2";
        */

        $floating_left_margin = !empty($option['global_floating_box_options']['floating_box_left_margin']) ?
            $option['global_floating_box_options']['floating_box_left_margin'] : "0";


        ob_start();
?>
/* --------------------- 
Floating Bar CSS
------------------------*/


div.share-juice-floating-bar{
    
    margin-left: <?php echo $floating_left_margin; ?>px;
    }



<?php

        $this->css_string .= ob_get_clean();


    }

    function save_css_string_to_file()
    {

        $path = SJ_SCRIPTS_DIR_BASE . "/style-add.css";
        $handle = fopen($path, 'w+');
        fwrite($handle, $this->css_string);
        fclose($handle);

    }
}
?>
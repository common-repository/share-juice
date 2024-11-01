<?php
function foobar_func( $atts ){
  $icon_path = SJ_IMG_URL.'/icons/connect/64/';
  
 ?>
 <div class="share-juice-connect-icons share-juice-connect-horizonal" >
 <ul>
 <li><img src ="<?php echo $icon_path?>fb64.png" ></li>
 <li><img src ="<?php echo $icon_path?>tw64.png" ></li>
 <li><img src ="<?php echo $icon_path?>ln64.png" ></li>
 <li><img src ="<?php echo $icon_path?>gp64.png" ></li>
 <li><img src ="<?php echo $icon_path?>gp64.png" ></li>
 <li><img src ="<?php echo $icon_path?>gp64.png" ></li>
 <li><img src ="<?php echo $icon_path?>gp64.png" ></li>
 <li><img src ="<?php echo $icon_path?>gp64.png" ></li>
 </ul>
 <div class="clear-float-both"></div>
 </div>

 <?php
}
add_filter('widget_text', 'do_shortcode');
add_shortcode( 'testcode', 'foobar_func' );
/* <ul>
 <li><img src ="<?php echo $icon_path?>fb32.png" ></li>
 <li><img src ="<?php echo $icon_path?>tw32.png" ></li>
 <li><img src ="<?php echo $icon_path?>ln32.png" ></li>
 <li><img src ="<?php echo $icon_path?>gp32.png" ></li>
 </ul>*/


?>
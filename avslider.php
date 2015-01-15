<?php

/* Plugin Name: AVslider

Plugin URI:

Description: Slider Component for WordPress

Version: 1.0

Author: Avinash Bode

Author URI:

License: GPLv2 or later

*/



// Hook for adding admin menus



add_action('admin_menu', 'mt_add_pages');



// action function for above hook

function mt_add_pages() {



    // Add a new top-level menu (ill-advised):

    add_menu_page(__('AV Slider','menu-test'), __('AV Slider','menu-test'), 'manage_options', 'mt-top-level-handle', 'mt_toplevel_page' );

  



    // Add a submenu to the custom top-level menu:

   add_submenu_page('mt-top-level-handle', __('Slider Setting','menu-test'), __('Slider Setting','menu-test'), 'manage_options', 'sub-page2', 'mt_sublevel_page2');

}











//create databse table for plugin

global $avslider_db_version;

$avslider_db_version = '1.0';



function avslider_install() {

	global $wpdb;

	global $avslider_db_version;



	$table_name = $wpdb->prefix . 'avslider1';

	

	$charset_collate = $wpdb->get_charset_collate();



	 $sql = "CREATE TABLE $table_name (

		id mediumint(9) NOT NULL AUTO_INCREMENT,

		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,

		name tinytext NOT NULL,

		description text NOT NULL,

		order varchar(55) DEFAULT '' NOT NULL,

		url varchar(55) DEFAULT '' NOT NULL, 

		UNIQUE KEY id (id)

	) $charset_collate;";



	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $sql );



	add_option( 'avslider_db_version', $avslider_db_version );

}



function avslider_install_data() {

	global $wpdb;

	

	$welcome_name = 'Mr. WordPres';

	$welcome_text = 'Congratulations, you just completed the installation!';

	

	$table_name = $wpdb->prefix . 'avslider';

	

	$wpdb->insert( 

		$table_name, 

		array( 

			'time' => current_time( 'mysql' ), 

			'name' => $welcome_name, 

			'url' => $welcome_text, 

		) 

	);

}





register_activation_hook( __FILE__, 'avslider_install' );

register_activation_hook( __FILE__, 'avslider_install_data' ); 



global $av_db_version;

$av_db_version = '1.0';



function av_install() {

	global $wpdb;

	global $av_db_version;



	$table_name = $wpdb->prefix . 'avsliderman';

	$table_name1 = $wpdb->prefix . 'avslidersetting';

	

	$charset_collate = $wpdb->get_charset_collate();



	$sql = "CREATE TABLE $table_name (

		id mediumint(9) NOT NULL AUTO_INCREMENT,

		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,

		name tinytext NOT NULL,

		description text NOT NULL,

		position text NOT NULL,

		url varchar(255) DEFAULT '' NOT NULL,

		UNIQUE KEY id (id)

	)$charset_collate;";

	

	$sql1 = "CREATE TABLE $table_name1 (

		id mediumint(9) NOT NULL AUTO_INCREMENT,

		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,

		width text NOT NULL,

		height text NOT NULL,

		descriptionhs int NOT NULL,

		UNIQUE KEY id (id)

	)$charset_collate;";

	



	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); 

	dbDelta( $sql );

	dbDelta( $sql1 );



	add_option( 'av_db_version', $av_db_version );

}





register_activation_hook( __FILE__, 'av_install' );











// the page content for the custom Test Toplevel menu

// mt_settings_page() displays the page content for the Test settings submenu

function mt_toplevel_page() {



    //must check that the user has the required capability 

    if (!current_user_can('manage_options'))

    {

      wp_die( __('You do not have sufficient permissions to access this page.') );

    }



  global $wpdb;





    // See if the user has posted us some information

    // If they did, this hidden field will be set to 'Y'

    if( isset($_POST[ 'avsubmit' ]) ) {



			

	$name = $_POST['name'];

	$order = $_POST['order'];

	$url = $_POST['url'];

	$description = $_POST['description'];



	

	$table_name = $wpdb->prefix . 'avsliderman';

	

	$wpdb->insert( 

		$table_name, 

		array( 

			'time' => current_time( 'mysql' ), 

			'name' => $name,

			'position'=> $order, 

			'url'  => $url,

			'description' => $description,

		) 

	);





        



?>

<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>

<?php



    }

	

	
	
	if( isset($_POST[ 'updateavsubmit' ]) ) {



			

	$name = $_POST['name'];

	$order = $_POST['order'];

	$url = $_POST['url'];

	$description = $_POST['description'];



	

	$table_name = $wpdb->prefix . 'avsliderman';

	$wpdb->update( 

	$table_name,  

	array( 

		'time' => current_time( 'mysql' ), 

			'name' => $name,

			'position'=> $order, 

			'url'  => $url,

			'description' => $description,	

	), 

	array( 'id' => $_GET['id'] )

	

);

	

	





        



?>

<div class="updated"><p><strong><?php _e('settings Updated.', 'menu-test' );?></strong></p></div>

<?php



    }



   

   ?>

<?php if($_GET['action']=='edit'){



$cid = $_GET['id'];

$table_name = $wpdb->prefix . 'avsliderman';

 $querystr = "

    SELECT ".$wpdb->prefix."avsliderman.* 

    FROM $table_name

	WHERE   $table_name.id=$cid

    ORDER BY ".$wpdb->prefix."avsliderman.position DESC

 ";



 $editpost = $wpdb->get_results($querystr, OBJECT);





}?>



<?php if($_GET['action']=='delete'){

$table_name = $wpdb->prefix . 'avsliderman';

$wpdb->delete( $table_name, array( 'ID' => $_GET['id'] ) );



?>

<div class="updated"><p><strong><?php _e('Data deleted.', 'menu-test' );?></strong></p></div>



<?php }?>

 <div class="wrap">







<h2> AV Slider Settings</h2>

<form name="form1" method="post" action="">





<p><?php _e("Name:", 'menu-test' );?> 

<input type="text" name="name" value="<?php echo $editpost[0]->name !=''? $editpost[0]->name : '';?>" size="20"></p>

<p><?php _e("URL:", 'menu-test' );?> 

<input type="text" name="url" value="<?php echo $editpost[0]->url !=''? $editpost[0]->url : '';?>" size="20"></p>

<p><?php _e("ORDER:", 'menu-test' );?> 

<input type="text" name="order" value="<?php echo $editpost[0]->position !=''? $editpost[0]->position : '';?>" size="20"></p>

<p><?php _e("Description:", 'menu-test' );?> 

<input type="text" name="description" value="<?php echo $editpost[0]->description !=''? $editpost[0]->description : '';?>"size="20"></p>

<hr />



<p class="submit">

<input type="submit" name="<?php echo $editpost[0]->id !=''? 'updateavsubmit' : 'avsubmit';?>" class="button-primary" value="<?php echo $editpost[0]->id !=''? esc_attr_e('Update Changes') : esc_attr_e('Save Changes');?>" />

</p>



</form>

</div>



<div >

<?php 

$table_name = $wpdb->prefix . 'avsliderman';

 $querystr = "

    SELECT ".$wpdb->prefix."avsliderman.* 

    FROM $table_name   

    ORDER BY ".$wpdb->prefix."avsliderman.position DESC

	

 ";



 $pageposts = $wpdb->get_results($querystr, OBJECT);

 

?>

<table class="wp-list-table widefat fixed pages" cellspacing="0">

	<thead>

	<tr>

   

     <th scope="col" id="title" class="manage-column column-title sortable desc" style=""><span>Title</span></th>

      <th scope="col" id="Order" class="manage-column column-author" style="">Order</th>

      <th scope="col" id="action" class="manage-column column-author" style="">Action</th>

    </tr>

    

    </thead>

    <tfoot>

	<tr>

		

          <th scope="col" id="title" class="manage-column column-title sortable desc" style=""><span>Title</span></th>

      <th scope="col" id="Order" class="manage-column column-author" style="">Order</th>

      <th scope="col" id="action" class="manage-column column-author" style="">Action</th>

        </tr>

	</tfoot>

    

    <tbody id="the-list">

  <?php   foreach ( $pageposts  as $imgv)

 {?>



 



					<tr id="post-2" class="post-2 type-page status-publish hentry alternate iedit author-self level-0" valign="top">

				

                            

                            <td class="post-title page-title column-title"><strong> <?php echo $imgv->name;?></strong>



</td>		

	<td class="author column-author"><?php echo $imgv->position;?></td>

    <td class="post-title page-title column-title"><span class="edit"><a href="admin.php?page=mt-top-level-handle&action=edit&id=<?php echo $imgv->id;?> " title="Edit this item">Edit</a></span> | <span class="trash"><a class="submitdelete" title="Move this item to the Trash" href="admin.php?page=mt-top-level-handle&action=delete&id=<?php echo $imgv->id;?> ">Delete</a> </td>

                            </tr>

                            

                            <?php }?>

                            </tbody>



    </table>



 </div>

<?php

 

}



// mt_sublevel_page() displays the page content for the first submenu

// of the custom Test Toplevel menu

function mt_sublevel_page() {?>

    <?php

 if (!current_user_can('manage_options'))

    {

      wp_die( __('You do not have sufficient permissions to access this page.') );

    }



  global $wpdb;





    // See if the user has posted us some information

    // If they did, this hidden field will be set to 'Y'

    if( isset($_POST[ 'avsubmit' ]) ) {



			

	$name = $_POST['name'];

	$order = $_POST['order'];

	$url = $_POST['url'];

	$description = $_POST['description'];



	

	$table_name = $wpdb->prefix . 'avsliderman';

	

	$wpdb->insert( 

		$table_name, 

		array( 

			'time' => current_time( 'mysql' ), 

			'name' => $name,

			'position'=> $order, 

			'url'  => $url,

			'description' => $description,

		) 

	);





       

?>

<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' );?></strong></p></div>

<?php



    }

	

	

	if( isset($_POST[ 'updateavsubmit' ]) ) {



			

	$name = $_POST['name'];

	$order = $_POST['order'];

	$url = $_POST['url'];

	$description = $_POST['description'];



	

	$table_name = $wpdb->prefix . 'avsliderman';

	$wpdb->update( 

	$table_name,  

	array( 

		'time' => current_time( 'mysql' ), 

			'name' => $name,

			'position'=> $order, 

			'url'  => $url,

			'description' => $description, 

	), 

	array( 'id' => $_GET['id'] )

	

);

	

	





        

?>

<div class="updated"><p><strong><?php _e('settings Updated.', 'menu-test' );?></strong></p></div>

<?php



    }



    



    echo '<div class="wrap">';



    

    echo "<h2>" . __( 'AV Slider Setting', 'menu-test' ) . "</h2>";



  

    

   ?>







<form name="form1" method="post" action="">





<p><?php _e("Name:", 'menu-test' ); ?> 

<input type="text" name="name" value="<?php echo $editpost[0]->name !=''? $editpost[0]->name : ''; ?>" size="20"></p>

<p><?php _e("URL:", 'menu-test' ); ?> 

<input type="text" name="url" value="<?php echo $editpost[0]->url !=''? $editpost[0]->url : ''; ?>" size="20"></p>

<p><?php _e("ORDER:", 'menu-test' ); ?> 

<input type="text" name="order" value="<?php echo $editpost[0]->position !=''? $editpost[0]->position : ''; ?>" size="20"></p>

<p><?php _e("Description:", 'menu-test' ); ?> 

<input type="text" name="description" value="<?php echo $editpost[0]->description !=''? $editpost[0]->description : ''; ?>"size="20"></p>

<hr />



<p class="submit">

<input type="submit" name="<?php echo $editpost[0]->id !=''? 'updateavsubmit' : 'avsubmit'; ?>" class="button-primary" value="<?php echo $editpost[0]->id !=''? esc_attr_e('Update Changes') : esc_attr_e('Save Changes'); ?>" />

</p>



</form>

</div>

<?php }

// mt_sublevel_page2() displays the page content for the second submenu

// of the custom Test Toplevel menu

 function mt_sublevel_page2() { ?>



<?php

 if (!current_user_can('manage_options'))

    {

      wp_die( __('You do not have sufficient permissions to access this page.') );

    } 

    

	

	 global $wpdb;





    // See if the user has posted us some information

    // If they did, this hidden field will be set to 'Y'

    if( isset($_POST[ 'avsubmit1' ]) ) {



			

	$width = $_POST['width'];

	$height = $_POST['height'];

	$deschs = $_POST['deshs'];

	



	

	$table_name1 = $wpdb->prefix . 'avslidersetting';

	

	$wpdb->insert( 

		$table_name1, 

		array( 

			'time' => current_time( 'mysql' ), 

			'width' => $width,

			'height'=> $height, 

			'descriptionhs'  => $deschs,

			

		) 

	);





        	

	

?>



<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>

<?php



    } 

	

	

	if( isset($_POST[ 'updateavsubmit1' ]) ) {



			

	$width = $_POST['width'];

	$height = $_POST['height'];

	$deshs = $_POST['deshs'];



	

	$table_name = $wpdb->prefix . 'avslidersetting';

	$wpdb->update( 

	$table_name,  

	array( 

		'time' => current_time( 'mysql' ), 

			'width' => $width,

			'height'=> $height, 

			'descriptionhs' => $deshs,

	), 

	array( 'id' =>1 )

	

);

	?>

<div class="updated"><p><strong><?php _e('settings Updated.', 'menu-test' ); ?></strong></p></div>

<?php



    }

	

	

	 echo '<div class="wrap">';



   



    echo "<h2>" . __( 'Manage slider', 'menu-test' ) . "</h2>";

	

	

	

	$cid = $_GET['id'];

$table_name = $wpdb->prefix . 'avslidersetting';

 $querystr = "

    SELECT ".$wpdb->prefix."avslidersetting.* 

    FROM $table_name

	";



 $setting = $wpdb->get_results($querystr, OBJECT);

	

	?>

    

	

	

	

	<form name="form2" method="post" action="">





<p><?php _e("Width:", 'menu-test' ); ?> 

<input type="text" name="width" value="<?php echo $setting[0]->width !=''? $setting[0]->width : ''; ?>" size="20"></p>

<p><?php _e("Height:", 'menu-test' ); ?> 

<input type="text" name="height" value="<?php echo $setting[0]->height !=''? $setting[0]->height : ''; ?>" size="20"></p>

<p><?php _e("Desription Hide/Show:", 'menu-test' ); ?> 

<input type="checkbox" name="deshs" value="1" <?php echo $setting[0]->descriptionhs ==0? '' : 'checked="checked"'; ?>> Check for show</p>



<hr />



<p class="submit">

<input type="submit" name="<?php echo $setting[0]->id !=''? 'updateavsubmit1' : 'avsubmit1'; ?>" class="button-primary" value="<?php echo $setting[0]->id !=''? esc_attr_e('Update Changes') : esc_attr_e('Save Changes'); ?>" />

</p>



</form>



<div>

<h3>How To Use:</h3>

<p>Use shortcode [avslider] in post and page. </p>



</div>



	

<?php }



function avslider_scripts() {

	wp_enqueue_style( 'style-name', plugins_url('/css/avslider.css', __FILE__),'','1.0.0' );

	wp_enqueue_script( 'script-name', plugins_url('/js/avslider.js', __FILE__), array(), '1.0.0', true );

	



}

  add_action( 'wp_enqueue_scripts', 'avslider_scripts' );  

  





function create_avslider(){

global $wpdb;







$table_namesetting = $wpdb->prefix . 'avslidersetting';

 $querystrsetting = "

    SELECT * 

    FROM $table_namesetting   

    

	

 ";

$resultpostsetting = $wpdb->get_results($querystrsetting, OBJECT);



$table_name = $wpdb->prefix . 'avsliderman';

 $querystr = "

    SELECT ".$wpdb->prefix."avsliderman.* 

    FROM $table_name   

    ORDER BY ".$wpdb->prefix."avsliderman.position ASC

	

 ";

$resultposts = $wpdb->get_results($querystr, OBJECT);



   ?>

<script type="text/javascript">



jQuery(document).ready(function(){

jQuery('#avrotate').cycle({ 

    fx:     'fade', 

    timeout: 6000,

    pager:  '#avbannernav',

    pagerEvent: 'click',

    pauseOnPagerHover: 1,

        pagerAnchorBuilder: function(idx, slide) {

        return '<li><a href="#">&nbsp;</a></li>';

        }

    });

 

});

</script>

  

 <div class="banner_wrap">

	<section id="avbanner">

	<div id="avrotate" style="position: relative; width: <?php echo $resultpostsetting[0]->width;?>px;  height:<?php echo $resultpostsetting[0]->height;?>px;">

         <?php foreach ($resultposts as $slides){ ?>   

            <div id="avbanner<?php echo $slides->id;?>" class="slide" style="position: absolute; top: 0px; left: 0px; z-index: 1; opacity: 0; display: none; width:100%; height: 100%;">

                <img src="<?php echo $slides->url;?>" style="height:100%; width:100%;" />

				  

                <?php if($resultpostsetting[0]->descriptionhs ==1){ ?> <h3><?php echo $slides->description; ?></h3> <?php  } ?>

				

            </div>

         <?php } ?>

	</div>

	<ul id="avbannernav"></ul>

	

</section>

 </div>

<?php 

}







add_shortcode( 'avslider' , 'create_avslider' );



function WCM_Setup_Demo_on_deactivation()

{



global $wpdb;

    if ( ! current_user_can( 'activate_plugins' ) )

        return;

    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';

    check_admin_referer( "deactivate-plugin_{$plugin}" );

	

	  

        $table1 = $wpdb->prefix."avsliderman";

		$table2 = $wpdb->prefix."avslidersetting";



        //Delete any options thats stored also?

	//delete_option('wp_yourplugin_version');



	$wpdb->query("DROP TABLE IF EXISTS $table1");

	$wpdb->query("DROP TABLE IF EXISTS $table2");

    

}

function WCM_Setup_Demo_on_uninstall()

{

    if ( ! current_user_can( 'activate_plugins' ) )

        return;

    check_admin_referer( 'bulk-plugins' );



    // Important: Check if the file is the one

    // that was registered during the uninstall hook.

    if ( __FILE__ != WP_UNINSTALL_PLUGIN )

        return;



}

register_deactivation_hook( __FILE__, 'WCM_Setup_Demo_on_deactivation' );

register_uninstall_hook(    __FILE__, 'WCM_Setup_Demo_on_uninstall' );








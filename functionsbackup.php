<?php
@ini_set( 'upload_max_size' , '512M' );
@ini_set( 'post_max_size', '512M');
@ini_set( 'max_execution_time', '300' );


add_filter( 'auto_update_plugin', '__return_false' );
add_filter( 'auto_update_theme', '__return_false' );

define('CHILD_THEME_MAGICRETREATS_VERSION','1.0');
add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles', 100);

function salient_child_enqueue_styles() {
		
	$nectar_theme_version = nectar_get_theme_version();
	wp_enqueue_style( 'salient-child-style', get_stylesheet_directory_uri() . '/style.css', '', $nectar_theme_version );

    /*wp_enqueue_style( 'ds-owlslider-css', 'https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css',array(), $nectar_theme_version );

    wp_enqueue_script( 'ds-owlslider-js', 'https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js', array('jquery'), '', true );

    wp_register_script( 'ds-custom-script-js', get_stylesheet_directory_uri().'/assets/js/custom-script.js', array('jquery'), wp_get_theme()->get( 'Version' ),true  );
    wp_localize_script( 'ds-custom-script-js', 'ajax_obj', 
        array(  
            'ajax_url' => admin_url( "admin-ajax.php" ),
            'site_url' => site_url(),
            'theme_url' => get_stylesheet_directory_uri(),
        )
    );
    wp_enqueue_script( 'ds-custom-script-js');*/

   
}

include_once('time-gem_post_meta_data.php'); // 'post_type' => 'time-gem',
include_once('img-upload-time-gem.php'); 


add_filter ('add_to_cart_redirect', 'redirect_to_checkout');
function redirect_to_checkout() {
    global $woocommerce;
    $checkout_url = $woocommerce->cart->get_checkout_url();
    return $checkout_url;
}


function has_active_subscription( $user_id='' ) {
    
    if( '' == $user_id && is_user_logged_in() ) 
        $user_id = get_current_user_id();

    if( $user_id == 0 ) 
        return false;

    $status=wcs_user_has_subscription( $user_id, '', 'active' );

    if($status){
        return true;
    }else{
        $lifetime_access=get_user_meta($user_id,'lifetime_access',true);
        if($lifetime_access==1){
            return true;
        }else{
            return false;
        }
    }
}

add_action( 'woocommerce_order_status_changed', 'single_giveaway_entry_pre_complete',10,3 );

function single_giveaway_entry_pre_complete( $order_id, $old_order_status, $new_order_status ) {
    if($new_order_status=='completed' || $new_order_status=='processing'){
        $order = wc_get_order( $order_id );
        $user_id = $order->get_user_id();
        $order_items = $order->get_items();
        foreach( $order_items as $item_id => $order_item ) {
            $product_id=$order_item->get_product_id(); 
            if(($product_id==321) || ($product_id==1789)){
                update_user_meta($user_id,'lifetime_access',1);
            }
        }
    }
}


// my account menu
add_filter( 'woocommerce_account_menu_items', function($items) {
    
    $new_items=array();
    foreach($items as $key=>$item){
        $new_items[$key]=$item;
        if($key=='orders'){
            $new_items['mytimegem']=__('My Time Gem', 'woocommerce');
            $new_items['my-timegem-tributes']=__('Tributes', 'woocommerce');
            $new_items['plaque']=__('Replacement Plaque', 'woocommerce');

        }
    }
    return $new_items;
}, 99, 1 );


add_action( 'init', function() {
    add_rewrite_endpoint( 'mytimegem', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'my-timegem-tributes', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'plaque', EP_ROOT | EP_PAGES );
    // Repeat above line for more items ...
});

add_action( 'woocommerce_account_mytimegem_endpoint', function() {
    wc_get_template_part('myaccount/mytimegem');
});

add_action( 'woocommerce_account_my-timegem-tributes_endpoint', function() {
    wc_get_template_part('myaccount/my-timegem-tributes');
});

add_action( 'woocommerce_account_plaque_endpoint', function() {
    //echo 'here is the functionality';
    wc_get_template_part('myaccount/my-timegem-plaque'); 
});

add_shortcode( 'create-time-gem', function(){
    ob_start();
    include('page-create-timegem.php');
    return ob_get_clean();
});
add_shortcode( 'update-time-gem', function(){
    
    ob_start();
    include('page-update-timegem.php');
    return ob_get_clean();
});
add_shortcode( 'public-post', function(){
    ob_start();
    include('public-time-gem.php');
    return ob_get_clean();
});


add_filter( 'ajax_query_attachments_args', 'show_current_user_attachments', 10, 1 );

function show_current_user_attachments( $query = array() ) {
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user(); 
        $user_id = get_current_user_id();
        if( $user_id ) {
            $allowed_roles = array('subscriber', 'customer');
            if ( array_intersect($allowed_roles, $user->roles )){
                $query['author'] = $user_id;
            }
        }
    }
    return $query;
}

// secure signon cookie
/*add_filter('secure_signon_cookie',function($secure_cookie, $credentials){
    return true;
},10,2);*/

add_action('admin_init', 'allow_subscriber_customer_upload_files');
add_action('init', 'allow_subscriber_customer_upload_files');
function allow_subscriber_customer_upload_files() {
    $customer = get_role('customer');
    $customer->add_cap('upload_files');

    $subscriber = get_role('subscriber');
    $subscriber->add_cap('upload_files');
}

add_action('wp_footer',function(){
    $caps = get_role( 'customer' )->capabilities;
    //print_r($caps);
});

// create time gem
function woox_validate_time_gem_post_data($data){
    $title=(isset($data['title'])?trim($data['title']):'');
    $story=(isset($data['story'])?trim($data['story']):'');
    $date_of_birth=(isset($data['date_of_birth'])?trim($data['date_of_birth']):'');
    $passing_date=(isset($data['passing_date'])?trim($data['passing_date']):'');

}


// send notification to admin for approving time gem
function send_time_gem_created_notification_to_admin($ID,$author){
    $edit_link=site_url().'/wp-admin/post.php?post='.$ID.'&action=edit';//get_edit_post_link($ID);
    $author=get_user_by('id', $author);
    $subject='A Time Gem waiting for approval!';
    $body='Hi!<br/><br/>'.$author->display_name.' has created a time gem.<br/><br/>Please review this time gem and approve it.<br/><br/>
    <a href="'.$edit_link.'">Click here</a><br/><br/>'.$edit_link.'<br/><br/><br/>Time Gem';

    $to = get_option( 'admin_email' );
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail( $to, $subject, $body, $headers );
}


// send mail to time gem author once the time gem is published
add_action('publish_time-gem',function($post_id,$post){
    $author = $post->post_author; /* Post author ID. */
    $name = get_the_author_meta( 'display_name', $author );
    $email = get_the_author_meta( 'user_email', $author );
    $title = $post->post_title;
    $permalink = get_permalink( $post_id );
    $to[] = sprintf( '%s <%s>', $name, $email );
    $subject = 'Your Time Gem Profile Has Been Approved';

    $message='<img src="'.get_site_url().'/wp-content/uploads/2023/09/timegemnewlogo.png" style="width:300px;"><br/><br/>
    Dear '.$name.',<br/>
    We are pleased to inform you that your Time Gem profile (<a href="'.$permalink.'">'.$title.'</a>) has been successfully approved!<br/><br/>    
    You can now access all the features and benefits associated with your Time Gem membership.<br/><br/>    
    Thank you for choosing Time Gem, and we look forward to providing you with an exceptional experience.<br/><br/>    
    Best regards,<br/>
    Time Gem Team
    ';

    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail( $to, $subject, $message, $headers );

},10,2);

// update time gem

add_action( 'wp_ajax_update_my_time_gem', 'update_my_time_gem' );
add_action( 'wp_ajax_nopriv_update_my_time_gem', 'update_my_time_gem' );

function update_my_time_gem(){
    
        $ID=$_POST['id'];
        $author_id = get_post_field ('post_author', $ID);
        $author=get_current_user_id();

        if($author>0 && has_active_subscription() && $author==$author_id){
            $data=$_POST;
            $error=array();
            $title=(isset($data['title'])?trim($data['title']):'');
            $title=sanitize_text_field($title);
            if(!$title){
                $error[]='Full name is required.';
            }
            $story=(isset($data['story'])?trim($data['story']):'');
            $story=sanitize_text_field($story);
            if(!$story){
                $error[]='Life story / about is required.';
            }
            $date_of_birth=trim($data['date_of_birth']);
            $date_of_birth=sanitize_text_field($date_of_birth);
            if(!$date_of_birth){
                $error[]='Birth date is required.';
            }

            $passing_date=trim($data['passing_date']);
            $passing_date=sanitize_text_field($passing_date);
            if(!$passing_date){
                $error[]='Passing date is required.';
            }

            if(count($error)>0){
                $return=array(
                    'status'=>false,
                    'messages'=>$error
                );
            }else{
                $vc='[vc_row type="in_container" full_screen_row_position="middle" column_margin="default" column_direction="default" column_direction_tablet="default" column_direction_phone="default" scene_position="center" text_color="dark" text_align="left" row_border_radius="none" row_border_radius_applies="bg" overflow="visible" overlay_strength="0.3" gradient_direction="left_to_right" shape_divider_position="bottom" bg_image_animation="none"][vc_column column_padding="no-extra-padding" column_padding_tablet="inherit" column_padding_phone="inherit" column_padding_position="all" column_element_direction_desktop="default" column_element_spacing="default" desktop_text_alignment="default" tablet_text_alignment="default" phone_text_alignment="default" background_color_opacity="1" background_hover_color_opacity="1" column_backdrop_filter="none" column_shadow="none" column_border_radius="none" column_link_target="_self" column_position="default" gradient_direction="left_to_right" overlay_strength="0.3" width="1/1" tablet_width_inherit="default" animation_type="default" bg_image_animation="none" border_type="simple" column_border_width="none" column_border_style="solid"]
            [vc_gallery type="image_grid" images="" image_grid_loading="default" layout="fullwidth" masonry_style="true" bypass_image_cropping="true" item_spacing="3px" constrain_max_cols="true" gallery_style="7" load_in_animation="none" img_size="full"]
            [/vc_column][/vc_row]';
                $time_gem = array(
                    'ID'=>$ID,
                    'post_title' => $title,
                    'post_password'=>(isset($data['post_private_and_password'])?$data['post_password']:''),
                    'post_content'=> $vc
                );
                
                wp_update_post($time_gem);

                // if(isset($data['profile_image'])){
                //     update_post_meta($ID,'profile_image',sanitize_text_field($data['profile_image']));
                // }else{
                //     update_post_meta($ID,'profile_image','');
                // }
                // process post meta data
                update_post_meta($ID,'story',$story);
                update_post_meta($ID,'date_of_birth',$date_of_birth);
                update_post_meta($ID,'passing_date',$passing_date);
                // if(isset($data['attachments']) && count($data['attachments'])>0){
                //     update_post_meta($ID,'attachments',$data['attachments']);
                // }else{
                //     update_post_meta($ID,'attachments',[]);
                // }

                if(isset($data['youtube_video_url']) && count($data['youtube_video_url'])>0){
                    update_post_meta($ID,'youtube_video_url',$data['youtube_video_url']);
                }else{
                    update_post_meta($ID,'youtube_video_url',[]);
                }

                if(isset($data['vimio_video_url']) && count($data['vimio_video_url'])>0){
                    update_post_meta($ID,'vimio_video_url',$data['vimio_video_url']);
                }else{
                    update_post_meta($ID,'vimio_video_url',[]);
                }

                update_post_meta($ID,'question_1',sanitize_text_field($data['question_1']));
                update_post_meta($ID,'question_2',sanitize_text_field($data['question_2']));
                update_post_meta($ID,'question_3',sanitize_text_field($data['question_3']));
                update_post_meta($ID,'question_4',sanitize_text_field($data['question_4']));
                update_post_meta($ID,'question_5',sanitize_text_field($data['question_5']));
                update_post_meta($ID,'question_6',sanitize_text_field($data['question_6']));
                update_post_meta($ID,'question_7',sanitize_text_field($data['question_7']));
                update_post_meta($ID,'question_8',sanitize_text_field($data['question_8']));
                update_post_meta($ID,'question_9',sanitize_text_field($data['question_9']));
                update_post_meta($ID,'question_10',sanitize_text_field($data['question_10']));
                update_post_meta($ID,'question_11',sanitize_text_field($data['question_11']));
                
                update_post_meta($ID,'charity_link',sanitize_text_field($data['charity_link']));

                update_post_meta($ID,'chairty_question',sanitize_text_field($data['chairty_question']));

                // if(isset($data['charity_image'])){
                //     update_post_meta($ID,'charity_image',sanitize_text_field($data['charity_image']));
                // }else{
                //     update_post_meta($ID,'charity_image','');
                // }

                if(isset($data['social_media_link']) && count($data['social_media_link'])>0){
                    update_post_meta($ID,'social_media_link',$data['social_media_link']);
                }else{
                    update_post_meta($ID,'social_media_link',[]);
                }

                if(isset($data['social_icon']) && count($data['social_icon'])>0){
                    update_post_meta($ID,'social_icon',$data['social_icon']);
                }else{
                    update_post_meta($ID,'social_icon',[]);
                }

                if(isset($data['disable_tribute'])){
                    update_post_meta($ID,'disable_tribute',true);
                }else{
                    update_post_meta($ID,'disable_tribute',false);
                }


                $bg=$data['background-image'];

                $bg1= get_site_url().'/wp-content/uploads/2023/10/header_grass.jpg';
                $bg2= get_site_url().'/wp-content/uploads/2023/10/Header_white_flowers.jpg';
                $bg3= get_site_url().'/wp-content/uploads/2023/10/header_sky.jpg';
                $bg4= get_site_url().'/wp-content/uploads/2023/10/header_waterfall.jpg';
                $bg5= get_site_url().'/wp-content/uploads/2023/10/header_space.jpg';
                $bg6= get_site_url().'/wp-content/uploads/2023/10/header_ocean.jpg';
                $bg7= get_site_url().'/wp-content/uploads/2023/10/header_mountain.jpg';
                $bg8= get_site_url().'/wp-content/uploads/2023/10/header_pink_flowers.jpg';

                $own=true;
                switch ($bg) {
                    case $bg1:
                        $own=false;
                        break;
                    case $bg2:
                        $own=false;
                        break;
                    case $bg3:
                        $own=false;
                        break;
                    case $bg4:
                        $own=false;
                        break;
                    case $bg5:
                        $own=false;
                        break;
                    case $bg6:
                        $own=false;
                        break;
                    case $bg7:
                        $own=false;
                        break;
                    case $bg8:
                        $own=false;
                        break;
                
                }

                if(!$own){
                    $time_custom_bg_id=get_post_meta($ID,'time_custom_bg_id',true);
                    if($time_custom_bg_id>0){
                        wp_delete_attachment($time_custom_bg_id); 
                        update_post_meta($ID,'time_custom_bg_id', 0);
                    }
                }

                if(isset($data['background-image'])){
                    update_post_meta($ID,'time_gem_bg_image',sanitize_text_field($data['background-image']));
                }else{
                    update_post_meta($ID,'time_gem_bg_image','');
                }

                update_user_meta($author,'my_draft_time_gem',''); // clear draft data

                // send notification to admin

                $edit_link=get_edit_post_link($ID);
                $author=get_user_by('id', $author);
                $subject='A time gem waiting for approval!';
                $body='Hi!<br/><br/>'.$author->display_name.' has been updated a time gem.<br/>Please review this time gem and approve it.<br/><a href="'.$edit_link.'">Click here</a><br/><br/>'.$edit_link.'<br/><br/><br/>Time Gem';
                //$to = 'hello@wooxperto.com';
                $to = get_option( 'admin_email' );
                $headers = array('Content-Type: text/html; charset=UTF-8');
                //wp_mail( $to, $subject, $body, $headers );

                $return=array(
                    'status'=>true,
                    'messages'=>['Congratulations! Your Time Gem profile has been successfully updated.']
                );
               
            }
        }else{
            $return=array(
                'status'=>false,
                'messages'=>['Sorry! you are not authorized to create time gem.']
            );
        }
        wp_send_json($return);

   

    exit();
}

add_action( 'wp_ajax_delete_my_draft_time_gem', 'delete_my_draft_time_gem' );
add_action( 'wp_ajax_nopriv_delete_my_draft_time_gem', 'delete_my_draft_time_gem' );
function delete_my_draft_time_gem(){
    $author=get_current_user_id();
    if($author>0){
        update_user_meta($author,'my_draft_time_gem','');

        $return=array(
            'status'=>true,
            'messages'=>['Success! your draft time gem data has been successfully deleted.']
        );
        wp_send_json($return);
    }

    exit();
}

add_action('wp',function(){
    global $post;
	
	
	//die();
	  
	
	 $subscription_id= get_post_meta( $post->ID, 'subscription_id',true );
		 $check_subscription= get_post_meta( $post->ID, 'check_subscription',true );
    $current_post_status = get_post_status(  $subscription_id );
	$status=explode('wc-',$current_post_status );
	if(!is_admin() && $post->post_type=="time-gem" && $check_subscription=='yes' && $status[1]!='active')
	{
	//echo get_post_status( 1068);
        $redirect_url = get_site_url().'/notfound';
	   wp_safe_redirect($redirect_url);
        exit();

	}
    if($post->ID==302 && !has_active_subscription()){
        $redirect_url = get_site_url().'/pricing';
        wp_safe_redirect($redirect_url);
        exit();
    }

    if($post->ID==448 && !has_active_subscription()){
        $redirect_url = get_site_url().'/pricing';
        wp_safe_redirect($redirect_url);
        exit();
    }


    // delete tribute
    if(isset($_GET['selected']) && isset($_GET['action'])){
        $tributeId = base64_decode($_GET['selected']);
        $actionDel = $_GET['action'];

        $timeGemAughId = get_post_meta( $tributeId, 'time_gem_auth_id', true );
        $user_id = get_current_user_id();

        // echo $tributeId.' id '.$timeGemAughId;
        if(($user_id == $timeGemAughId) && $actionDel=='delete_tr'){
            // delete code here
            wp_delete_post($tributeId, true);
        }

    }


});



// add from Short code
add_shortcode('select-option-from','subscription_from_short_code_fun');
function subscription_from_short_code_fun($jekono){ 
    $result = shortcode_atts(array( 
        'pid' =>'',
    ),$jekono);
    extract($result);
    ob_start();
    ?>

    <div class="option-from-wrap">
        <div class="img-wrap">

            <div class="single-img">
                <label> 
                    <input checked type="radio" name="subscription-pack_<?php echo $pid;?>" class="input-hidden" value="<?php echo site_url(); ?>/wp-content/uploads/2023/09/whitegem.png" data-pimg="1" />
                    <img class="timegem-img" src="<?php echo site_url(); ?>/wp-content/uploads/2023/08/Whitegem100.png" alt="goldgem" />
                </label>
            </div>

            <div class="single-img">
                <label> 
                    <input type="radio" name="subscription-pack_<?php echo $pid;?>" class="input-hidden" value="<?php echo site_url(); ?>/wp-content/uploads/2023/09/blackgem.png" data-pimg="2" />
                    <img class="timegem-img" src="<?php echo site_url(); ?>/wp-content/uploads/2023/08/blackgem100.png" alt="blackgem" />
                </label>
            </div>

            <div class="single-img">
                <label>
                    <input type="radio" name="subscription-pack_<?php echo $pid;?>" class="input-hidden" value="<?php echo site_url(); ?>/wp-content/uploads/2023/10/Bronze.png" data-pimg="3" />
                    <img class="timegem-img" src="<?php echo site_url(); ?>/wp-content/uploads/2023/10/Bronze.png" alt="whitegem" />
                </label>
            </div>

        </div>

        <label>
            <input type="checkbox" name="need-hole" value="yes"  onchange="need_hole(this)"> Click For Holes To Mount Your Time Gem
        </label>
        <input type="hidden" name="pid" value="<?php echo $pid; ?>">
        <p id="sms_<?php echo $pid; ?>"></p>
    </div>
    
    <?php
    return ob_get_clean();
}

add_action('wp_footer', 'subscription_from_script');
function subscription_from_script(){

    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $meta = get_user_meta($user_id,'wcpay_currency',true);
        ?>
        <script type="text/javascript">
            var currency = "<?php echo ucwords($meta)?>";
            console.log('currency '+currency)
            if (currency) {
                //jQuery('#wcc-switcher-style-01 ul.wcc-list li[data-code="'+currency+'"]').trigger('click');
            }
            
        </script>
        <?php
    }
?>
<script>
    

    function need_hole(data){

        let siteUrl = '<?php echo site_url(); ?>';
        let img1  = siteUrl+'/wp-content/uploads/2023/08/Whitegem100.png';
        let img1h = siteUrl+'/wp-content/uploads/2023/10/hw-1.jpg';

        let img2  = siteUrl+'/wp-content/uploads/2023/08/blackgem100.png';
        let img2h = siteUrl+'/wp-content/uploads/2023/10/hb-1.jpg';
        
        let img3  = siteUrl+'/wp-content/uploads/2023/10/Bronze.png';
        let img3h = siteUrl+'/wp-content/uploads/2023/10/Bronze-ho.png';
        // values
        let img1v  = siteUrl+'/wp-content/uploads/2023/09/whitegem.png';
        let img1hv = siteUrl+'/wp-content/uploads/2023/10/IMG_8550-min.jpg';

        let img2v = siteUrl+'/wp-content/uploads/2023/09/blackgem.png';
        let img2hv = siteUrl+'/wp-content/uploads/2023/10/IMG_8554-min.jpg';

        let img3v  = siteUrl+'/wp-content/uploads/2023/09/goldgem.png';
        let img3hv = siteUrl+'/wp-content/uploads/2023/12/gold-hole.jpg';

        let withOutWholeImages=[
            img1,
            img2,
            img3
        ];

        let withWholeImages=[
            img1h,
            img2h,
            img3h
        ];

        let withOutWholeImagesV=[
            img1v,
            img2v,
            img3v
        ];

        let withWholeImagesV=[
            img1hv,
            img2hv,
            img3hv
        ];




        let parent = jQuery(data).parent().parent();

        let checked = false;
        if(jQuery(parent).find('input[name=need-hole]').is(':checked')) checked = true;

        if(checked){
            jQuery(parent).find('input[type=radio]').each(function(i,element){
                jQuery(element).val(withWholeImagesV[i]);
                jQuery(element).siblings('img').attr('src',withWholeImages[i]);
            });
        }else{
            jQuery(parent).find('input[type=radio]').each(function(i,element){
                jQuery(element).val(withOutWholeImagesV[i]);
                jQuery(element).siblings('img').attr('src',withOutWholeImages[i]);
            });
        }

        
    }

    jQuery(document).on('click' , '.add-cart-fun' , function(e){
        e.preventDefault();
        //event
        let sFrom = jQuery(this).parent();
        let sFromPid  = jQuery(sFrom).find('input[name=pid]').val();
        let sFromImg  = jQuery(sFrom).find('input[name=subscription-pack_'+sFromPid+']:checked').val();
        let sFromHole = jQuery(sFrom).find('input[name=need-hole]:checked').val();
        

        // WP Ajax Call with submit function
        jQuery('#sms_'+sFromPid).html(`<b>Please wait..</b> <span class='edit_loading'>&#10044;</spa> `);

        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo admin_url('admin-ajax.php')?>',
            data: {
                action: 'add_to_card_s_from',
                img: sFromImg,
                hole: sFromHole,
                pid: sFromPid
            },
            success: function(response) { 
                if ( ! response || response.error ) return;

                jQuery('#sms_'+sFromPid).html(` `);
                if (response.status == 'ok') { 
                    jQuery('#sms_'+sFromPid).html(`${response.message}`);
					// window.location.replace("<?php // echo wc_get_cart_url(); ?>");
					window.location.replace("<?php echo wc_get_checkout_url(); ?>");

                } else { 
                    jQuery('#sms_'+sFromPid).html(`<p class='error'>Something went wrong!</p>`);
                }
            
            }
        });

        
    });

    jQuery(document).on('click' , '#update-slug-btn' , function(){

        let timeGemSlug = jQuery('input[name=slug]').val();
        let timeGemId   = jQuery('#update-time-gem-form').find('input[name=id]').val();

        jQuery('#update-slug-btn').html(`Wait <span class='edit_loding'>&#10044;</spa>`);

        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo admin_url('admin-ajax.php')?>',
            data: {
                action: 'time_gem_slug_update',
                slug: timeGemSlug,
                id: timeGemId
            },
            success: function(response) {

                if ( ! response || response.error ) return;
                jQuery('#update-slug-sms').html(` `);

                if (response.status == 'ok') { 
                    // jQuery('#update-slug-sms').html(`${response.message}`);
                    jQuery('input[name=slug]').val(response.message);
                    jQuery('#update-slug-btn').html(`Ok`);
                } else { 
                    jQuery('#update-slug-sms').html(`<p class='error'>Some problam</p>`);
                }
            
            }
        });

    });
    
</script>
<?php 
}

// ajax process add_to_card_s_from
function add_to_card_s_from() {
    if(isset($_POST['hole'])){
        $hole = sanitize_text_field($_POST['hole']);
    }else{
        $hole = 'No';
    }
    $pid = sanitize_text_field($_POST['pid']);
    $img = $_POST['img'];
    if (sizeof( WC()->cart->get_cart() ) > 0 ) { 
        WC()->cart->empty_cart();
    }
    $s_from_add_to_cart_meta=array(
        's_from_hole'=>$hole,
        's_from_img'=>$img
    );

    WC()->cart->add_to_cart( $pid,1,0,array(),$s_from_add_to_cart_meta);

    $sms = 'Added to the cart!';
    echo json_encode(['status'=>'ok', 'message' => $sms ]);

    exit(); // wp_die();
}
add_action('wp_ajax_add_to_card_s_from', 'add_to_card_s_from');
add_action('wp_ajax_nopriv_add_to_card_s_from', 'add_to_card_s_from');


/**
 * Display custom item data in the cart
 */
add_filter( 'woocommerce_get_item_data', 's_from_data', 10, 2 );
function s_from_data( $item_data, $cart_item_data ) {
    if ( isset( $cart_item_data['s_from_hole'] ) ) {
        $item_data[] = array(
            'key'   => __( 'Holes Needed', 'webkul' ),
            'value' => wc_clean( $cart_item_data['s_from_hole'] ),
        );
    }
    if ( isset( $cart_item_data['plaque_timegem_id'] ) ) {
        $item_data[] = array(
            'key'   => __( 'My Time Gem Id', 'webkul' ),
            'value' => wc_clean( $cart_item_data['plaque_timegem_id'] ),
        );
    }

    if ( isset( $cart_item_data['plaque_order_id'] ) ) {
        $item_data[] = array(
            'key'   => __( 'Order Id', 'webkul' ),
            'value' => wc_clean( $cart_item_data['plaque_order_id'] ),
        );
    }

    return $item_data;
}

// Display custom item imgae in the cart
add_filter('woocommerce_in_cart_product_thumbnail', 'subscription_from_thumbnail', 99, 3);
add_filter('woocommerce_cart_item_thumbnail', 'subscription_from_thumbnail', 99, 3);
function subscription_from_thumbnail($product_image, $cart_item, $cart_item_key) {
    //Ds Added
    $switch_subs = WC()->session->get( 'switch_subs' );
    $ds_product_thumbnail = WC()->session->get( 'ds_product_thumbnail' );
    if (!empty($switch_subs) && (!empty($ds_product_thumbnail))) {
        $product_image='<img src="'.$ds_product_thumbnail.'" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy" width="300" height="300">'; 
    }
    //Ds Ended
    if(isset($cart_item["s_from_img"])) {
        $product_image='<img src="'.$cart_item["s_from_img"].'" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy" width="300" height="300">';
    }
    return $product_image;
}



// send mail to supplier
function send_mail_to_supplier($slug,$image_url,$hollow,$cust_details){
    $firstname=$cust_details['firstname'];
    $lastname=$cust_details['lastname'];
    $email=$cust_details['email'];
    $phone=$cust_details['phone'];
    $address1=$cust_details['address1'];
    $address2=$cust_details['address2'];
    $country=$cust_details['country'];
    $city=$cust_details['city'];
    $state=$cust_details['state'];
    $zip=$cust_details['zip'];


    $white=site_url()."/wp-content/uploads/2023/09/whitegem.png";
    $black=site_url()."/wp-content/uploads/2023/09/blackgem.png";
    $brown=site_url()."/wp-content/uploads/2023/10/Bronze.png";

    $white1=site_url()."/wp-content/uploads/2023/10/IMG_8550-min.jpg";
    $black1=site_url()."/wp-content/uploads/2023/10/IMG_8554-min.jpg";
    $brown1=site_url()."/wp-content/uploads/2023/10/IMG_8552-min.jpg";

    $text="";

    switch ($image_url) {
        case $white:
            $text="White Plaque Without Holes";
            break;
        case $black:
            $text="Black Plaque Without Holes";
            break;
        case $brown:
            $text="Bronze Plaque Without Holes";
            break;
        case $white1:
            $text="White Plaque With Holes";
            break;
        case $black1:
            $text="Black Plaque With Holes";
            break;
        case $brown1:
            $text="Bronze Plaque Without Holes";
            break;
        
    }

    $url = get_site_url().'/time-gem/'.$slug;    
    $qr_url=get_stylesheet_directory_uri().'/qr.php?link='.$url;
    $subject='A Metal Alloy Plaque with this newly generated QR Code has been requested';
    $body='Hi,<br/>A Metal Alloy Plaque with this newly generated QR Code has been requested.<br/><strong>Plaque Option:</strong> '.$text.'<br/><strong>This is the QR Code:</strong><br/><img src="'.$qr_url.'" alt="QR code" style="max-width:300px;width:100%;">
    <br/>Click <a href="'.$qr_url.'" download>here</a> to download QR code.
    <br/>Below is customer details:<br/><strong>Name:</strong> '.$firstname.' '.$lastname.'<br/><strong>Email:</strong> '.$email.'<br/><strong>Phone:</strong> '.$phone.'<br/><strong>Address1:</strong> '.$address1.'<br/><strong>Address2:</strong> '.$address2.'<br/><strong>Country:</strong> '.$country.'<br/><strong>State:</strong> '.$state.'<br/><strong>City:</strong> '.$city.'<br/><strong>Zip:</strong> '.$zip.'<br/> <br/>Regards-<br/>The Time Gem';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    //wp_mail( 'qr@thetimegem.com,qrthetimegem@yopmail.com', $subject, $body, $headers );
    wp_mail( 'qr@thetimegem.com', $subject, $body, $headers );
}

add_action( 'woocommerce_thankyou', 'wooxperto_redirectcustom');
function wooxperto_redirectcustom( $order_id ){
   
    $order = wc_get_order( $order_id );

    $subscription_switch = $order->get_meta('_subscription_switch');
    if (!empty($subscription_switch)) {
        $redurl = get_site_url().'/my-account/subscriptions';
        wp_safe_redirect( $redurl );
        exit();
    }else{
        
        $is_plaque_purchase = false;
        $plaque_timegem_id = '';
        //$allowed_palque_variations = array(1331,1332,1333,1334);
        $allowed_palque_variations = array(1790,1791,1792,1793);



        $url = '';
        $orderStatus = $order->get_status();
        $timeGemAuth = $order->get_user_id();

        $itemsArray = [];
        $order_items = $order->get_items();
        $photo_url='';
        $hollow='';
        
        $timeGemItems = array(1516,1517,1518,1789);

        $timeGemItem = 0;
        foreach( $order_items as $item_id => $order_item ) {
            $product_id=$order_item->get_product_id();
            $itemsArray[]= $product_id;
            if(in_array($product_id,$timeGemItems)){
                $photo_url=wc_get_order_item_meta($item_id,'photo_url',true);
                $hollow=wc_get_order_item_meta($item_id,'Holes Needed',true);
                $timeGemItem=$item_id;
            }else if(in_array($product_id,$allowed_palque_variations)){
                $photo_url=wc_get_order_item_meta($item_id,'photo_url',true);
                $hollow=wc_get_order_item_meta($item_id,'Holes Needed',true);
                $plaque_timegem_id=wc_get_order_item_meta($item_id,'plaque_timegem_id',true);
                $is_plaque_purchase = true;
            }
        }
        $create_time_gem=false;
        $check_subscription='no';
        //echo '<pre>';print_r($itemsArray);die;
        if(in_array(1516,$itemsArray)){
            $create_time_gem=true;
            $check_subscription='yes';
        }
        //DS Ended

        if(($order->has_status( 'processing' ) || $order->has_status( 'completed' ))){
            if($create_time_gem){
                $subscriptions_ids = wcs_get_subscriptions_for_order( $order_id, array( 'order_type' => 'any' ) );
                // We get all related subscriptions for this order
                $subs_id=0;
                foreach( $subscriptions_ids as $subscription_id => $subscription_obj ){
                    if($subscription_obj->order->id == $order_id){
                        $subs_id=$subscription_id;
                        break;
                    }
                }

                $time_gem_exist = get_post_meta( $order_id, 'time_gem_id', true );
                if($time_gem_exist>0){

                    $encodeTimeGemId = base64_encode($time_gem_exist);
                    $url =  site_url().'/update-my-time-gem/?selected='.$encodeTimeGemId.'&action=edit';
                    wp_safe_redirect( $url );
                    exit();

                }else{
                    $vc='[vc_row type="in_container" full_screen_row_position="middle" column_margin="default" column_direction="default" column_direction_tablet="default" column_direction_phone="default" scene_position="center" text_color="dark" text_align="left" row_border_radius="none" row_border_radius_applies="bg" overflow="visible" overlay_strength="0.3" gradient_direction="left_to_right" shape_divider_position="bottom" bg_image_animation="none"][vc_column column_padding="no-extra-padding" column_padding_tablet="inherit" column_padding_phone="inherit" column_padding_position="all" column_element_direction_desktop="default" column_element_spacing="default" desktop_text_alignment="default" tablet_text_alignment="default" phone_text_alignment="default" background_color_opacity="1" background_hover_color_opacity="1" column_backdrop_filter="none" column_shadow="none" column_border_radius="none" column_link_target="_self" column_position="default" gradient_direction="left_to_right" overlay_strength="0.3" width="1/1" tablet_width_inherit="default" animation_type="default" bg_image_animation="none" border_type="simple" column_border_width="none" column_border_style="solid"]
                    [vc_gallery type="image_grid" images="" image_grid_loading="default" layout="fullwidth" masonry_style="true" bypass_image_cropping="true" item_spacing="3px" constrain_max_cols="true" gallery_style="7" load_in_animation="none" img_size="full"]
                    [/vc_column][/vc_row]';
                    $time_gem_slug = $order_id.uniqid(); // 'time-gem-'.$order_id;
                    $new_time_gem = array(
                        'post_type'     => 'time-gem',  
                        'post_title'    => 'Time gem - '.$order_id,
                        'post_status'   => 'draft', // draft | pending | publish
                        'post_author'   => $timeGemAuth,    // Post Author ID
                        'post_name'     => $time_gem_slug,  // Slug of the Post
                        'post_content'  => $vc
                    );
                    $new_time_gem_id = wp_insert_post($new_time_gem);
                    update_post_meta( $order_id, 'time_gem_id', $new_time_gem_id );
                    update_post_meta( $new_time_gem_id, 'time_gem_order_id', $order_id );
                    if ((!empty($photo_url)) && (isset($photo_url[0]))) {
                       update_post_meta( $new_time_gem_id, 'plaque_photo_url', $photo_url[0]);
                    }else{
                       update_post_meta( $new_time_gem_id, 'plaque_photo_url', $photo_url); 
                    }
                    update_post_meta( $new_time_gem_id, 'check_subscription', $check_subscription );
                    update_post_meta( $new_time_gem_id, 'subscription_id', $subs_id );

                    send_time_gem_created_notification_to_admin($new_time_gem_id,$timeGemAuth);
                    // send mail to supplier
                    $cust_details=array();
                    $country=WC()->countries->countries[ $order->get_billing_country() ];

                    $cust_details['firstname']=$order->get_billing_first_name();
                    $cust_details['lastname']=$order->get_billing_last_name();
                    $cust_details['email']=$order->get_billing_email();
                    $cust_details['phone']=$order->get_billing_phone();
                    $cust_details['address1']=$order->get_billing_address_1();
                    $cust_details['address2']=$order->get_billing_address_2();
                    $cust_details['country']=$country;
                    $cust_details['city']=$order->get_billing_city();
                    $cust_details['state']=$order->get_billing_state();
                    $cust_details['zip']=$order->get_billing_postcode();
                    //comment out 09-11-2023
                    send_mail_to_supplier($time_gem_slug,$photo_url[0],$hollow,$cust_details);
                    //#comment out 09-11-2023
                    $encodeTimeGemId = base64_encode($new_time_gem_id);
                    $url =  site_url().'/update-my-time-gem/?selected='.$encodeTimeGemId.'&action=edit'; 

                    $emailBody='';
                    $emailBody.='<h2>Hi '.$order->get_billing_first_name().'</h2>';
                    $emailBody.='<p>Your time gem has been registered.</p>';
                    $emailBody.='<p>Click <a href="'.$url.'" target="_blank" rel="time gem link">here</a> to update your time gem.</p><br/><br/><strong>Thanks for using The Time Gem!</strong><br/><img src="'.get_site_url().'/wp-content/uploads/2023/09/timegemnewlogo.png">';
                    
                    $user = get_user_by( 'id', $timeGemAuth );
                    $billingEmail=$order->get_billing_email();
                    $to = $billingEmail?$billingEmail:$user->user_email;
                    $subject = 'Registered Time Gem!';
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    //wp_mail( $to, $subject, $emailBody, $headers );
                    $registration_mail_send=get_user_meta($timeGemAuth,'registration_mail_send',true);
                    if($registration_mail_send!=1){
                        $timegenurl =  site_url().'/pricing/'; 
                        $imagelink =  site_url().'/wp-content/uploads/2023/10/timegen-e1698303175561.jpg'; 
                        $subject='Registration Email!';
                        $body='<img src="'.get_site_url().'/wp-content/uploads/2023/09/timegemnewlogo.png" style="width:300px;"><br/><br/>We are dedicated to preserving the lives of your loved ones memories forever 🥰<br/>
                        Thank you for registering your account with Time Gem!<br/>
                        To get a Time Gem for your loved one, please <a href="'.$timegenurl.'">Click here</a><br/>
                        <img src="'.$imagelink.'" width="100"><br/><br/><strong>Thanks for using The Time Gem!</strong>';

                        $headers = array('Content-Type: text/html; charset=UTF-8');
                        wp_mail( $to, $subject, $body, $headers );
                        update_user_meta($timeGemAuth,'registration_mail_send',1);
                    }
                    wp_safe_redirect( $url );
                    exit();
                }
            }else if ($is_plaque_purchase) {
                $plaque_photo_url = $photo_url;
                if ((!empty($photo_url)) && (isset($photo_url[0]))) {
                    $plaque_photo_url = $photo_url[0];
                    update_post_meta( $plaque_timegem_id, 'plaque_photo_url', $photo_url[0]);
                }
                update_post_meta( $plaque_timegem_id, 'plaque_photo_url', $plaque_photo_url);


                $timegem_post = get_post($plaque_timegem_id); 
                $timegem_post_slug = $timegem_post->post_name;

                // send mail to supplier
                $cust_details=array();
                $country=WC()->countries->countries[ $order->get_billing_country() ];

                $cust_details['firstname']=$order->get_billing_first_name();
                $cust_details['lastname']=$order->get_billing_last_name();
                $cust_details['email']=$order->get_billing_email();
                $cust_details['phone']=$order->get_billing_phone();
                $cust_details['address1']=$order->get_billing_address_1();
                $cust_details['address2']=$order->get_billing_address_2();
                $cust_details['country']=$country;
                $cust_details['city']=$order->get_billing_city();
                $cust_details['state']=$order->get_billing_state();
                $cust_details['zip']=$order->get_billing_postcode();
                //comment out 09-11-2023
                send_mail_to_supplier($timegem_post_slug,$plaque_photo_url,$hollow,$cust_details);
                //#comment out 09-11-2023
                $url = get_site_url().'/my-account/orders?hollow='.$hollow;
                wp_safe_redirect( $url );
                exit();
            }

        }
    }
}

add_action('wp_footer',function(){
    if(isset($_GET['gmail'])){
        $timegenurl =  site_url().'/pricing/'; 
        $imagelink =  site_url().'/wp-content/uploads/2023/11/time_gem_275.jpg'; 
        $subject='Registration Email!';
        $body='<div style="width:460px;"><img src="'.get_site_url().'/wp-content/uploads/2023/09/timegemnewlogo.png" style="width:300px;"><br/><br/>We are dedicated to preserving the lives of your loved ones memories forever 🥰<br/><br/>
        Thank you for registering your account with Time Gem!<br/><br/>
        To get a Time Gem for your loved one, please <a href="'.$timegenurl.'">Click here</a><br/><br/>
        <div style="width:100%;text-align:center;"><img src="'.$imagelink.'" style="margin:auto;"></div><br/><br/><strong>Thanks for using Time Gem!</strong></div>';

        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail('greensabuj350@gmail.com', $subject, $body, $headers );
    }
});
// ajax process for time gem slug update
function time_gem_slug_update() {

    $slugText = sanitize_text_field($_POST['slug']);
    $post_ID = sanitize_text_field($_POST['id']);
    
    list($permalink, $post_name) = get_sample_permalink( $post_ID, '', $slugText );
    wp_update_post([
        "post_name" => $post_name,
        "ID" => $post_ID,
    ]);
    
    echo json_encode(['status'=>'ok', 'message' => $post_name ]);

    exit(); // wp_die();
}
// add_action('wp_ajax_time_gem_slug_update', 'time_gem_slug_update');
// add_action('wp_ajax_nopriv_time_gem_slug_update', 'time_gem_slug_update');

// Display custom item imgae in the cart
add_filter('woocommerce_admin_order_item_thumbnail', 'wc_admin_order_item_img', 99, 3);
function wc_admin_order_item_img($thumbnail, $item_id, $item){

    $photo_url=wc_get_order_item_meta($item_id, 'photo_url',true);

    if(is_array($photo_url)){
        $custome_image=(isset($photo_url[0])?$photo_url[0]:'');
        if(!empty($custome_image)) $thumbnail='<img src="'.$custome_image.'" class="attachment-thumbnail size-thumbnail" alt="" loading="lazy" title="" width="150" height="150">';
    }
    
    
    return $thumbnail;
}

add_action('woocommerce_add_order_item_meta','wdm_add_values_to_order_item_meta',1,2);
function wdm_add_values_to_order_item_meta($item_id, $values){
    //Ds Added
    $switch_subs = WC()->session->get( 'switch_subs' );
    $ds_product_thumbnail = WC()->session->get( 'ds_product_thumbnail' );
    if (!empty($switch_subs) && (!empty($ds_product_thumbnail))) {
        wc_add_order_item_meta($item_id,'photo_url',array($ds_product_thumbnail)); 
    }
    if(isset($values['plaque_timegem_id']))
    { 
        wc_add_order_item_meta($item_id,'plaque_timegem_id',$values['plaque_timegem_id']);  
    }
    //Ds Ended
    if(isset($values['s_from_img']))
    { 
        wc_add_order_item_meta($item_id,'photo_url',array($values['s_from_img']));  
    }

    if(isset($values['s_from_hole']))
    { 
        wc_add_order_item_meta($item_id,'Holes Needed','yes');  
    }
}



add_action('wp_footer', 'tributes_script');
function tributes_script(){
?>
    <script>
    
    // submit tribute
    jQuery('#publish_tribute').on('click',function(){
        let time_gem_id  = jQuery('input[name=time_gem_id]').val().trim();
        let tribute_type = jQuery('input[name=tribute_type]:checked').val();
        let tribute_body = jQuery('textarea#tribute_body').val().trim();
        let author= jQuery('input[name=author_name]').val().trim();
        let pdate= jQuery('input[name=post_date]').val().trim();
        let author_type  = 1;

        // validation
        let isValid = true;
        if(!tribute_type){
            jQuery('#tribute_message').html(`<span class="error">Chose a tribute type!</span>`);
            return false;
            isValid = false;
        }
        if(!tribute_body){
            jQuery('#tribute_message').html(`<span class="error">Tribute text is empty!</span>`);
            return false;
            isValid = false;
        }
        if(!author){
            jQuery('#tribute_message').html(`<span class="error">Author name is missing!</span>`);
            return false;
            isValid = false;
        }
        if(!pdate){
            jQuery('#tribute_message').html(`<span class="error">Post date is missing!</span>`);
            return false;
            isValid = false;
        }


        // let emailId = jQuery('input[name=email]').val().trim();
        // let userName = jQuery('input[name=name]').val().trim();
        let img = "";
        if(tribute_type==1){
            img = "<?php echo get_stylesheet_directory_uri();?>/assets/give-flower.svg";
        } 
        if(tribute_type==2){
            img = "<?php echo get_stylesheet_directory_uri();?>/assets/give-hug-blue.svg";
        } 
        if(tribute_type==3){
            img = "<?php echo get_stylesheet_directory_uri();?>/assets/send-love-blue.svg";
        } 

        let newTributes = `

        `;
        
        
            
        // ajax Call 
        // let userName = jQuery('input[name=name]').val().trim();
        
        if(isValid){
            // WP Ajax Call with submit function
            jQuery('#tribute_message').html(`<b>Wait..</b> <span class='loding_spin'>&#10044;</spa> `);
            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?php echo admin_url('admin-ajax.php')?>',
                data: {
                    action: 'add_tributes',
                    tribute_type: tribute_type,
                    tribute_body: tribute_body,
                    author_type: author_type,
                    mdate: pdate,
                    time_gem_id: time_gem_id,
                    author: author
                },
                success: function(response) { 
                    if ( ! response || response.error ) return;
                    jQuery('#tribute_message').html(` `);
                    if (response.status == 'ok') { 
                        jQuery('#tribute_message').html(`<span style="color:green;">${response.message}</span>`);
                        // jQuery("#wrapTributes").append(newTributes);
                    } else { 
                        jQuery('#tribute_message').html(`<p class='error'>Some problam</p>`);
                    }
                
                }
            });
        }
        

    });

    function tributesStatus(id, data){

        let statusUpdate = jQuery(data).val(); 
        jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: '<?php echo admin_url('admin-ajax.php')?>',
        data: {
            action: 'tribute_status_update',
            id: id,
            status: statusUpdate
        },
        success: function(response) { }
        });
        
    }

    jQuery(document).ready(function() {
        function timegemShowPassField(checkbox) {
            let passWrap = jQuery(checkbox).closest('.main-switch').find('.timegem-pass-wrap');

            let isValid = false;
            if (checkbox.checked) {
                passWrap.show();
            } else {
                passWrap.hide();
                isValid = true;
            }

            let tid = jQuery(checkbox).attr("id");
            
            if(isValid){
                // ajax call
                jQuery.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo admin_url('admin-ajax.php')?>',
                    data: {
                        action: 'reset_timegem_pass',
                        id: tid,
                    },
                    success: function(response) { 
                        if ( ! response || response.error ) return;
                        // jQuery(data).html(`<span class="dashicons dashicons-saved"></span>`);

                        if (response.status == 'ok') { 
                            // jQuery(data).html(`${response.message}`);
                        } else { 
                            jQuery(data).html(`<p class='error'>Some problam</p>`);
                        }
                    
                    }
                });
            }

        }
        
        jQuery('.main-switch input[type="checkbox"]').change(function() {
            timegemShowPassField(this);
        });
    });

    // Ajax call onclick='timegemPass()'
    function timegemPass(id,data) {
        let setPassword = jQuery(data).parent().find('input[type=text]').val().trim();
        
        // validation 
        let isValid = true;
        jQuery(data).html(`...`);
        if(isValid){

            jQuery.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?php echo admin_url('admin-ajax.php')?>',
                data: {
                    action: 'set_timegem_pass',
                    id: id,
                    pass: setPassword
                },
                success: function(response) { 
                    if ( ! response || response.error ) return;
                    jQuery(data).html(`<span class="dashicons dashicons-saved"></span>`);

                    if (response.status == 'ok') { 
                        // jQuery(data).html(`${response.message}`);
                    } else { 
                        jQuery(data).html(`<p class='error'>Some problam</p>`);
                    }
                
                }
            });
        }
    }


    </script>
<?php 
}





// Add Tributes data ajax process
function add_tributes() {

    $tribute_type = sanitize_text_field($_POST['tribute_type']);
    $tribute_body = sanitize_text_field($_POST['tribute_body']);
    $author_type  = sanitize_text_field($_POST['author_type']);
    $mdate        = sanitize_text_field($_POST['mdate']);
    $author       = sanitize_text_field($_POST['author']);
    $time_gem_id  = sanitize_text_field($_POST['time_gem_id']);
    $disable_tribute=get_post_meta($time_gem_id,'disable_tribute',true);

    if($disable_tribute==true){
        $sms = 'Not authorized.';
        echo json_encode(['status'=>'not ok', 'message' => $sms ]);
        exit();
    }
    $user_ID = get_current_user_id();


    $timeGemAuthorId = get_post_field('post_author', $time_gem_id);

    // echo $tribute_body." post_type - ".$tribute_type." authoer - ".$author_type." Name - ".$author." date-".$mdate;
    // exit();

    $new_tribute = array(
        'post_title' => $author,
        'post_content' => $tribute_body,
        'post_status' => 'draft',  // draft | pending | publish
        'post_date' => $mdate, // date('Y-m-d H:i:s'),
        'post_author' => $user_ID,
        'post_type' => 'tributes', // post
        // 'post_category' => array(0)
    );
    $tribute_id = wp_insert_post($new_tribute);

    update_post_meta($tribute_id, 'tribute_img', $tribute_type );
    update_post_meta($tribute_id, 'author_name', $author );
    update_post_meta($tribute_id, 'time_gem_id', $time_gem_id );
    update_post_meta($tribute_id, 'time_gem_auth_id', $timeGemAuthorId );


    // send mail to time gem author
    $user = get_user_by( 'id', $timeGemAuthorId );
    $name = $user->first_name . ' ' . $user->last_name;
    $emailBody='';
    $emailBody.='<h2>Hi '.$name.'</h2>';
    $emailBody.='<p>A new tribute waiting for your approval.</p>';
    $emailBody.='<p>Click <a href="'.get_site_url().'/my-account/my-timegem-tributes/" target="_blank" rel="time gem link">here</a> to review & publish the tribute.</p>';
    
    

    $to = $user->user_email;
    $subject = 'A new tribute waiting for approval!';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail( $to, $subject, $emailBody, $headers );


    $sms = 'Thank you for sending your tribute. Tribute will be published once the account owner has approved.';
    echo json_encode(['status'=>'ok', 'message' => $sms ]);
    exit(); // wp_die();
}
add_action('wp_ajax_add_tributes', 'add_tributes');
add_action('wp_ajax_nopriv_add_tributes', 'add_tributes');



// ajax
add_action('wp_ajax_get_my_ajax_data', 'get_my_ajax_data');

add_action('wp_ajax_nopriv_get_my_ajax_data', 'get_my_ajax_data');

function get_my_ajax_data(){
    $id=$_POST['id'];
    $paged=$_POST['paged'];
    $args = array(
        'post_type' => 'tributes', 
        'posts_per_page' => 1,
        'post_status'   => 'publish', // draft | pending | publish
        'paged'=>$paged, 
        'meta_query'=>array(
            'relation' => 'AND', // Optional, defaults to "AND"
            array(
                'key'     => 'time_gem_id',
                'value'   => $id,
                'compare' => '='
            ),
        )

    );
    $tributes_query = new WP_Query($args);

    if ($tributes_query->have_posts()) {
        while ($tributes_query->have_posts()) {
            $tributes_query->the_post();

            $tribute_id= get_the_ID();

            $timeGemId = get_post_meta( $tribute_id, 'time_gem_id', true );
            $imgTribute= get_post_meta( $tribute_id, 'tribute_img', true );
        
            $img = "";
            if($imgTribute==1){
                $img = get_stylesheet_directory_uri()."/assets/give-flower.svg";
            } 
            if($imgTribute==2){
                $img = get_stylesheet_directory_uri()."/assets/give-hug-blue.svg";
            } 
            if($imgTribute==3){
                $img = get_stylesheet_directory_uri()."/assets/send-love-blue.svg";
            }
    ?>

        <div class="card comment-body">
            <div class="card-body">
                
                <div class="row">
                    <div class="col-md-2">
                        
                        <?php 
                        if($img){
                            echo '<img src="'.$img.'" class="img img-fluid icon-s"/>';
                        }
                        ?>
                        
                        <p class="text-secondary text-center post-time"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';?></p>
                        
                    </div>
                    <div class="col-md-10 tributes-sms-share">
                        <h4 class="arthur">
                            <a class="" href="<?php the_permalink(); ?>"><strong><?php the_title();?></strong></a>
                        </h4>
                        <div class="clearfix"></div>
                        <?php the_content();?>
                    </div>
                </div>
            </div>
        </div>
        
    <?php
        }
        // Restore the global post data
        wp_reset_postdata();
    } 

    exit();
}


// ajax process tribute status update
function tribute_status_update() {

    $id = sanitize_text_field($_POST['id']);
    $status = sanitize_text_field($_POST['status']);

    $timeGemAughId = get_post_meta( $id, 'time_gem_auth_id', true );
    $user_id = get_current_user_id();
    $timeGemAuth = (int)$timeGemAughId;

    if($user_id === $timeGemAuth){
        $post_data = array(
            'ID'          => $id,
            'post_status' => $status,  // Replace with the desired status
        );
        wp_update_post($post_data);
    }

    exit(); // wp_die();

}

add_action('wp_ajax_tribute_status_update', 'tribute_status_update');
add_action('wp_ajax_nopriv_tribute_status_update', 'tribute_status_update');



function cptui_register_my_cpts() {

	/**
	 * Post Type: Tributes.
	 */

	$labels = [
		"name" => esc_html__( "Tributes", "custom-post-type-ui" ),
		"singular_name" => esc_html__( "Tributes", "custom-post-type-ui" ),
		"menu_name" => esc_html__( "My Tributes", "custom-post-type-ui" ),
		"all_items" => esc_html__( "All Tributes", "custom-post-type-ui" ),
		"add_new" => esc_html__( "Add new", "custom-post-type-ui" ),
		"add_new_item" => esc_html__( "Add new Tributes", "custom-post-type-ui" ),
		"edit_item" => esc_html__( "Edit Tributes", "custom-post-type-ui" ),
		"new_item" => esc_html__( "New Tributes", "custom-post-type-ui" ),
		"view_item" => esc_html__( "View Tributes", "custom-post-type-ui" ),
		"view_items" => esc_html__( "View Tributes", "custom-post-type-ui" ),
		"search_items" => esc_html__( "Search Tributes", "custom-post-type-ui" ),
		"not_found" => esc_html__( "No Tributes found", "custom-post-type-ui" ),
		"not_found_in_trash" => esc_html__( "No Tributes found in trash", "custom-post-type-ui" ),
		"parent" => esc_html__( "Parent Tributes:", "custom-post-type-ui" ),
		"featured_image" => esc_html__( "Featured image for this Tributes", "custom-post-type-ui" ),
		"set_featured_image" => esc_html__( "Set featured image for this Tributes", "custom-post-type-ui" ),
		"remove_featured_image" => esc_html__( "Remove featured image for this Tributes", "custom-post-type-ui" ),
		"use_featured_image" => esc_html__( "Use as featured image for this Tributes", "custom-post-type-ui" ),
		"archives" => esc_html__( "Tributes archives", "custom-post-type-ui" ),
		"insert_into_item" => esc_html__( "Insert into Tributes", "custom-post-type-ui" ),
		"uploaded_to_this_item" => esc_html__( "Upload to this Tributes", "custom-post-type-ui" ),
		"filter_items_list" => esc_html__( "Filter Tributes list", "custom-post-type-ui" ),
		"items_list_navigation" => esc_html__( "Tributes list navigation", "custom-post-type-ui" ),
		"items_list" => esc_html__( "Tributes list", "custom-post-type-ui" ),
		"attributes" => esc_html__( "Tributes attributes", "custom-post-type-ui" ),
		"name_admin_bar" => esc_html__( "Tributes", "custom-post-type-ui" ),
		"item_published" => esc_html__( "Tributes published", "custom-post-type-ui" ),
		"item_published_privately" => esc_html__( "Tributes published privately.", "custom-post-type-ui" ),
		"item_reverted_to_draft" => esc_html__( "Tributes reverted to draft.", "custom-post-type-ui" ),
		"item_scheduled" => esc_html__( "Tributes scheduled", "custom-post-type-ui" ),
		"item_updated" => esc_html__( "Tributes updated.", "custom-post-type-ui" ),
		"parent_item_colon" => esc_html__( "Parent Tributes:", "custom-post-type-ui" ),
	];

	$args = [
		"label" => esc_html__( "Tributes", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "tributes", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
		"show_in_graphql" => false,
	];

	register_post_type( "tributes", $args );

	/**
	 * Post Type: Time gem.
	 */

	$labels = [
		"name" => esc_html__( "Time gem", "custom-post-type-ui" ),
		"singular_name" => esc_html__( "Time gem", "custom-post-type-ui" ),
		"menu_name" => esc_html__( "My Time Gem", "custom-post-type-ui" ),
		"all_items" => esc_html__( "All Time Gem", "custom-post-type-ui" ),
		"add_new" => esc_html__( "Add new", "custom-post-type-ui" ),
		"add_new_item" => esc_html__( "Add new Time Gem", "custom-post-type-ui" ),
		"edit_item" => esc_html__( "Edit Time Gem", "custom-post-type-ui" ),
		"new_item" => esc_html__( "New Time Gem", "custom-post-type-ui" ),
		"view_item" => esc_html__( "View Time Gem", "custom-post-type-ui" ),
		"view_items" => esc_html__( "View Time Gem", "custom-post-type-ui" ),
		"search_items" => esc_html__( "Search Time Gem", "custom-post-type-ui" ),
		"not_found" => esc_html__( "No Time Gem found", "custom-post-type-ui" ),
		"not_found_in_trash" => esc_html__( "No Time Gem found in trash", "custom-post-type-ui" ),
		"parent" => esc_html__( "Parent Time Gem:", "custom-post-type-ui" ),
		"featured_image" => esc_html__( "Featured image for this Time Gem", "custom-post-type-ui" ),
		"set_featured_image" => esc_html__( "Set featured image for this Time Gem", "custom-post-type-ui" ),
		"remove_featured_image" => esc_html__( "Remove featured image for this Time Gem", "custom-post-type-ui" ),
		"use_featured_image" => esc_html__( "Use as featured image for this Time Gem", "custom-post-type-ui" ),
		"archives" => esc_html__( "Time Gem archives", "custom-post-type-ui" ),
		"insert_into_item" => esc_html__( "Insert into Time Gem", "custom-post-type-ui" ),
		"uploaded_to_this_item" => esc_html__( "Upload to this Time Gem", "custom-post-type-ui" ),
		"filter_items_list" => esc_html__( "Filter Time Gem list", "custom-post-type-ui" ),
		"items_list_navigation" => esc_html__( "Time Gem list navigation", "custom-post-type-ui" ),
		"items_list" => esc_html__( "Time Gem list", "custom-post-type-ui" ),
		"attributes" => esc_html__( "Time Gem attributes", "custom-post-type-ui" ),
		"name_admin_bar" => esc_html__( "Time Gem", "custom-post-type-ui" ),
		"item_published" => esc_html__( "Time Gem published", "custom-post-type-ui" ),
		"item_published_privately" => esc_html__( "Time Gem published privately.", "custom-post-type-ui" ),
		"item_reverted_to_draft" => esc_html__( "Time Gem reverted to draft.", "custom-post-type-ui" ),
		"item_scheduled" => esc_html__( "Time Gem scheduled", "custom-post-type-ui" ),
		"item_updated" => esc_html__( "Time Gem updated.", "custom-post-type-ui" ),
		"parent_item_colon" => esc_html__( "Parent Time Gem:", "custom-post-type-ui" ),
	];

	$args = [
		"label" => esc_html__( "Time gem", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "time-gem", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail", "author" ],
		"show_in_graphql" => false,
	];

	register_post_type( "time-gem", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );



function hs_image_editor_default_to_gd( $editors ) {
    $gd_editor = 'WP_Image_Editor_GD';
    $editors = array_diff( $editors, array( $gd_editor ) );
    array_unshift( $editors, $gd_editor );
    return $editors;
}

function pippin_get_image_id($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
        return $attachment[0]; 
}


// ajax process set_timegem_pass
function set_timegem_pass() {

    $id   = sanitize_text_field($_POST['id']);
    $pass = sanitize_text_field($_POST['pass']);

    $postId = (int)$id;
    $post_data = array(
        'ID' => $postId,
        'post_password' => $pass
    );

    // Use the wp_update_post function to update the post.
    wp_update_post($post_data);

    // post_password

    $sms = 'password save Done!';
    echo json_encode(['status'=>'ok', 'message' => $sms ]);
    exit(); // wp_die();
}
add_action('wp_ajax_set_timegem_pass', 'set_timegem_pass');
add_action('wp_ajax_nopriv_set_timegem_pass', 'set_timegem_pass');

// ajax process reset_timegem_pass
function reset_timegem_pass() {

    $id   = sanitize_text_field($_POST['id']);
    // $pass = sanitize_text_field($_POST['pass']);

    $postId = (int)$id;
    $post_data = array(
        'ID' => $postId,
        'post_password' => ''
    );

    // Use the wp_update_post function to update the post.
    wp_update_post($post_data);

    // post_password

    $sms = 'Password removed Done!';
    echo json_encode(['status'=>'ok', 'message' => $sms ]);
    exit(); // wp_die();
}
add_action('wp_ajax_reset_timegem_pass', 'reset_timegem_pass');
add_action('wp_ajax_nopriv_reset_timegem_pass', 'reset_timegem_pass');

add_filter('manage_time-gem_posts_columns', function($columns) {
    return array_merge($columns, ['qr_code' => __('QR Code', 'textdomain')]);
});
 
add_action('manage_time-gem_posts_custom_column', function($column_key, $post_id) {
    if ($column_key == 'qr_code') {
        $status=get_post_status($post_id);
        if($status=='publish'){
            $qr_url=get_stylesheet_directory_uri().'/qr.php?link='.get_the_permalink($post_id);
            echo '<a href="'.$qr_url.'" target="_blank" style="color:#8224e3">QR Code</a>';
        }
        
    }
}, 10, 2);

// Replace 'your_custom_post_type' with the name of your custom post type

function woox_on_custom_post_status_transition($new_status, $old_status, $post) {
    // Check if the post type is your custom post type
    if ($post->post_type === 'time-gem') {
        // Check if the transition is to a specific status
        if ($new_status === 'disapprove') {
            $author = $post->post_author;
            $name = get_the_author_meta( 'display_name', $author );
            $email = get_the_author_meta( 'user_email', $author );
            $to[] = sprintf( '%s <%s>', $name, $email );
            //$to[] = sprintf( '%s <%s>', 'Hasan', 'greensabuj350@gmail.com' );
            $subject = 'Time Gem not approved';
            $message = 'Regrettably, we must inform you that your Time Gem profile has not been approved.<br/><br/>
            This decision is due to the presence of inappropriate content that violates our terms and conditions and privacy policy.<br/><br/>            
            We kindly request you to carefully review your profile, make necessary amendments, and resubmit it for our review and approval.<br/><br/>            
            Thank you for your understanding and cooperation.<br/><br/>            
            Best regards,<br/>
            Team at Time Gem';
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail($to, $subject, $message,$headers);
        }
    }
}

add_action('transition_post_status', 'woox_on_custom_post_status_transition', 10, 3);

/*Ds Added*/

// before add to cart, only allow 1 item in a cart
add_filter( 'woocommerce_add_to_cart_validation', 'wooc_custom_add_to_cart_before' );
function wooc_custom_add_to_cart_before( $cart_item_data ) {
    global $woocommerce;
    $woocommerce->cart->empty_cart();
    // Do nothing with the data and return
    return true;
}
// update switch subscription product thumbnail
add_action('init','set_swich_subscription_image');
function set_swich_subscription_image(){
    if (isset($_GET['switch-subscription'])) {
        $order_id = (isset($_GET['switch-subscription']) ? $_GET['switch-subscription'] :'');
        $getorder = wc_get_order($order_id); //getting order Object
        $get_parent_id = $getorder->get_parent_id();
        $order = wc_get_order($get_parent_id); //getting order Object
        if ($order) {
            $order_data = array();
            foreach ($order->get_items() as $item_id => $item) {
                $getThumb = $item->get_meta('photo_url');
                
                if (!empty($getThumb) && !empty($get_parent_id)) {
                    WC()->session->set( 'switch_subs' , $get_parent_id );
                    WC()->session->set( 'ds_product_thumbnail' , $getThumb[0]);
                }
            }
        }
    }    
}
//On login set default currency USD;
add_filter( 'woocommerce_login_redirect', 'wc_custom_user_redirect', 9999, 2 ); 
function wc_custom_user_redirect(  $redirect,$user ) {
    if ($user) {
        if (class_exists('WCCS')) {
            $redirect = get_site_url().'/my-account/?currency=USD';
        }
    }
    return $redirect;
}
// Display Dynamic Price on pricing table
add_shortcode( 'woocommerce_price', 'ds_wc_get_product_price' );
function ds_wc_get_product_price( $atts ) {
    ob_start();
    global  $woocommerce;
   $symbol = get_woocommerce_currency_symbol();
    $atts = shortcode_atts( array(
        'id' => null,
    ), $atts, 'bartag' );

    $html = '';

    if( intval( $atts['id'] ) > 0 && function_exists( 'wc_get_product' ) ){
         $_product = wc_get_product( $atts['id'] );
         $html = $_product->get_price_html();
    }
    if ($atts['id'] == 1789) {
       $htmlarr = explode('every',$html);
       $html = (isset($htmlarr[0]) ? $htmlarr[0] : $html); 
    }
    else if ($atts['id'] == 1517) {
       $html = str_replace('and a','+',$html);
       //$html = str_replace('sign-up fee','Setup Fee',$html);
    }
    echo $html;
    return ob_get_clean();
}

//Add interval wc subscription for lifetime
function wc_extend_subscription_period_intervals( $intervals ) {
    $intervals[100] = sprintf( __( 'every %s', 'woocommerce' ), WC_Subscriptions::append_numeral_suffix( 100 ) );
    return $intervals;
}
add_filter( 'woocommerce_subscription_period_interval_strings', 'wc_extend_subscription_period_intervals',10,3 );
//Remove related items on product page
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

add_filter( 'woocommerce_add_cart_item_data', function ( $cart_item_data, $productId, $variationId ) {
    if ( isset($_GET['plaque_order_id']) && ! empty($_GET['plaque_order_id']) ) {
        $cart_item_data['plaque_order_id'] = sanitize_text_field( $_GET['plaque_order_id'] );
    }
    return $cart_item_data;
}, 10, 3 );

add_filter( 'woocommerce_get_cart_item_from_session', function ( $cart_item_data, $cartItemSessionData, $cartItemKey ) {
    if ( isset( $cartItemSessionData['plaque_order_id'] ) ) {
        $cart_item_data['plaque_order_id'] = $cartItemSessionData['plaque_order_id'];
    }
    return $cart_item_data;
}, 10, 3 );

// ajax process add_to_card_plaque_from
function add_to_card_plaque_from() {

    $response = [
        'message' => 'something went wrong',
        'error' => true,
        'class' => 'error',
    ]; 
    //$allowed_variations = array(1332,1333,1334);
    $allowed_variations = array(1791,1792,1793);
    
    $user_id = get_current_user_id();
    $plaque_timegem_id = (isset($_POST['plaque_timegem_id']) ? sanitize_text_field($_POST['plaque_timegem_id']) :'');
    $plaque_product_id = (isset($_POST['plaque_product_id']) ? sanitize_text_field($_POST['plaque_product_id']) :'');
    $plaque_subscription_id = (isset($_POST['plaque_subscription_id']) ? sanitize_text_field($_POST['plaque_subscription_id']) :'');
    $variation_id = (isset($_POST['plaque_color']) ? sanitize_text_field($_POST['plaque_color']) :'');
    $hole = (isset($_POST['need-hole']) ? sanitize_text_field($_POST['need-hole']) :'no');
    if (!empty($variation_id)) {
        $variation_id = intval($variation_id);
    }

    $thumbnail = get_site_url().'/wp-content/uploads/2023/09/whitegem.png';
    if ($variation_id == 1791) {
        $thumbnail = get_site_url().'/wp-content/uploads/2023/09/whitegem.png';
    }else if ($variation_id == 1792) {
        $thumbnail = get_site_url().'/wp-content/uploads/2023/09/blackgem.png';
    }else if ($variation_id == 1793) {
        $thumbnail = get_site_url().'/wp-content/uploads/2023/10/Bronze.png';
    }
   
    $postData = array(
        'timegem_post_id' => $timegem_post_id,
        'product_id' => $plaque_product_id,
        'subscription_id' => $plaque_subscription_id,
        'variation_id' => $variation_id,
        'hole'=>$hole,
        'thumbnail'=>$thumbnail,
    );
    if(empty($plaque_timegem_id)) {
        $response['message'] = 'Please Select timegem';
        wp_send_json_error($response);
    }else if(empty($variation_id)) {
        $response['message'] = 'Please Select Plaque color';
        wp_send_json_error($response);
    }else{
        $author_id = get_post_field ('post_author', $plaque_timegem_id);
        if (!in_array($variation_id, $allowed_variations)) {
            $response['message'] = 'Please Select valid Plaque color';
            wp_send_json_error($response);
        }else if ($user_id != $author_id) {
            $response['message'] = 'Post Author is invalid';
            wp_send_json_error($response);
        }else {
            if (sizeof( WC()->cart->get_cart() ) > 0 ) { 
                WC()->cart->empty_cart();
            }
            $addCartMeta=array(
                's_from_img'=>$thumbnail,
                'plaque_timegem_id'=>$plaque_timegem_id,
                's_from_hole'=>$hole,
            );
            //print_r($s_from_add_to_cart_meta);die;
            WC()->cart->add_to_cart( $variation_id,1,0,array(),$addCartMeta);
            $response = [
                'message' => 'Item added successfully',
                'error' => false,
                'class' => 'success',
                's_from_img' => $plaqueImage[$variation_id],
                's_from_hole' => $hole,
                'variation_id' => $variation_id,
                'plaque_timegem_id' => $plaque_timegem_id,
            ];
            wp_send_json_success($response);
        }
    }
    die(); // wp_die();
}
add_action('wp_ajax_add_to_card_plaque_from', 'add_to_card_plaque_from');
add_action('wp_ajax_nopriv_add_to_card_plaque_from', 'add_to_card_plaque_from');


function cstm_wcs_add_cart_first_renewal_payment_date( $order_total_html, $cart ) {

     $order_total_html = str_replace( 'First renewal', 'Next Payment Date', $order_total_html );
    
    return $order_total_html;
}
add_filter( 'wcs_cart_totals_order_total_html', 'cstm_wcs_add_cart_first_renewal_payment_date', 10, 2 );



//add_action( 'woocommerce_review_order_before_submit', 'ds_add_custom_info_checkout_button',10 );
function ds_add_custom_info_checkout_button() {
        echo '<div><a class="checkout-link-text link-text-btn" href="'.get_the_permalink(2065).'" target="_blank">For more shipping information please click here.</a></div>';
   
}
//add_action( 'woocommerce_review_order_after_cart_contents', 'ds_add_shipping_info_checkout_item',10 );
function ds_add_shipping_info_checkout_item() {
        echo '<div class="shipping-info">
            <strong>Shipping Times:</strong> 8 - 15 Business Days <br>
            <span class="infodetails" style="font-size:12px">We kindly ask for your patience as our skilled artisans meticulously craft your Time Gem, ensuring every detail is perfected.</span>
        </div>';
   
}


/*add_filter( 'wp_mail_from', 'update_mail_from',99,3 );
function update_mail_from( $email ){
    return "contact@thetimegem.com";
}*/


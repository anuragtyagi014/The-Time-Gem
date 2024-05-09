<?php
?>

        <?php

            // Get the current page number
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $user_id = get_current_user_id();

            $args = array(
                'post_type'=>'tributes', // tributes
                'orderby'       =>  'post_date',
                'order'         =>  'DESC',
                'posts_per_page' => -1,
                'paged' => $paged,
                'post_status'=>'any',
                'meta_query'=>array(
                    'relation' => 'AND', // Optional, defaults to "AND"
                    array(
                        'key'     => 'time_gem_auth_id',
                        'value'   => $user_id,
                        'compare' => '='
                    ),
                )
              );
              
              
              $timegems = get_posts( $args );



              if(!is_wp_error($timegems)){
                if(count($timegems)>0){
?>
<h2 style="color:#8224e3;"><b><?php echo __( 'My Time Gem Tributes', 'woocommerce' ); ?></b></h2>
<table class="shop_table shop_table_responsive my_account_orders">

    <thead>
        <tr>
            <th class=""><span class="nobr">Image</span></th>
            <th class=""><span class="nobr">Tributes</span></th>
            <th class=""><span class="nobr">Status</span></th>
            <th class=""><span class="nobr">Action</span></th>
        </tr>
    </thead>

    <tbody>
<?php
                    foreach($timegems as $row){

                        $tribute_id = $row->ID;
                        $imgTribute= get_post_meta( $tribute_id, 'tribute_img', true );
        
                        $img = "";
                        if($imgTribute==1){
                            $img = get_stylesheet_directory_uri()."/assets/give-flower.svg";
                        } 
                        if($imgTribute==2){
                            $img = get_stylesheet_directory_uri()."/assets/send-love-blue.svg";
                        } 
                        if($imgTribute==3){
                            $img = get_stylesheet_directory_uri()."/assets/give-hug-blue.svg";
                        }

                        $tributeImg = $img ? '<img src="'.$img.'" class="img img-fluid icon-s" style="background: transparent; width: 100px;" />' : '';

                        // $timeDeff = human_time_diff( $row->post_date, date("Y-m-d H:i:s") ) . ' ago';
                        $timeDeff = human_time_diff( strtotime($row->post_date), current_time('timestamp') ) . ' ago';
                        echo'
                        <tr>
                            <td>'.$tributeImg.'</td>
                            <td style="width:400px;">
                            <p style="color:#8224e3;"><b>'.$row->post_title.'</b></p>
                            '.$row->post_content.'
                            </td>
                            <td>
                                <p style="color:#b61919;padding-bottom: 0;">'.$timeDeff.'</p>
                                <select name="_status" onchange="tributesStatus('.$tribute_id.', this)">';
                                
                                $publishe = ("publish"===$row->post_status)?'selected':'';
                                $pending  = ("pending"===$row->post_status)?'selected':'';
                                $draft    = ("draft"===$row->post_status)?'selected':'';
                                echo '
                                    <option value="publish" '.$publishe.' >Published</option>               
                                    <option value="pending" '.$pending.' >Pending Review</option>
                                    <option value="draft" '.$draft.' >Draft</option>
                                </select>
                            </td>
                            <td>
                                <a href="https://thetimegem.com/my-account/my-timegem-tributes/?selected='.base64_encode($row->ID).'&action=delete_tr" class="woocommerce-button button delete-tr" style="background:#b61919;">Delete</a>
                                <!--<a href="'.get_the_permalink($row->ID).'" class="woocommerce-button button view">View</a>-->
                            </td>
                        </tr>';
                    }

                    /*
                        // Display pagination links
                        echo '<div class="pagination">';
                        echo paginate_links(
                            array(
                                'base' => get_pagenum_link(1) . '%_%',
                                'format' => '/page/%#%',
                                'current' => max(1, $paged),
                                'total' => count($timegems), //$timegems->max_num_pages,
                            )
                        );
                        echo '</div>';
                    */
?>
    </tbody>
</table>
<?php
                }else{
                    wc_print_notice( esc_html__( 'No tributes from anyone yet.', 'woocommerce' ), 'notice' );
                }
              }else{
                wc_print_notice( esc_html__( 'No tributes from anyone yet.', 'woocommerce' ), 'notice' );
              }

        ?>


<?php
// echo do_shortcode("[tributes_pagination]");



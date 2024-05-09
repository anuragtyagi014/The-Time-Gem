<?php
global $woocommerce;
$user_id = get_current_user_id();
$args = array(
    'post_type' => 'time-gem',
    'author'        =>  $user_id,
    'orderby'       =>  'post_date',
    'order'         =>  'DESC',
    'posts_per_page' => -1,
    'post_status' => array('publish', 'draft', 'pending'),
);
$getTimegems = get_posts($args);


$subscription_ids = wcs_get_users_subscriptions($user_id, array(
    'subscriptions_per_page' => -1,
    //'subscription_status'   => 'active', // Change to 'on-hold' or other status if needed.
));
$product_id = 1790; //pass plaque product id here
$product = wc_get_product($product_id);

if (($subscription_ids) && ($product->is_type('variable'))) {
    $variations = $product->get_available_variations();
    $variation_ids = wp_list_pluck($variations, 'variation_id');
?>
    <div id="plaque-form-container">
        <?php //echo do_shortcode('[wcc_switcher]');
        ?>
        <form action="#" method="POST" id="plaque_purchase_form">
            <div class="response" id="plaque-response-txt"></div>
            <?php wp_nonce_field('add_to_card_plaque_from_nonce'); ?>
            <input type="hidden" name="action" value="add_to_card_plaque_from">
            <input type="hidden" name="plaque_product_id" value="<?php echo $product_id; ?>">

            <div class="form-group plaque-gemid-field">
                <label for="plaque_timegem_id">Select Time Gem</label>
                <select name="plaque_timegem_id" id="plaque_timegem_id" required>
                    <option value="">Please Select</option>
                    <?php
                    if ((!is_wp_error($getTimegems)) && (count($getTimegems) > 0)) {
                        foreach ($getTimegems as $timegem) {
                            echo '<option value="' . $timegem->ID . '">' . $timegem->post_title . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group plaque-color-field" style="margin-top: 15px;">
                <h4><span style="color: #8224e3;"><strong>Choose Time Gem Colour</strong></span></h4>
                <div class="option-from-wrap plaque_option-from-wrap" style="display:flex;margin-top:20px ;">
                    <div class="img-wrap">
                        <div class="single-img">
                            <label>
                                <input checked="" type="radio" name="plaque_color" class="input-hidden" value="1791" data-pimg="1">
                                <img class="timegem-img" src="<?php echo get_site_url(); ?>/wp-content/uploads/2023/08/Whitegem100.png" alt="whitegem">
                            </label>
                        </div>

                        <div class="single-img">
                            <label>
                                <input type="radio" name="plaque_color" class="input-hidden" value="1792" data-pimg="2">
                                <img class="timegem-img" src="<?php echo get_site_url(); ?>/wp-content/uploads/2023/08/blackgem100.png" alt="blackgem">
                            </label>
                        </div>

                        <div class="single-img">
                            <label>
                                <input type="radio" name="plaque_color" class="input-hidden" value="1793" data-pimg="3">
                                <img class="timegem-img" src="<?php echo get_site_url(); ?>/wp-content/uploads/2023/10/Bronze.png" alt="goldgem">
                            </label>
                        </div>

                    </div>

                </div>
            </div>
            <div class="form-group need-hole-fld">
                <label>
                    <input type="checkbox" id="need-hole" name="need-hole" value="yes"> Click For Holes To Mount Your Time Gem
                </label>
            </div>
            <div class="price-container">
                <div class="plaque-price" style="margin-top: 10px; color: #8224e3;">
                    <p>Plaque Replacement Price: <strong><?php echo do_shortcode('[woocommerce_price id=1791]'); ?></strong></p>
                </div>
            </div>
            <input type="submit" value="Purchase" name="submit-plaque" id="submit-plaque" class="btn button btn-submit">
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#need-hole').change(function() {
                console.log("Checkbox change detected");
                need_hole_2(this);
            });
        });

        function need_hole_2(checkbox) {
            console.log("Function need_hole_2 invoked");
            let siteUrl = '<?php echo get_site_url(); ?>';
            let isChecked = $(checkbox).is(':checked');
            console.log("Checkbox checked state: ", isChecked);

            // Define image  replacement
            let images = {
                withHole: [
                    siteUrl + '/wp-content/uploads/2023/10/hw-1.jpg',
                    siteUrl + '/wp-content/uploads/2023/10/hb-1.jpg',
                    siteUrl + '/wp-content/uploads/2023/10/Bronze-ho.png',
                ],
                withoutHole: [
                    siteUrl + '/wp-content/uploads/2023/08/Whitegem100.png',
                    siteUrl + '/wp-content/uploads/2023/08/blackgem100.png',
                    siteUrl + '/wp-content/uploads/2023/10/Bronze.png',
                ],
                withHoleV: [
                    siteUrl + '/wp-content/uploads/2023/10/IMG_8550-min.jpg',
                    siteUrl + '/wp-content/uploads/2023/10/IMG_8554-min.jpg',
                    siteUrl + '/wp-content/uploads/2023/12/gold-hole.jpg',
                ],
                withoutHoleV: [
                    siteUrl + '/wp-content/uploads/2023/09/whitegem.png',
                    siteUrl + '/wp-content/uploads/2023/09/blackgem.png',
                    siteUrl + '/wp-content/uploads/2023/09/goldgem.png',
                ]
            };

            // Attempt to directly find the .timegem-img elements in the document
            let $imgs = $('.timegem-img');
            console.log("Number of .timegem-img elements found: ", $imgs.length);

            $imgs.each(function(index, img) {
                if (isChecked) {
                    console.log("Checkbox is checked - Updating image at index: ", index);
                    $(img).attr('src', images.withHole[index]);
                    //  $(img).closest('.single-img').find('input[type=radio]').val(images.withHoleV[index]);

                    $(img).closest('.single-img').find('input[type=radio]').attr("data-pimg", images.withHoleV[index]);
                } else {
                    console.log("Checkbox is not checked - Reverting image at index: ", index);
                    $(img).attr('src', images.withoutHole[index]);
                    //$(img).closest('.single-img').find('input[type=radio]').val(images.withoutHoleV[index]);
                    $(img).closest('.single-img').find('input[type=radio]').attr("data-pimg", images.withoutHoleV[index]);
                }
            });
        }
    </script>
    <script type="text/javascript">
        jQuery(document).on('click', '#submit-plaque', function(e) {
            e.preventDefault();
            var serializedData = jQuery('#plaque_purchase_form').serialize();
            let ajaxurl = "<?php echo admin_url('admin-ajax.php') ?>";
            jQuery('#plaque-response-txt').removeClass('error');
            jQuery('#plaque-response-txt').removeClass('success');
            jQuery('#plaque-response-txt').html('');
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                //dataType: 'html',
                data: serializedData + "&action=add_to_card_plaque_from",
                success: function(response) {
                    var response_class = 'error';
                    var message = response.data.message;
                    if (typeof message == 'undefined') {
                        message = 'Something went wrong';
                    }
                    console.log(response)
                    if (response.success) {
                        response_class = 'success';
                        message = response.data.message;
                        var response_text = '<span>' + message + '</span>';
                        jQuery('#plaque-response-txt').addClass(response_class);
                        jQuery('#plaque-response-txt').html(response_text);
                        window.location.replace("<?php echo wc_get_checkout_url(); ?>");
                    } else {
                        var response_text = '<span>' + message + '</span>';
                        jQuery('#plaque-response-txt').addClass(response_class);
                        jQuery('#plaque-response-txt').html(response_text);
                    }
                }
            });
        });
    </script>
<?php
} else {
    echo '<div class="woocommerce-info">You have not ordered a Time Gem Plaque Yet.</div>';
}
?>
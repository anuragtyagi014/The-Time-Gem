<?php
global $post;
$id = $post->ID;
$meta = get_post_meta($id, '', true);
$title = get_post_field('post_title', $id);
$description = $meta['story'][0];
$args = array(
    'post_type' => 'tributes',
    'posts_per_page' => -1,
    'post_status'   => 'publish', // draft | pending | publish
    'meta_query' => array(
        'relation' => 'AND', // Optional, defaults to "AND"
        array(
            'key'     => 'time_gem_id',
            'value'   => $id,
            'compare' => '='
        ),
    )

);

$tributes_query = new WP_Query($args);
//$tributs_num=0;

$tributs_num = $tributes_query->found_posts;
?>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/public-post.css?v=23724ghd">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css"> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script> -->

<style>
    #masonary_tab_gallery_content {
        margin-left: 0px !important;
        width: 100% !important;
        left: 0 !important;
    }

    .gallery-area .addtoany_share_save_container {
        display: none !important;
    }

    .tributes-sms-share .addtoany_share_save_container.addtoany_content.addtoany_content_bottom {
        text-align: right;
    }

    @media only screen and (max-width: 600px) {
        .tributes-sms-share .addtoany_share_save_container.addtoany_content {
            text-align: center !important;
        }
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="header-area">
            <div class="top-bannaer">
                <?php
                // print_r($meta);
                if (isset($meta['time_gem_bg_image'][0]) && !empty($meta['time_gem_bg_image'][0])) {
                    echo '<img class="card-img-top" src="' . $meta['time_gem_bg_image'][0] . '" alt="' . $title . '">';
                }
                ?>
            </div>
            <div class="avatar-i card-body text-right">
                <?php
                if (isset($meta['profile_image'][0]) && !empty($meta['profile_image'][0])) {
                    echo '<div class="avatar text-right"><img class="rounded-circle" src="' . wp_get_attachment_image_src($meta['profile_image'][0], 'thumbnail')[0] . '" alt="Timegem profile image"></div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<section class="sec-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="intro-text">
                    <div class="pre-text">In loving memory of</div>
                    <h2 class="card-title"><?php echo $title; ?></h2>
                    <div class="bd-date"><?php echo (isset($meta['date_of_birth'][0]) ? date('d/m/Y', strtotime($meta['date_of_birth'][0])) : '') ?> - <?php echo (isset($meta['passing_date'][0]) ? date('d/m/Y', strtotime($meta['passing_date'][0])) : '') ?></div>

                    <div class="more-status-m">
                        <div class="single-in">
                            <div class="count"><?php echo do_shortcode('[post-views]'); ?></div>
                            <div class="t-text">Views</div>
                        </div>
                        <?php
                        if (!isset($meta['disable_tribute'][0])) {
                        ?>
                            <div class="single-in">
                                <div class="count"><?php echo $tributs_num; ?></div>
                                <div class="t-text">Tributes</div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>

                    <ul class="social-info">
                        <li><?php echo do_shortcode('[addtoany]'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div id="model_2">
                    <div id="tab-navigation" class="tabs-container tab-wrapper">
                        <ul class="tabs nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-link tab-link active" id="gallery-tab"><a class="tab-nav" href="#about-area"><i class="fa fa-user"></i>About</a></li>
                            <li class="nav-link tab-link" id="info-tab"><a class="tab-nav" href="#gallery-area"><i class="fa fa-image"></i>Gallery</a></li>
                            <?php
                            if ($meta['disable_tribute'][0] == false) {
                            ?>
                                <li class="nav-link tab-link" id="tributes-tab"><a class="tab-nav" href="#tributes-area"><i class="fa fa-comments"></i>Tributes</a></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="tab-content content-wrapper" id="myTabContent">
                        <div id="about-area" class="tab-content active tab-pane show">
                            <div class="heading-text">
                                <h3 class="heading custom-text">About</h3>
                            </div>
                            <p class="card-text">
                                <?php echo $description; ?>
                            </p>
                            <div class="row more-info-area">
                                <?php
                                if (!empty($meta['question_1'][0])) :
                                ?>
                                    <div class="col-md-12">
                                        <p><strong>Early Life:</strong> <br><?php echo $meta['question_1'][0]; ?></p>
                                    </div>
                                <?php
                                endif;
                                if (!empty($meta['question_2'][0])) :
                                ?>
                                    <div class="col-md-12">

                                        <p><strong>Education & Career:</strong> <br><?php echo $meta['question_2'][0]; ?></p>
                                    </div>
                                <?php
                                endif;
                                if (!empty($meta['question_3'][0])) :
                                ?>
                                    <div class="col-md-12">

                                        <p><strong>Personal Interests:</strong> <br><?php echo $meta['question_3'][0]; ?></p>
                                    </div>
                                <?php
                                endif;
                                if (!empty($meta['question_4'][0])) :
                                ?>
                                    <div class="col-md-12">

                                        <p><strong>Achievements & Milestones:</strong> <br><?php echo $meta['question_4'][0]; ?></p>
                                    </div>
                                <?php
                                endif;
                                if (!empty($meta['question_5'][0])) :
                                ?>
                                    <div class="col-md-12">

                                        <p><strong>Family & Relationships:</strong> <br><?php echo $meta['question_5'][0]; ?></p>
                                    </div>
                                <?php
                                endif;
                                if (!empty($meta['question_6'][0])) :
                                ?>
                                    <div class="col-md-12">

                                        <p><strong>Philanthropic Activities:</strong> <br><?php echo $meta['question_6'][0]; ?></p>
                                    </div>
                                <?php
                                endif;
                                if (!empty($meta['question_7'][0])) :
                                ?>
                                    <div class="col-md-12">

                                        <p><strong>Lessons Learned:</strong> <br><?php echo $meta['question_7'][0]; ?></p>
                                    </div>
                                <?php
                                endif;
                                if (!empty($meta['question_8'][0])) :
                                ?>
                                    <div class="col-md-12">

                                        <p><strong>Sense of Humor:</strong> <br><?php echo $meta['question_8'][0]; ?></p>
                                    </div>
                                <?php
                                endif;
                                if (!empty($meta['question_9'][0])) :
                                ?>
                                    <div class="col-md-12">

                                        <p><strong>Inspirational Quotes:</strong> <br><?php echo $meta['question_9'][0]; ?></p>
                                    </div>
                                <?php
                                endif;
                                if (!empty($meta['question_10'][0])) :
                                ?>
                                    <div class="col-md-12">

                                        <p><strong>Personal Values:</strong> <br><?php echo $meta['question_10'][0]; ?></p>
                                    </div>
                                <?php
                                endif;
                                if (!empty($meta['question_11'][0])) :
                                ?>
                                    <div class="col-md-12">

                                        <p><strong>Fond Memories:</strong> <br><?php echo $meta['question_11'][0]; ?></p>
                                    </div>
                                <?php
                                endif;
                                ?>


                                <?php
                                if (!empty($meta['social_media_link'][0]) && !empty($meta['social_media_link'][0])) :

                                    $socialMediaLinks = maybe_unserialize($meta['social_media_link'][0]);
                                    $socialIcon = maybe_unserialize($meta['social_icon'][0]);

                                ?>
                                    <div class="social-media">
                                        <h3>Connect your loved one’s social media profiles</h3>
                                        <?php
                                        if (count($socialMediaLinks) > 0) {
                                            foreach ($socialMediaLinks as $k => $url) {
                                                $icon = $socialIcon[$k];
                                                if (!is_null($url) && !empty($url)) {
                                                    if ($icon === 'fa-linkedin') {
                                                        echo ' <a href="' . $url . '" target="_blank" ><i style="font-family: \'Font Awesome 6 Brands\'!important;" class="fa ' . $icon . '" aria-hidden="true"></i></a> ';
                                                    } else {
                                                        echo ' <a href="' . $url . '" target="_blank" ><i class="fa ' . $icon . '" aria-hidden="true"></i></a> ';
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php
                                endif;
                                ?>
                            </div>

                        </div>
                        <div id="gallery-area" class="tab-content active tab-pane show">

                            <div class="heading-text">
                                <h3 class="heading custom-text">Gallery</h3>
                            </div>

                            <div class="tabs-container tab-wrapper" id="inner-tab" style="display:none">
                                <ul class="tabs nav nav-tabs" id="myTab" role="tablist">

                                    <li class="nav-link tab-link active" id="photo-tab" data-tab="4">Photo</li>

                                    <li class="nav-link tab-link" data-tab="5" id="video-tab">Video</li>
                                </ul>
                            </div>
                            <!-- inner-tab-are start -->
                            <div class="tab-content content-wrapper" id="myTabContent-2">
                                <div id="tab-4" class="tab-content active tab-pane show">
                                    <div class="gallery-area">
                                        <?php
                                        $gimages = '';
                                        if (isset($meta['attachments'][0]) && !empty($meta['attachments'][0])) {
                                            $attachments = maybe_unserialize($meta['attachments'][0]);
                                            if (is_array($attachments) && count($attachments) > 0) {
                                                $gimages = implode(',', $attachments);
                                            }
                                        }

                                        if (!empty($gimages)) {
                                            $attachmentsIds = explode(',', $gimages);
                                            if ($attachmentsIds) {
                                                echo '<ul class="images-links fancybox-list-container">';
                                                foreach ($attachmentsIds as $attachmentsId) {
                                                    $mime_type = get_post_mime_type($attachmentsId);
                                                    $data_caption = strip_tags(get_the_excerpt($attachmentsId));

                                                    if (strpos($mime_type, "image") !== false) {

                                                        $video_thumbnail = get_stylesheet_directory_uri() . '/assets/video.png';
                                                        $attachment_url = wp_get_attachment_url($attachmentsId);
                                                        $filetype = wp_check_filetype($attachment_url);
                                                        $file_ext = $filetype['ext'];

                                                        echo '<li><a class="fancybox-image-gallery" rel="image-gallery" href="' . $attachment_url . '" data-caption="' . $data_caption . '">
                                                                    <img src="' . $attachment_url . '" alt="' . $data_caption . '">
                                                                 </a></li>';
                                                    }
                                                }
                                                echo '</ul>';
                                            }
                                        }
                                        ?>
                                        <?php
                                        $bk = '[vc_row id="masonary_tab_gallery_content" type="full_width_content" full_screen_row_position="middle" column_margin="default" column_direction="default" column_direction_tablet="default" column_direction_phone="default" scene_position="center" text_color="dark" text_align="left" row_border_radius="none" row_border_radius_applies="bg" overflow="visible" overlay_strength="0.3" gradient_direction="left_to_right" shape_divider_position="bottom" bg_image_animation="none" gradient_type="default" shape_type=""][vc_column column_padding="no-extra-padding" column_padding_tablet="inherit" column_padding_phone="inherit" column_padding_position="all" column_element_direction_desktop="default" column_element_spacing="default" desktop_text_alignment="default" tablet_text_alignment="default" phone_text_alignment="default" background_color_opacity="1" background_hover_color_opacity="1" column_backdrop_filter="none" column_shadow="none" column_border_radius="none" column_link_target="_self" column_position="default" gradient_direction="left_to_right" overlay_strength="0.3" width="1/1" tablet_width_inherit="default" animation_type="default" bg_image_animation="none" border_type="simple" column_border_width="none" column_border_style="solid"][vc_gallery type="image_grid" images="' . $gimages . '" image_grid_loading="skip-lazy-load" layout="fullwidth" masonry_style="true" bypass_image_cropping="true" item_spacing="3px" constrain_max_cols="true" gallery_style="7" load_in_animation="none" img_size="full" el_id="timegem_image_gellary"][/vc_column][/vc_row]';

                                        ?>
                                        <?php
                                        //echo apply_filters( 'the_content', $bk );
                                        ?>

                                        <?php
                                        $videoGallery = false;
                                        $attachmentsIds = explode(',', $gimages);
                                        if ($attachmentsIds) {
                                            foreach ($attachmentsIds as $attachmentsId) {
                                                $mime_type = get_post_mime_type($attachmentsId);
                                                if (strpos($mime_type, "video") !== false) {
                                                    $videoGallery = true;
                                                }
                                            }
                                        }

                                        if (!empty($gimages) && ($videoGallery)) {
                                            $attachmentsIds = explode(',', $gimages);
                                            if ($attachmentsIds) {
                                                echo '<div class="video-gallery"><h3>Our Video Gallery</h3>';
                                                echo '<ul class="video-links fancybox-list-container">';
                                                $i = 0;
                                                foreach ($attachmentsIds as $attachmentsId) {
                                                    $i++;
                                                    $mime_type = get_post_mime_type($attachmentsId);
                                                    $data_caption = strip_tags(get_the_excerpt($attachmentsId));

                                                    if (strpos($mime_type, "video") !== false) {

                                                        $video_thumbnail = get_stylesheet_directory_uri() . '/assets/video.png';
                                                        $attachment_url = wp_get_attachment_url($attachmentsId);
                                                        $filetype = wp_check_filetype($attachment_url);
                                                        $file_ext = $filetype['ext'];
                                        ?>
                                                        <a class="video-gallery-fancy" href="#video<?= $i; ?>">
                                                            <video width="320" controls height="240" autoplay loop playsinline muted>
                                                                <source src="<?= $attachment_url; ?>" type="video/mp4" #t=0.4>
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        </a>



                                                        <video data-controls="true" id="video<?= $i; ?>" style="display:none;">
                                                            <source src="<?= $attachment_url; ?>" type="video/mp4">
                                                            Your browser doesn't support HTML5 video tag.
                                                        </video>

                                        <?php



                                                        // echo '<li><a class="fancybox-video-gallery" rel="video-gallery" href="' . $attachment_url . '" data-caption="' . $data_caption . '" >
                                                        //             <video width="320" controls height="240" autoplay loop playsinline muted>
                                                        //               <source src="' . $attachment_url . '" type="video/mp4" #t=0.4>
                                                        //               Your browser does not support the video tag.
                                                        //             </video>
                                                        //          </a></li>';
                                                    }
                                                }
                                                echo '</ul>';
                                                echo '</div>';
                                            }
                                        }
                                        ?>
                                    </div>

                                </div>
                                <div id="tab-5" class="tab-content active tab-pane show" style="display:inline;">
                                    <div class="videos-g">
                                        <div class="video-wrapper">
                                            <div class="row">
                                                <?php
                                                $youtube_video_url = maybe_unserialize($meta['youtube_video_url'][0]);
                                                // print_r($youtube_video_url);
                                                if (is_array($youtube_video_url) && count($youtube_video_url) > 0) {
                                                    foreach ($youtube_video_url as $link) {
                                                        //echo '<div class="col-12 col-md-6"><iframe width="560" height="315" src="' . $link . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>';
                                                        $youtube_url = $link;
                                                        $link = explode('?v=', $link);
                                                        if (isset($link[1])) {
                                                            echo '<div class="col-12 col-md-6"><iframe width="560" height="315" src="https://www.youtube.com/embed/' . $link[1] . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>';
                                                        } else {
                                                            if (!empty($youtube_url)) {
                                                                preg_match('/\/shorts\/([a-zA-Z0-9_-]{11})/', $youtube_url, $matches);
                                                                if (isset($matches[1])) {
                                                                    $v_id = $matches[1];
                                                                    echo '<div class="col-12 col-md-6"><iframe width="560" height="315" src="https://www.youtube.com/embed/' . $v_id . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                ?>
                                                <?php
                                                $vimio_video_url = maybe_unserialize($meta['vimio_video_url'][0]);
                                                if (is_array($vimio_video_url) && count($vimio_video_url) > 0) {
                                                    foreach ($vimio_video_url as $link) {
                                                        //$link=explode('?v=',$id);
                                                        if (!empty($link)) {
                                                            echo '<div class="col-12 col-md-6">
                                                                    <iframe src="' . $link . '" width="640" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                                                    </div>
                                                                    ';
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- inner-tab-are end -->
                        </div>
                        <?php
                        if ($meta['disable_tribute'][0] == false) {
                        ?>


                            <?php
                            if (!empty($meta['charity_link'][0]) && !empty($meta['charity_image'][0])) :
                            ?>
                                <div class="heading-text">
                                    <h3 class="heading custom-text" style="line-height: 1.1em;">Donate In their Memory</h3>
                                </div>
                                <div class="charity-img">
                                    <div class="charity-wrap">
                                        <div class="charity-left-img">
                                            <a href="<?php echo $meta['charity_link'][0]; ?>" target="_blank">
                                                <img src="<?php echo wp_get_attachment_url($meta['charity_image'][0]); ?>" alt="Charity logo" style="margin-bottom: 0;">
                                                <p><b>Click to Donate</b></p>
                                            </a>
                                            <div><?php echo $meta['chairty_question'][0]; ?></div>
                                        </div>
                                        <!-- <div class="charity-text">
                                            
                                        </div> -->
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                            <div id="tributes-area" class="tab-content active tab-pane show">
                                <div class="heading-text">
                                    <h3 class="heading custom-text">Tributes</h3>
                                </div>
                                <div class="leave-tributes-b">
                                    <a href="#add-tribute" class="leave-tribute-btn"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/tribute.svg" class="icon-sa" alt="icon"> Leave a Tribute</a>
                                </div>
                                <div id="wrapTributes" class="wrapTributes">
                                    <?php



                                    if ($tributes_query->have_posts()) {
                                        while ($tributes_query->have_posts()) {
                                            $tributes_query->the_post();

                                            $tribute_id = get_the_ID();

                                            $timeGemId = get_post_meta($tribute_id, 'time_gem_id', true);
                                            $imgTribute = get_post_meta($tribute_id, 'tribute_img', true);
                                            //$author_name= get_post_meta( $tribute_id, 'author_name', true );

                                            $img = "";
                                            if ($imgTribute == 1) {
                                                $img = get_stylesheet_directory_uri() . "/assets/give-flower.svg";
                                            }
                                            if ($imgTribute == 2) {
                                                $img = get_stylesheet_directory_uri() . "/assets/send-love-blue.svg";
                                            }
                                            if ($imgTribute == 3) {
                                                $img = get_stylesheet_directory_uri() . "/assets/give-hug-blue.svg";
                                            }
                                    ?>

                                            <div class="card comment-body">
                                                <div class="card-body">

                                                    <div class="row">
                                                        <div class="col-md-2">

                                                            <?php
                                                            if ($img) {
                                                                echo '<img src="' . $img . '" class="img img-fluid icon-s"/>';
                                                            }
                                                            ?>

                                                            <p class="text-secondary text-center post-time"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></p>

                                                        </div>
                                                        <div class="col-md-10 tributes-sms-share">
                                                            <h4 class="arthur">
                                                                <a class="" href="<?php the_permalink(); ?>"><strong><?php the_title(); ?></strong></a>
                                                            </h4>
                                                            <div class="clearfix"></div>
                                                            <?php the_content(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                    <?php
                                        }
                                        // Restore the global post data
                                        wp_reset_postdata();
                                    }
                                    ?>
                                </div>

                                <!--<button type="button" id="clickme">Click me!</button>-->

                                <button id="prevButton" style="display:none;">Previous</button>
                                <div id="add-tribute">
                                    <h3>Leave a Tribute</h3>
                                    <form action="" id="tribute_form">
                                        <div class="t-sub">

                                            <input type="radio" id="give-flowers" name="tribute_type" value="1" checked><label for="give-flowers"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/give-flower-white.svg" class="icon-sa" alt="icon"> Give flowers</label>
                                            <input type="radio" id="give-hug" name="tribute_type" value="2"><label for="give-hug"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/give-hug-white.svg" class="icon-sa" alt="icon"> Give a hug</label>
                                            <input type="radio" id="send-love" name="tribute_type" value="3"><label for="send-love"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/send-love-white.svg" class="icon-sa" alt="icon"> Send love</label>
                                        </div>
                                        <textarea id="tribute_body" name="tribute_body" class="form-control form-control-sm mb-3" rows="6" placeholder="Leave your tribute here"></textarea>
                                        <?php
                                        $current_user = wp_get_current_user();
                                        $user_ID = get_current_user_id();
                                        if (($current_user instanceof WP_User) && $user_ID > 0) {

                                        ?>
                                            <div>
                                                <style>
                                                    #add-tribute p {
                                                        margin: 0px;
                                                        padding: 0px;
                                                    }

                                                    a,
                                                    button {
                                                        outline: none;
                                                        box-shadow: none !important;
                                                    }

                                                    .switch {
                                                        position: relative;
                                                        display: inline-block;
                                                        width: 50px;
                                                        height: 28px;
                                                    }

                                                    .switch input {
                                                        opacity: 0;
                                                        width: 0;
                                                        height: 0;
                                                    }

                                                    .slider {
                                                        position: absolute;
                                                        cursor: pointer;
                                                        top: 0;
                                                        left: 0;
                                                        right: 0;
                                                        bottom: 0;
                                                        background-color: #ccc;
                                                        -webkit-transition: .4s;
                                                        transition: .4s;
                                                    }

                                                    .slider:before {
                                                        position: absolute;
                                                        content: "";
                                                        height: 20px;
                                                        width: 20px;
                                                        left: 4px;
                                                        bottom: 4px;
                                                        background-color: white;
                                                        -webkit-transition: .4s;
                                                        transition: .4s;
                                                    }

                                                    input:checked+.slider {
                                                        background-color: #6f13cc;
                                                        ;
                                                    }

                                                    input:focus+.slider {
                                                        box-shadow: 0 0 1px #6f13cc;
                                                        ;
                                                    }

                                                    input:checked+.slider:before {
                                                        -webkit-transform: translateX(20px);
                                                        -ms-transform: translateX(20px);
                                                        transform: translateX(20px);
                                                    }

                                                    /* Rounded sliders */
                                                    .slider.round {
                                                        border-radius: 34px;
                                                    }

                                                    .slider.round:before {
                                                        border-radius: 50%;
                                                    }

                                                    .loding_spin {
                                                        animation: rotetSpin 3s linear infinite;
                                                        display: inline-block;
                                                        font-size: 18px;
                                                        line-height: 0;
                                                    }

                                                    @keyframes rotetSpin {
                                                        0% {
                                                            transform: rotate(360deg);
                                                        }

                                                        100% {
                                                            transform: rotate(0deg);
                                                        }
                                                    }

                                                    #on_behalf_of {
                                                        display: flex;
                                                        justify-content: start;
                                                    }

                                                    .btn-wrap {
                                                        text-align: right;
                                                    }
                                                </style>
                                                <p style="display:none;">
                                                    <strong>I'm the author</strong>
                                                    <label class="switch">
                                                        <input name="author" type="checkbox" checked>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </p>
                                                <input type="hidden" name="mauthor" value="<?php echo  $current_user->user_firstname . ' ' . $current_user->user_lastname; ?>">
                                                <input type="hidden" name="mpost_date" value="<?php echo date('Y-m-d H:i:s'); ?>">
                                                <input type="hidden" name="time_gem_id" value="<?php echo $id; ?>">
                                                <p id="not_iam" style="display:none;">Posting on behalf of</p>

                                                <div id="on_behalf_of">
                                                    <div class="form-grop mr-2">
                                                        <label>Name</label>
                                                        <input type="text" name="author_name" value="<?php echo  $current_user->user_firstname . ' ' . $current_user->user_lastname; ?>">

                                                        <!-- <input type="text" name="author_name"value="<?php // echo  $current_user->user_firstname.' '.$current_user->user_lastname;
                                                                                                            ?>"> -->
                                                    </div>
                                                    <div class="form-grop">
                                                        <label>Date of post</label>
                                                        <input type="date" name="post_date">
                                                    </div>
                                                </div>
                                            </div>
                                            <p id="tribute_message" style="margin-top:20px;"></p>
                                            <div class="btn-wrap tributes-publish-btn">
                                                <!-- <button style="margin-top:20px;" type="button" class="btn btn-publish text-right" id="publish_tribute">Publish</button> -->

                                                <button type="button" style="margin-top:20px;" class="btn btn-publish text-right nectar-button jumbo see-through-2  has-icon time-gem-btn-ok" id="publish_tribute"><span>Publish</span><i class="icon-button-arrow"></i></button>
                                            <?php
                                        } else {
                                            echo '<a href="' . get_site_url() . '/my-account/"><button style="margin-top:20px;" type="button" class="btn btn-publish" id="publish_tribute">Publish</button></a>';
                                        }
                                            ?>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- <div class="row">
    <div class="card-deck col-9">


        <div class="card">
            <a data-fancybox="gallery" href="#myVideo">
                <img class="card-img-top img-fluid" src="https://www.html5rocks.com/en/tutorials/video/basics/poster.png" />
            </a>



            <video width="640" height="320" controls id="myVideo" style="display:none;">
                <source src="https://thetimegem.com/wp-content/uploads/2024/04/73426905191__C63B728E-A6FF-4498-9EAC-10B562B228FC.mov" type="video/mp4">

                Your browser doesn't support HTML5 video tag.
            </video>


        </div>
    </div>
</div> -->
<script src="<?php echo get_stylesheet_directory_uri(); ?>/public-post.js?v=1"></script>
<script>
    $(document).ready(function() {
        $('input[name=author]').on('click', function() {
            if (this.checked) {
                $("#iam").show();
                $("#on_behalf_of").hide();
            } else {
                $("#iam").hide();
                $("#on_behalf_of").show();
            }
        });
        let i = 1;
        let ajaxRequest = 2;



        $('#clickme').on('click', function() {
            if (ajaxRequest) {
                i++;

                $.post(
                    '<?php echo admin_url('admin-ajax.php'); ?>', {
                        'action': 'get_my_ajax_data',
                        'id': <?php echo $id; ?>,
                        'paged': i
                    },
                    function(result) {
                        $('#wrapTributes').append(result);

                        if (result.trim() === '') {
                            ajaxRequest = false;
                            $('#clickme').hide();
                        }
                    });
            }
        });
    });



    jQuery(document).ready(function() {

        // $.fancybox.open($('.video-gallery-fancy'), {
        //     slideShow: {
        //         autoStart: false,
        //     },
        //     afterShow: function(instance, current) {
        //         $(".fancybox-video[data-controls='true']").attr('controls', true);
        //     }
        // });

        let intervalId = 0;

        $().fancybox({
            selector: '.video-gallery-fancy',
            afterLoad: function(instance, current) {
                clearInterval(intervalId);
                $(".fancybox-video[data-controls='true']").attr('controls', true);
                intervalId = setInterval(function() {
                    $(".fancybox-video[data-controls='true']").off();
                }, 250)


            },


        });
        // $('[data-fancybox]').fancybox({
        //     afterShow: function(instance, current) {
        //         alert("sesefef")
        //     }
        // })

        /* var isiPhone = /iPhone/i.test(navigator.userAgent);
         var fancyBoxOptions = {
             iframe: {
                 tpl: '<video class="fancybox-video" controls="true" controlsList="nodownload" poster="{{poster}}" preload="auto" muted >' +
                     '<source src="{{src}}" type="video/mp4" />' +
                     '</video>'
             }
         };
         if (isiPhone) {
             fancyBoxOptions.type = 'image';
         } else {
             fancyBoxOptions.type = 'iframe';
         }

         jQuery(".fancybox-video-gallery").fancybox(fancyBoxOptions);*/
        //jQuery(window).bind("resize.fb", $fancybox.resize);


        // jQuery(".fancybox-video-gallery").fancybox({
        //     type: 'iframe',
        //     iframe: {
        //         tpl: '<video class="fancybox-video" controls controlsList="nodownload" poster="{{poster}}" autoplay loop playsinline muted>' +
        //             '<source src="{{src}}" type="{{format=="video/mov" ? "video/mp4" : format }}" />' +
        //             'Sorry, your browser doesn\'t support embedded videos, <a href="{{src}}">download</a> and watch with your favorite video player!' +
        //             "</video>"
        //     }


        /*   afterShow: function() {
               //console.log(this.$content);
               // After the show-slide-animation has ended - play the video
               //  this.content.find('video').trigger('play')
               // console.log('test')

           }*/
        // });

        // var vids = jQuery(".fancybox-list-container video");
        // jQuery.each(vids, function() {
        //     this.controls = true;
        // });
        // jQuery(".fancybox-image-gallery").fancybox({
        //     'transitionIn': 'elastic',
        //     'transitionOut': 'elastic',
        //     'speedIn': 600,
        //     'speedOut': 200,
        //     'overlayShow': false
        // });


    });


    // $('[data-fancybox="videos"]').fancybox({
    //     arrows: true,
    //     afterShow: function(instance, current) {
    //         console.log(instance, current);
    //         $(".fancybox-video[data-controls='true']").attr('controls', true);
    //     }
    // });

    // });
</script>
<style type="text/css">
    .video-gallery h3 {
        text-align: center;
        margin: 10px 0 15px;
    }

    .fancybox-list-container {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .fancybox-list-container>li {
        display: inline-block;
        margin-right: 10px;
    }

    .fancybox-list-container>li:first-child {
        padding-left: 0;
    }

    .video-gallery-fancy {
        display: block;

    }

    .video-gallery-fancy video {
        object-fit: cover;
    }

    ul.video-links.fancybox-list-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 4px 8px;
    }

    ul.video-links.fancybox-list-container .video-gallery-fancy video {
        width: 100% !important;
    }

    @media(max-width:767px) {
        ul.video-links.fancybox-list-container {
            grid-template-columns: repeat(1, 1fr);
        }
    }
</style>

<?php

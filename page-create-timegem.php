<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js" integrity="sha512-eYSzo+20ajZMRsjxB6L7eyqo5kuXuS2+wEbbOkpaur+sA2shQameiJiWEzCIDwJqaB0a4a6tCuEvCOBHUg3Skg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/create-time-gem.css?v=123">
<div class="form-wrapper">
    <div class="container">
        <?php
        $author = get_current_user_id();
        $draft = get_user_meta($author, 'my_draft_time_gem', true);
        //print_r($draft);
        if (!is_array($draft)) {
            $draft = array();
        }

        ?>
        <div class="form-wrap">
            <form id="time-gem-form">
                <input type="hidden" name="action" value="create_my_time_gem">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="title-label" for="title">Title *</label>
                            <input value="<?php echo (isset($draft['title']) ? $draft['title'] : ''); ?>" type="text" name="title" id="Title" placeholder="Enter your title" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row story-area">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label id="story-label" for="title">Share their life story / about *</label>
                            <textarea name="story" id="story" cols="30" rows="10" class="form-control" required placeholder="Life story / about"><?php echo (isset($draft['story']) ? $draft['story'] : ''); ?></textarea>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <button class="btn button profile_image" type="button">Upload profile image</button>
                            <div id="profile_image">
                                <?php
                                if (isset($draft['profile_image'])) {
                                    if ($draft['profile_image'] > 0) {
                                        echo "<div class='time-gem-single-attachement'>
                                            <img src='" . wp_get_attachment_url($draft['profile_image']) . "'>
                                            <button type='button' onclick='woox_remove_selected_img(this)'>X</button>
                                            <input type='hidden' name='profile_image' value='" . $draft['profile_image'] . "'>
                                        </div>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="name-label" for="date_of_birth">Birth Date *</label>
                            <input value="<?php echo (isset($draft['date_of_birth']) ? $draft['date_of_birth'] : ''); ?>" type="text" name="date_of_birth" id="date_of_birth" placeholder="Birth Date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="email-label" for="passing_date">Passing Date *</label>
                            <input value="<?php echo (isset($draft['passing_date']) ? $draft['passing_date'] : ''); ?>" type="text" name="passing_date" id="passing_date" placeholder="Passing Date" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button class="btn button rudr-upload" type="button">Upload gallery images</button>
                        </div>

                        <div id="attachement_images">
                            <?php
                            if (isset($draft['attachments'])) {
                                if (count($draft['attachments']) > 0) {
                                    foreach ($draft['attachments'] as $id) {
                                        echo "<div class='time-gem-single-attachement'>
                                            <img src='" . wp_get_attachment_url($id) . "'>
                                            <button type='button' onclick='woox_remove_selected_img(this)'>X</button>
                                            <input type='hidden' name='attachments[]' value='" . $id . "'>
                                            </div>";
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div id="add-video-url">
                                <label id="video-label">Add Youtube video URL</label>
                                <?php
                                $youtube_url = false;
                                if (isset($draft['youtube_video_url'])) {
                                    if (count($draft['youtube_video_url']) > 0) {
                                        foreach ($draft['youtube_video_url'] as $url) {
                                            echo '<div class="inner-area">
                                                    <a class="close-icon"><i class="fas fa-times-circle"></i></a>
                                                    <input value="' . $url . '" type="text" name="youtube_video_url[]" class="form-control form-group">
                                                </div>';
                                            $youtube_url = true;
                                        }
                                    }
                                }

                                if (!$youtube_url) {
                                ?>
                                    <div class="inner-area">
                                        <a class="close-icon"><i class="fas fa-times-circle"></i></a>
                                        <input type="text" name="youtube_video_url[]" class="form-control form-group">
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <button type="button" id="btn3" class="btn"><i class="fas fa-plus"></i> Add more</button>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div id="add-vimio-url">
                                <label id="video-label">Add vimeo video URL</label>
                                <?php
                                $vimio_url = false;
                                if (isset($draft['vimio_video_url'])) {
                                    if (count($draft['vimio_video_url']) > 0) {
                                        foreach ($draft['vimio_video_url'] as $url) {
                                            echo '<div class="inner-area">
                                                    <a class="close-icon"><i class="fas fa-times-circle"></i></a>
                                                    <input value="' . $url . '" type="text" name="vimio_video_url[]" class="form-control form-group">
                                                </div>';
                                            $vimio_url = true;
                                        }
                                    }
                                }

                                if (!$vimio_url) {
                                ?>
                                    <div class="inner-area">
                                        <a class="close-icon"><i class="fas fa-times-circle"></i></a>
                                        <input type="text" name="vimio_video_url[]" class="form-control form-group">
                                    </div>
                                <?php
                                }
                                ?>

                            </div>
                            <button type="button" id="btn4" class="btn"><i class="fas fa-plus"></i> Add more</button>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="question_1_label" for="question_1">Early Life: Where did they grow up, and what were some significant moments from their childhood?</label>
                            <input value="<?php echo (isset($draft['question_1']) ? $draft['question_1'] : ''); ?>" type="text" name="question_1" id="question_1" placeholder="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="question_2_label" for="question_2">Education & Career: What educational achievements did they have, and what career path did they pursue?</label>
                            <input value="<?php echo (isset($draft['question_2']) ? $draft['question_2'] : ''); ?>" type="text" name="question_2" id="question_2" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_3_label" for="question_3">Personal Interests: What were some of their hobbies, interests, or passions?</label>
                            <input value="<?php echo (isset($draft['question_3']) ? $draft['question_3'] : ''); ?>" type="text" name="question_3" id="question_3" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_4_label" for="question_4">Achievements & Milestones: What were some of their proudest achievements or milestones in life?</label>
                            <input value="<?php echo (isset($draft['question_4']) ? $draft['question_4'] : ''); ?>" type="text" name="question_4" id="question_4" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_5_label" for="question_5">Family & Relationships: Who were their immediate family members, and what were some special relationships they cherished?</label>
                            <input value="<?php echo (isset($draft['question_5']) ? $draft['question_5'] : ''); ?>" type="text" name="question_5" id="question_5" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="question_6_label" for="question_6">Philanthropic Activities: Did they engage in any charitable or community activities?</label>
                            <input value="<?php echo (isset($draft['question_6']) ? $draft['question_6'] : ''); ?>" type="text" name="question_6" id="question_6" placeholder="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="question_7_label" for="question_7">Lessons Learned: What were some valuable life lessons they imparted to others?</label>
                            <input value="<?php echo (isset($draft['question_7']) ? $draft['question_7'] : ''); ?>" type="text" name="question_7" id="question_7" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="question_8_label" for="question_8">Sense of Humor: Share some humorous or light-hearted stories that reflect their sense of humour.</label>
                            <input value="<?php echo (isset($draft['question_8']) ? $draft['question_8'] : ''); ?>" type="text" name="question_8" id="question_8" placeholder="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="question_9_label" for="question_9">Inspirational Quotes: Did they have any favourite quotes or sayings that resonated with them?</label>
                            <input value="<?php echo (isset($draft['question_9']) ? $draft['question_9'] : ''); ?>" type="text" name="question_9" id="question_9" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="question_10_label" for="question_10">Personal Values: What were some core values they lived by?.</label>
                            <input value="<?php echo (isset($draft['question_10']) ? $draft['question_10'] : ''); ?>" type="text" name="question_10" id="question_10" placeholder="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="question_11_label" for="question_11">Fond Memories: Share a personal memory that highlights their uniqueness and impact on your life.</label>
                            <input value="<?php echo (isset($draft['question_11']) ? $draft['question_11'] : ''); ?>" type="text" name="question_11" id="question_11" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label id="charity_link_label" for="charity_link">Add links to which charity you like people to donate to + add image of charity.</label>
                            <input value="<?php echo (isset($draft['charity_link']) ? $draft['charity_link'] : ''); ?>" type="text" name="charity_link" id="charity_link" placeholder="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button class="btn button charity_image" type="button">Upload image</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="charity_image">
                            <?php
                            if (isset($draft['charity_image'])) {
                                if ($draft['charity_image'] > 0) {
                                    echo "<div class='time-gem-single-attachement'>
                                            <img src='" . wp_get_attachment_url($draft['charity_image']) . "'>
                                            <button type='button' onclick='woox_remove_selected_img(this)'>X</button>
                                            <input type='hidden' name='charity_image' value='" . $draft['charity_image'] . "'>
                                        </div>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div id="pro-service-div">
                                <label id="social-label" for="social">Connect social media profiles</label>
                                <?php
                                $social_url = false;
                                if (isset($draft['social_media_link'])) {
                                    if (count($draft['social_media_link']) > 0) {
                                        foreach ($draft['social_media_link'] as $url) {
                                            echo '<div class="inner-area">
                                                    <a class="close-icon"><i class="fas fa-times-circle"></i></a>
                                                    <select class="btn btn-select-primary" name="social_icon[]">  
                                                        <option class="facebook fa" value="fa-facebook">Facebook</option>    
                                                        <option class="twitter fa" value="fa-twitter">Twitter</option>          
                                                        <option  class="instagram fa" value="fa-instagram">Instagram</option>         
                                                        <option class="linkedin fa" value="fa-linkedin">Linkedin</option>     
                                                        <option class="pinterest fa"  value="fa-pinterest">Pinterest</option>            
                                                        <option class="google-plus fa" value="fa-google-plus">Google Plus</option>             
                                                        <option class="other fa" value="fa-globe">Other</option>
                                                    </select>
                                                    <input value="' . $url . '" type="text" name="social_media_link[]" class="form-control form-group">
                                                </div>';
                                            $social_url = true;
                                        }
                                    }
                                }

                                if (!$social_url) {
                                ?>
                                    <div class="inner-area">
                                        <a class="close-icon"><i class="fas fa-times-circle"></i></a>
                                        <select class="btn btn-select-primary" name="social_icon[]">
                                            <option class="facebook fa" value="fa-facebook">Facebook</option>
                                            <option class="twitter fa" value="fa-twitter">Twitter</option>
                                            <option class="instagram fa" value="fa-instagram">Instagram</option>
                                            <option class="linkedin fa" value="fa-linkedin">Linkedin</option>
                                            <option class="pinterest fa" value="fa-pinterest">Pinterest</option>
                                            <option class="google-plus fa" value="fa-google-plus">Google Plus</option>
                                            <option class="other fa" value="fa-globe">Other</option>
                                        </select>
                                        <input type="text" name="social_media_link[]" class="form-control form-group">
                                    </div>
                                <?php
                                }
                                ?>

                            </div>
                            <button type="button" id="btn2" class="btn"><i class="fas fa-plus"></i> Add more</button>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Time Gem Options</label>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input <?php echo (isset($draft['post_private_and_password']) ? 'checked' : ''); ?> type="checkbox" class="custom-control-input" name="post_private_and_password" value="1" id="post_private_and_password">
                                <label class="custom-control-label" for="post_private_and_password">Make post private and password protected</label>
                            </div>
                            <div style="<?php echo (isset($draft['post_private_and_password']) ? '' : 'display:none;'); ?>" id="time_gem_password_area">
                                <input type="email" style="display:none;">
                                <input value="<?php echo (isset($draft['post_password']) ? $draft['post_password'] : ''); ?>" autocomplete="off" type="text" name="post_password" id="post_password" placeholder="Password" class="form-control">
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input <?php echo (isset($draft['disable_tribute']) ? 'checked' : ''); ?> type="checkbox" class="custom-control-input" name="disable_tribute" value="1" id="disable_tribute">
                                <label class="custom-control-label" for="disable_tribute">Disable tributes on my Time Gem</label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $bg = '';
                if (isset($draft['background-image'])) {
                    $bg = $draft['background-image'];
                }

                $bg1 = get_site_url() . '/wp-content/uploads/2023/10/header_grass.jpg';
                $bg2 = get_site_url() . '/wp-content/uploads/2023/10/Header_white_flowers.jpg';
                $bg3 = get_site_url() . '/wp-content/uploads/2023/10/header_sky.jpg';
                $bg4 = get_site_url() . '/wp-content/uploads/2023/10/header_waterfall.jpg';
                $bg5 = get_site_url() . '/wp-content/uploads/2023/10/header_space.jpg';
                $bg6 = get_site_url() . '/wp-content/uploads/2023/10/header_ocean.jpg';
                $bg7 = get_site_url() . '/wp-content/uploads/2023/10/header_mountain.jpg';
                $bg8 = get_site_url() . '/wp-content/uploads/2023/10/header_pink_flowers.jpg';
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="name-label" for="name">Choose background image for header section</label>
                            <div class="c-bg">
                                <label class="img-btn">
                                    <input <?php echo ($bg == $bg1 ? 'checked' : ''); ?> type="radio" name="background-image" value="<?php echo $bg1; ?>">
                                    <img src="<?php echo $bg1; ?>" alt="Trees">
                                </label>

                                <label class="img-btn">
                                    <input <?php echo ($bg == $bg2 ? 'checked' : ''); ?> type="radio" name="background-image" value="<?php echo $bg2; ?>">
                                    <img src="<?php echo $bg2; ?>" alt="flowers">
                                </label>

                                <label class="img-btn">
                                    <input <?php echo ($bg == $bg3 ? 'checked' : ''); ?> type="radio" name="background-image" value="<?php echo $bg3; ?>">
                                    <img src="<?php echo $bg3; ?>" alt="sky">
                                </label>

                                <label class="img-btn">
                                    <input <?php echo ($bg == $bg4 ? 'checked' : ''); ?> type="radio" name="background-image" value="<?php echo $bg4; ?>">
                                    <img src="<?php echo $bg4; ?>" alt="beach">
                                </label>

                                <label class="img-btn">
                                    <input <?php echo ($bg == $bg5 ? 'checked' : ''); ?> type="radio" name="background-image" value="<?php echo $bg5; ?>">
                                    <img src="<?php echo $bg5; ?>" alt="Space">
                                </label>

                                <label class="img-btn">
                                    <input <?php echo ($bg == $bg6 ? 'checked' : ''); ?> type="radio" name="background-image" value="<?php echo $bg6; ?>">
                                    <img src="<?php echo $bg6; ?>" alt="PCean">
                                </label>

                                <label class="img-btn">
                                    <input <?php echo ($bg == $bg7 ? 'checked' : ''); ?> type="radio" name="background-image" value="<?php echo $bg7; ?>">
                                    <img src="<?php echo $bg7; ?>" alt="Nature">
                                </label>

                                <label class="img-btn">
                                    <input <?php echo ($bg == $bg8 ? 'checked' : ''); ?> type="radio" name="background-image" value="<?php echo $bg8; ?>">
                                    <img src="<?php echo $bg8; ?>" alt="River">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">


                    <div class="col-md-12">
                        <div class="form-group">
                            <button class="btn button background_image_upload" type="button">Upload own image</button>
                        </div>

                        <div id="own_background_image">
                            <?php
                            if (!empty($bg)) {
                                if (
                                    $bg != $bg1 &&
                                    $bg != $bg2 &&
                                    $bg != $bg3 &&
                                    $bg != $bg4 &&
                                    $bg != $bg5 &&
                                    $bg != $bg6 &&
                                    $bg != $bg7 &&
                                    $bg != $bg8
                                ) {
                                    echo "<div class='time-gem-single-attachement'>
                                        <img src='" . $bg . "'>
                                        <button type='button' onclick='woox_remove_selected_img(this)'>X</button>
                                        <input style='display:none' checked type='radio' name='background-image' value='" . $bg . "'>
                                        </div>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="messages"></div>
                </div>
                <div class="row btn-area">
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="button" id="submit1" class="btn btn-primary btn-block">Save and submit</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="button" id="submit2" class="btn btn-primary btn-block">Save as draft</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="button" id="submit3" class="btn btn-primary btn-block">Delete</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/create-time-gem.js"></script>
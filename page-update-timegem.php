<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js" integrity="sha512-eYSzo+20ajZMRsjxB6L7eyqo5kuXuS2+wEbbOkpaur+sA2shQameiJiWEzCIDwJqaB0a4a6tCuEvCOBHUg3Skg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/create-time-gem.css?v=13522">
<style>
    .x-opacity {
        opacity: 0;
    }
</style>
<div class="form-wrapper">
    <div class="container">
        <?php
        $id = base64_decode($_GET['selected']);
        $author = get_current_user_id();
        $draft = get_post_meta($id);
        $title = get_post_field('post_title', $id);
        $story = (isset($draft['story'][0]) ? $draft['story'][0] : '');
        $slug = get_post_field('post_name', $id);
        // page-update-timegem.php
        ?>
        <div class="form-wrap time-gem-update-form">
            <form id="update-time-gem-form">
                <input type="hidden" name="action" value="update_my_time_gem">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="title-label" for="title">Your Loved One’s Full Name *</label>
                            <input value="<?php echo $title; ?>" type="text" name="title" id="Title" placeholder="Full Name" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="story-label" for="title">Share Their Life Story *<br /><small>(This is where you can share where they grew up, what their values were, your favourite memories of them, etc.)</small></label>
                        </div>
                        <?php
                        wp_editor($story, 'story', array(
                            'wpautop'       => true,
                            'media_buttons' => false,
                            'textarea_name' => 'story',
                            'editor_class'  => 'story',
                            'textarea_rows' => 10
                        ));
                        ?>
                    </div>
                </div>
                <?php
                $profileImg = '';
                if (isset($draft['profile_image'][0])) {
                    if ($draft['profile_image'][0] > 0) {
                        $profileImg = wp_get_attachment_url($draft['profile_image'][0]);
                    }
                }


                $Profile_image_cstm_class = "profile_not_uploaded";
                if (!empty($profileImg)) {
                    $Profile_image_cstm_class = "profile_uploaded";
                }

                ?>
                <div id="profileImageWrap" class="row <?php echo $Profile_image_cstm_class; ?>">
                    <div class="col-md-12">
                        <div class="form-group">

                            <!-- <input type="hidden" name="action" value="profile_img_upload"> -->
                            <!-- <input type="file" id="image" name="image" onchange="profile_img()" class="img-hidden"> -->
                            <h2 id="profileShowImg" style="display: none;">
                                <?php
                                if (isset($draft['profile_image'][0])) {
                                    if ($draft['profile_image'][0] > 0) {
                                        echo "
                                            <img src='" . wp_get_attachment_url($draft['profile_image'][0]) . "' width='200' data-old-img=" . $draft['profile_image'][0] . " >
                                        ";
                                    }
                                }
                                ?>
                            </h2>

                            <!-- profile_image -->
                            <button id="profileBtn" class="btn button bg-own-img" type="button" style=" <?php echo ($profileImg ? 'background-image:url(' . $profileImg . ')' : '') ?>">
                                <input type="file" id="image" name="image" onchange="profile_img()" class="img-hidden" accept=".png, .jpg, .jpeg, .gif">
                                <svg xmlns="https://www.w3.org/2000/svg" height="60px" viewBox="0 0 512 512">
                                    <style>
                                        svg {
                                            fill: #8224e3
                                        }
                                    </style>
                                    <path d="M96 352V96c0-35.3 28.7-64 64-64H416c35.3 0 64 28.7 64 64V293.5c0 17-6.7 33.3-18.7 45.3l-58.5 58.5c-12 12-28.3 18.7-45.3 18.7H160c-35.3 0-64-28.7-64-64zM272 128c-8.8 0-16 7.2-16 16v48H208c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h48v48c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V256h48c8.8 0 16-7.2 16-16V208c0-8.8-7.2-16-16-16H320V144c0-8.8-7.2-16-16-16H272zm24 336c13.3 0 24 10.7 24 24s-10.7 24-24 24H136C60.9 512 0 451.1 0 376V152c0-13.3 10.7-24 24-24s24 10.7 24 24l0 224c0 48.6 39.4 88 88 88H296z" />
                                </svg>
                                <p style="padding-bottom: 1px;" class="img-info-text">Upload profile image</p>
                                <span class="img-info-text" style="font-size:12px; padding-bottom: 1px;">Maximum size 500KB</span>

                            </button>

                            <p id="smsProfile" style="text-align: center;"></p>
                            <div id="overlay" name="smsProfile" style="display: none;">
                                <div class="cv-spinner">
                                    <span class="spinner"></span>
                                </div>
                            </div>


                            <div id="profile_image" style="display: none;">
                                <?php
                                if (isset($draft['profile_image'][0])) {
                                    if ($draft['profile_image'][0] > 0) {
                                        echo "<div class='time-gem-single-attachement'>
                                            <img src='" . wp_get_attachment_url($draft['profile_image'][0]) . "'>
                                            <button type='button' onclick='woox_remove_selected_img(this)'>X</button>
                                            <input type='hidden' name='profile_image' value='" . $draft['profile_image'][0] . "'>
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
                            <input value="<?php echo (isset($draft['date_of_birth'][0]) ? $draft['date_of_birth'][0] : ''); ?>" type="date" name="date_of_birth" id="date_of_birth" placeholder="Birth Date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label id="email-label" for="passing_date">Passing Date *</label>
                            <input value="<?php echo (isset($draft['passing_date'][0]) ? $draft['passing_date'][0] : ''); ?>" type="date" name="passing_date" id="passing_date" placeholder="Passing Date" class="form-control" required>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <!-- rudr-upload onchange="galleryImages()" -->
                            <button id="galleryBtn" class="btn button bg-own-img" type="button" style="">
                                <input accept="image/*,video/*" type="file" multiple id="images_gallery" name="images_gallery[]" class="img-hidden">
                                <svg xmlns="https://www.w3.org/2000/svg" height="60px" viewBox="0 0 512 512">
                                    <style>
                                        svg {
                                            fill: #8224e3
                                        }
                                    </style>
                                    <path d="M96 352V96c0-35.3 28.7-64 64-64H416c35.3 0 64 28.7 64 64V293.5c0 17-6.7 33.3-18.7 45.3l-58.5 58.5c-12 12-28.3 18.7-45.3 18.7H160c-35.3 0-64-28.7-64-64zM272 128c-8.8 0-16 7.2-16 16v48H208c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h48v48c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V256h48c8.8 0 16-7.2 16-16V208c0-8.8-7.2-16-16-16H320V144c0-8.8-7.2-16-16-16H272zm24 336c13.3 0 24 10.7 24 24s-10.7 24-24 24H136C60.9 512 0 451.1 0 376V152c0-13.3 10.7-24 24-24s24 10.7 24 24l0 224c0 48.6 39.4 88 88 88H296z" />
                                </svg>
                                <p style="padding-bottom: 1px;">Upload Images and Videos in Your Gallery</p>
                                <!-- <span style="font-size:12px; padding-bottom: 1px;">Maximum size 500KB/Image</span> -->

                            </button>
                            <p id="smsGalleryImages" style="text-align: center;"></p>
                            <div id="overlay" name="smsGalleryImages" style="display: none;">
                                <div class="cv-spinner">
                                    <span class="spinner"></span>
                                </div>
                            </div>
                            <!-- <input name="file" type="text" /> -->
                        </div>

                        <div id="attachement_images">
                            <?php
                            if (isset($draft['attachments'][0])) {
                                $attachments = array();
                                if (!empty($draft['attachments'][0])) {
                                    $attachments = maybe_unserialize($draft['attachments'][0]);
                                }


                                if (count($attachments) > 0) {
                                    foreach ($attachments as $id) {
                                        $mime_type = get_post_mime_type($id);
                                        $post = get_post($id);
                                        $caption = ($post->post_excerpt) ? $post->post_excerpt : '';
                                        if (strpos($mime_type, "video") !== false) {
                                            $video_thumbnail = get_stylesheet_directory_uri() . '/assets/video.png';


                                            echo "<div class='time-gem-single-attachement attachment-wrap'>
                                                <a target='_blank' href='" . wp_get_attachment_url($id) . "'><video width='200' height='140' autoplay loop playsinline muted>
                                                      <source src='" . wp_get_attachment_url($id) . "' type='video/mp4' #t=0.4>
                                                      Your browser does not support the video tag.
                                                    </video></a>
                                            <button type='button' onclick='woox_remove_selected_img(this)'><i class='fas fa-times-circle'></i></button>
                                            
                                        <div class='cap-position'>
                                           <!-- <div class='caption-wrap caption-wrap-163'>
                                                <input type='hidden' name='attachments[]' value='" . $id . "'>
                                                <input type='text' name='attach_caption[]' class='attachment-caption' id='cp" . $id . "' placeholder='Caption here' value='" . $caption . "' >
                                                <button type='button' id='" . $id . "' onclick='addAttachCaption(this)' ><span class='dashicons dashicons-saved'></span></button>
                                            </div>-->
                                        </div>
                                            </div>";
                                        } else {

                                            echo "<div class='time-gem-single-attachement attachment-wrap'>
                                            <img src='" . wp_get_attachment_url($id, 'thumbnail') . "'>
                                            <button type='button' onclick='woox_remove_selected_img(this)'><i class='fas fa-times-circle'></i></button>
                                        <div class='cap-position'>
                                           <!-- <div class='caption-wrap caption-wrap-176'>
                                                <input type='hidden' name='attachments[]' value='" . $id . "'>
                                                <input type='text' name='attach_caption[]' class='attachment-caption' id='cp" . $id . "' placeholder='Caption here' value='" . $caption . "' >
                                                <button type='button' id='" . $id . "' onclick='addAttachCaption(this)' ><span class='dashicons dashicons-saved'></span></button>
                                            </div>-->
                                        </div>
                                            </div>";
                                        }
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
                                <!-- <p><strong>Note:</strong> Open YouTube, select the video, click "Share" > "Embed," copy the iframe src URL, and paste it given field.</p> -->
                                <?php
                                $youtube_url = false;
                                if (isset($draft['youtube_video_url'][0])) {
                                    $youtube_video_urls = maybe_unserialize($draft['youtube_video_url'][0]);
                                    if (count($youtube_video_urls) > 0) {
                                        foreach ($youtube_video_urls as $url) {
                                            echo '<div class="inner-area">
                                                    <a class="close-icon"><i class="fas fa-times-circle"></i></a>
                                                    <input placeholder="eg:- https://www.youtube.com/watch?v=xxxx" value="' . $url . '" type="text" name="youtube_video_url[]" class="form-control form-group">
                                                </div>';
                                            $youtube_url = true;
                                        }
                                    }
                                }

                                if (!$youtube_url) {
                                ?>
                                    <div class="inner-area">
                                        <a class="close-icon"><i class="fas fa-times-circle"></i></a>
                                        <input placeholder="eg:- https://www.youtube.com/watch?v=xxxx" type="text" name="youtube_video_url[]" class="form-control form-group">
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                            <button type="button" id="btn3" class="btn"><i class="fas fa-plus"></i> Add more</button>
                        </div>
                    </div>
                </div>


                <div class="row" style="display:none;">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div id="add-vimio-url">
                                <label id="video-label">Add vimeo video URL</label>
                                <?php
                                $vimio_url = false;
                                if (isset($draft['vimio_video_url'][0])) {
                                    $vimio_video_urls = maybe_unserialize($draft['vimio_video_url'][0]);
                                    if (count($vimio_video_urls) > 0) {
                                        foreach ($vimio_video_urls as $url) {
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_1_label" for="question_1">Early Life: Where did they grow up, and what were some significant moments from their childhood?</label>
                            <input value="<?php echo (isset($draft['question_1'][0]) ? $draft['question_1'][0] : ''); ?>" type="text" name="question_1" id="question_1" placeholder="Write answer in here…" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_2_label" for="question_2">Education & Career: What educational achievements did they have, and what career path did they pursue?</label>
                            <input value="<?php echo (isset($draft['question_2'][0]) ? $draft['question_2'][0] : ''); ?>" type="text" name="question_2" id="question_2" placeholder="Write answer in here…" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_3_label" for="question_3">Personal Interests: What were some of their hobbies, interests, or passions?</label>
                            <input value="<?php echo (isset($draft['question_3'][0]) ? $draft['question_3'][0] : ''); ?>" type="text" name="question_3" id="question_3" placeholder="Write answer in here…" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_4_label" for="question_4">Achievements & Milestones: What were some of their proudest achievements or milestones in life?</label>
                            <input value="<?php echo (isset($draft['question_4'][0]) ? $draft['question_4'][0] : ''); ?>" type="text" name="question_4" id="question_4" placeholder="Write answer in here…" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_5_label" for="question_5">Family & Relationships: Who were their immediate family members, and what were some special relationships they cherished?</label>
                            <input value="<?php echo (isset($draft['question_5'][0]) ? $draft['question_5'][0] : ''); ?>" type="text" name="question_5" id="question_5" placeholder="Write answer in here…" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_6_label" for="question_6">Philanthropic Activities: Did they engage in any charitable or community activities?</label>
                            <input value="<?php echo (isset($draft['question_6'][0]) ? $draft['question_6'][0] : ''); ?>" type="text" name="question_6" id="question_6" placeholder="Write answer in here…" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_7_label" for="question_7">Lessons Learned: What were some valuable life lessons they imparted to others?</label>
                            <input value="<?php echo (isset($draft['question_7'][0]) ? $draft['question_7'][0] : ''); ?>" type="text" name="question_7" id="question_7" placeholder="Write answer in here…" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_8_label" for="question_8">Sense of Humor: Share some humorous or light-hearted stories that reflect their sense of humour.</label>
                            <input value="<?php echo (isset($draft['question_8'][0]) ? $draft['question_8'][0] : ''); ?>" type="text" name="question_8" id="question_8" placeholder="Write answer in here…" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_9_label" for="question_9">Inspirational Quotes: Did they have any favourite quotes or sayings that resonated with them?</label>
                            <input value="<?php echo (isset($draft['question_9'][0]) ? $draft['question_9'][0] : ''); ?>" type="text" name="question_9" id="question_9" placeholder="Write answer in here…" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_10_label" for="question_10">Personal Values: What were some core values they lived by?</label>
                            <input value="<?php echo (isset($draft['question_10'][0]) ? $draft['question_10'][0] : ''); ?>" type="text" name="question_10" id="question_10" placeholder="Write answer in here…" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="question_11_label" for="question_11">Fond Memories: Share a personal memory that highlights their uniqueness and impact on your life.</label>
                            <input value="<?php echo (isset($draft['question_11'][0]) ? $draft['question_11'][0] : ''); ?>" type="text" name="question_11" id="question_11" placeholder="Write answer in here…" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label id="charity_link_label" for="charity_link">Add the link of a charity or organization you would love for visitors to donate to on behalf of your loved one</label>
                            <input value="<?php echo (isset($draft['charity_link'][0]) ? $draft['charity_link'][0] : ''); ?>" type="text" name="charity_link" id="charity_link" placeholder="" class="form-control">
                        </div>
                    </div>
                    <?php
                    $chbg = '';
                    $chbg_class = 'charity_not_uploaded';
                    if (isset($draft['charity_image'][0])) {
                        if ($draft['charity_image'][0] > 0) {
                            $chbg = wp_get_attachment_url($draft['charity_image'][0]);
                            $chbg_class = 'charity_uploaded';
                        }
                    }
                    ?>

                    <!-- <input type="hidden" name="action" value="charity_img_upload"> -->
                    <!-- <input type="file" id="image" name="image" onchange="profile_img()" class="img-hidden"> -->
                    <h2 id="charityShowImg" style="display: none;">
                        <?php
                        if (isset($draft['charity_image'][0])) {
                            if ($draft['charity_image'][0] > 0) {
                                echo "
                                            <img src='" . wp_get_attachment_url($draft['charity_image'][0]) . "' width='200' data-old-img=" . $draft['charity_image'][0] . " >
                                        ";
                            }
                        }
                        ?>

                    </h2>

                    <div id="charity_image_container" class="col-md-4 charity-upload-btn-m <?php echo $chbg_class; ?>">
                        <div class="form-group">
                            <!-- charity_image -->
                            <button id="charityBtn" class="btn button bg-own-img" type="button" style=" <?php echo ($chbg ? 'background-image:url(' . $chbg . ')' : '') ?>">
                                <input type="file" id="img_charity" name="img_charity" onchange="imgcharity()" class="img-hidden">
                                <svg xmlns="https://www.w3.org/2000/svg" height="60px" viewBox="0 0 512 512">
                                    <style>
                                        svg {
                                            fill: #8224e3
                                        }
                                    </style>
                                    <path d="M96 352V96c0-35.3 28.7-64 64-64H416c35.3 0 64 28.7 64 64V293.5c0 17-6.7 33.3-18.7 45.3l-58.5 58.5c-12 12-28.3 18.7-45.3 18.7H160c-35.3 0-64-28.7-64-64zM272 128c-8.8 0-16 7.2-16 16v48H208c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h48v48c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V256h48c8.8 0 16-7.2 16-16V208c0-8.8-7.2-16-16-16H320V144c0-8.8-7.2-16-16-16H272zm24 336c13.3 0 24 10.7 24 24s-10.7 24-24 24H136C60.9 512 0 451.1 0 376V152c0-13.3 10.7-24 24-24s24 10.7 24 24l0 224c0 48.6 39.4 88 88 88H296z" />
                                </svg>
                                <p style="padding-bottom: 1px;">Upload charity logo</p>
                                <span style="font-size:12px; padding-bottom: 1px;">Maximum size 500KB</span>

                            </button>
                            <p id="smsCharity" style="text-align: center;"></p>
                            <div id="overlay" name="smsCharity" style="display: none;">
                                <div class="cv-spinner">
                                    <span class="spinner"></span>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="charity_image" style="display: none;">
                            <?php
                            if (isset($draft['charity_image'][0])) {
                                if ($draft['charity_image'][0] > 0) {
                                    echo "<div class='time-gem-single-attachement'>
                                            <img src='" . wp_get_attachment_url($draft['charity_image'][0]) . "'>
                                            <button type='button' onclick='woox_remove_selected_img(this)'>X</button>
                                            <input type='hidden' name='charity_image' value='" . $draft['charity_image'][0] . "'>
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
                            <label for="chairty_question">Share some words to get visitors to donate on behalf of your loved one</label>
                            <textarea name="chairty_question" id="chairty_question" cols="30" rows="4"><?php echo (isset($draft['chairty_question'][0]) ? $draft['chairty_question'][0] : ''); ?></textarea>
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
                                if (isset($draft['social_media_link'][0])) {
                                    $social_media_links = maybe_unserialize($draft['social_media_link'][0]);
                                    $social_icons = maybe_unserialize($draft['social_icon'][0]);
                                    if (count($social_media_links) > 0) {
                                        foreach ($social_media_links as $k => $url) {
                                            $icon = $social_icons[$k];
                                            echo '<div class="inner-area">
                                                    <a class="close-icon"><i class="fas fa-times-circle"></i></a>
                                                    <select class="btn btn-select-primary" name="social_icon[]">  
                                                        <option class="facebook fa" value="fa-facebook" ' . ($icon == 'fa-facebook' ? 'selected' : '') . '>Facebook</option>    
                                                        <option class="twitter fa" value="fa-x-twitter" ' . ($icon == 'fa-x-twitter' ? 'selected' : '') . '>X</option>          
                                                        <option  class="instagram fa" value="fa-instagram" ' . ($icon == 'fa-instagram' ? 'selected' : '') . '>Instagram</option>         
                                                        <option class="linkedin fa" value="fa-linkedin" ' . ($icon == 'fa-linkedin' ? 'selected' : '') . '>Linkedin</option>     
                                                        <option class="pinterest fa"  value="fa-pinterest" ' . ($icon == 'fa-pinterest' ? 'selected' : '') . '>Pinterest</option>            
                                                        <option class="google-plus fa" value="fa-google-plus" ' . ($icon == 'fa-google-plus' ? 'selected' : '') . '>Google Plus</option>             
                                                        <option class="other fa" value="fa-globe" ' . ($icon == 'fa-globe' ? 'selected' : '') . '>Other</option>
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
                                            <option class="twitter fa" value="fa-x-twitter">X</option>
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
                            <h4><span style="color: #8224e3;"><strong>Time Gem Privacy Options</strong></span></h4>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input <?php echo (isset($draft['post_private_and_password'][0]) ? 'checked' : ''); ?> type="checkbox" class="custom-control-input" name="post_private_and_password" value="1" id="post_private_and_password">
                                <label class="custom-control-label" for="post_private_and_password">Click here to make your Time Gem profile private and password protected</label>
                            </div>
                            <div style="<?php echo (isset($draft['post_private_and_password'][0]) ? '' : 'display:none;'); ?>" id="time_gem_password_area">
                                <input type="email" style="display:none;">
                                <input value="<?php echo (isset($draft['post_password'][0]) ? $draft['post_password'][0] : ''); ?>" autocomplete="off" type="text" name="post_password" id="post_password" placeholder="Password" class="form-control">
                            </div>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input <?php echo ($draft['disable_tribute'][0] == true ? 'checked' : ''); ?> type="checkbox" class="custom-control-input" name="disable_tribute" value="1" id="disable_tribute">
                                <label class="custom-control-label" for="disable_tribute">Disable tributes on my Time Gem</label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                //print_r($draft);
                $bg = '';
                if (isset($draft['time_gem_bg_image'][0])) {
                    $bg = $draft['time_gem_bg_image'][0];
                    if ($bg > 0) $bg = wp_get_attachment_url($bg);
                }

                //print_r($draft);

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
                            <div class="c-bg c-bg-img-m">
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

                        <div id="own_background_image" style="display: none;">
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

                        <?php
                        // $profileImg='';
                        // if(isset($draft['profile_image'][0])){
                        //     if($draft['profile_image'][0]>0){
                        //         $profileImg=wp_get_attachment_url($draft['profile_image'][0]);
                        //     }
                        // }
                        ?> <?php // echo ($profileImg?'background-image:url('.$profileImg.')':'')
                            ?>

                        <?php
                        $background_image_class = 'image_not_uploaded';
                        ?>
                        <!-- <input type="hidden" name="action" value="ownbg_img_upload"> -->
                        <h2 id="ownbgShowImg" style="display: none;">
                            <?php
                            if (isset($draft['time_gem_bg_image'][0])) {
                                if ($draft['time_gem_bg_image'][0] > 0) {
                                    $background_image_class = 'image_uploaded';
                                    echo "
                                            <img src='" . $bg . "' width='200' data-old-img=" . $draft['time_gem_bg_image'][0] . " >
                                        ";
                                }
                            }
                            ?>
                        </h2>

                        <?php
                        $uploaded_bgimg = !empty($draft['time_gem_bg_image'][0]) ? $draft['time_gem_bg_image'][0] : '';
                        ?>

                        <div id="background_image_class" class="form-group">
                            <button id="bgOwnBtn" class="btn button bg-own-img" type="button" style="border-radius: 20px !important;">
                                <input type="file" id="own_bg_img" name="own_bg_img" onchange="ownBgImgage()" class="img-hidden">
                                <svg xmlns="https://www.w3.org/2000/svg" height="80px" viewBox="0 0 512 512">
                                    <style>
                                        svg {
                                            fill: #8224e3
                                        }
                                    </style>
                                    <path d="M96 352V96c0-35.3 28.7-64 64-64H416c35.3 0 64 28.7 64 64V293.5c0 17-6.7 33.3-18.7 45.3l-58.5 58.5c-12 12-28.3 18.7-45.3 18.7H160c-35.3 0-64-28.7-64-64zM272 128c-8.8 0-16 7.2-16 16v48H208c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h48v48c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V256h48c8.8 0 16-7.2 16-16V208c0-8.8-7.2-16-16-16H320V144c0-8.8-7.2-16-16-16H272zm24 336c13.3 0 24 10.7 24 24s-10.7 24-24 24H136C60.9 512 0 451.1 0 376V152c0-13.3 10.7-24 24-24s24 10.7 24 24l0 224c0 48.6 39.4 88 88 88H296z" />
                                </svg>
                                <p style="padding-bottom: 1px;">Upload Own Background Image</p>
                                <span style="font-size:12px; padding-bottom: 1px;">Maximum size 500KB</span>

                            </button>
                            <p id="smsOwnBg" style="text-align: center;"></p>
                            <div id="overlay" name="smsOwnBg" style="display: none;">
                                <div class="cv-spinner">
                                    <span class="spinner"></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="messages"></div>
                </div>
                <div class="btn-area" style="text-align: center;">

                    <!-- <button type="button" id="submit4" class="btn btn-primary btn-block">Update</button> -->

                    <button type="button" id="submit4" class="nectar-button jumbo see-through-2  has-icon time-gem-btn-ok"><span>Complete Your Time Gem</span><i class="icon-button-arrow"></i></button>

                </div>

            </form>



        </div>
    </div>
</div>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/create-time-gem.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript">
    var vids = jQuery("video");
    jQuery.each(vids, function() {
        this.controls = false;
    });
</script>
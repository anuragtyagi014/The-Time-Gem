<?php
# 'post_type' => 'time-gem',
// echo 'Tanvir md. al amin';
add_action( 'add_meta_boxes', 'timegem_form_meta_box' );
function timegem_form_meta_box() {

    add_meta_box(
        'timegem-form',
        __( 'Time gem form data', 'woocommerce' ),
        'timegem_form_meta_box_callback',
        'time-gem'
    );
}


add_action('admin_head', 'timegem_form_meta_box_style');
function timegem_form_meta_box_style(){
    global $post_type;
    if($post_type=='time-gem'){
        echo'<style>#postdivrich{display:none!important;}</style>';
    }
?>
<style>

    .timegem_form_show_wrap {
    }
    .timegem_input_field {
        padding: 2px 10px;
    }
    .timegem_input_full {
        padding: 2px 10px;
    }
    .timegem_input_full p img {
        padding: 5px;
        border: 1px solid #eee;
        margin: 2px;
        border-radius: 2px;
    }
    div#timegem-form p b {
        font-size: 15px;
        font-weight: bold;
    }
    .time-gem-single-attachement {
        display: inline-flex;
        border: 1px solid #ddd;
        width: 100px;
        padding: 5px;
        border-radius: 2px;
        position: relative;
        height: 100px;
        margin: 2px;
    }
    .time-gem-single-attachement img {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 0;
        max-height: 100%;
        bottom: 0;
        right: 0;
        width:100%;
    }
</style>
<?php 
}


function timegem_form_meta_box_callback( $post ) {

    // Add a nonce field so we can check for it later.
    // wp_nonce_field( 'timegem_form_nonce', 'timegem_form_nonce' );

    $dob = get_post_meta( $post->ID, 'date_of_birth', true ); // $post->ID=430 | esc_attr( $dob )
    $pDate = get_post_meta( $post->ID, 'passing_date', true ); // $post->ID=430 | esc_attr( $dob )
    $story = get_post_meta( $post->ID, 'story', true ); // $post->ID=430 | esc_attr( $dob )

    echo '
    <div class="timegem_form_show_wrap">
        <div class="timegem_story">
            <p><b>Life story / about:</b></p>
            <div class="story_box" style="max-height:200px;overflow:auto;">
                '.$story.'
            </div>
        </div>
        <div class="timegem_input_field">
            <p><b>Profile image:</b></p>';
            $profile_image = get_post_meta( $post->ID, 'profile_image', true );
            $profile_image = wp_get_attachment_url($profile_image);
            echo '
            <div class="time-gem-single-attachement"><img class="time-gem-single-attachement" src="'.$profile_image.'" alt="background image"></div>
        </div>
        <div class="timegem_input_field">
            <p><b>Birth Date:</b> '.$dob .'</p>
        </div>
        <div class="timegem_input_field">
            <p><b>Passing Date:</b> '.$pDate .'</p>
        </div>
    </div>

    <div class="timegem_form_show_wrap">
        <div class="timegem_input_field">
            <p><b>Youtube video URL:</b></p>
            <ul>';
            $yUrls = get_post_meta( $post->ID, 'youtube_video_url', true );
            foreach ($yUrls as $key => $url) {
                echo '<li><a href="'.$url.'" target="_blank" class="link-btn" >'.$url.'</a></li>';
            }
            echo '
            </ul>
        </div>
        <div class="timegem_input_field">
            <p><b>Vimio video URL:</b></p>
            <ul>';
            $vUrls = get_post_meta( $post->ID, 'vimio_video_url', true );
            foreach ($vUrls as $key => $url) {
                echo '<li><a href="'.$url.'" target="_blank" class="link-btn" >'.$url.'</a></li>';
            }

            echo '
            </ul>
        </div>

    </div>
    ';
   
    echo '
    <div class="timegem_input_full">
        <p><b>Images:</b></p><div id="attachement_images">'; 
        $uploadImgs = get_post_meta( $post->ID, 'attachments', true );
        foreach ($uploadImgs as $key => $attachmentId) {
           $imgUrl = wp_get_attachment_url($attachmentId);
           $mime_type = get_post_mime_type($attachmentId);
           if(strpos($mime_type, "video") !== false){
                $video_thumbnail = get_stylesheet_directory_uri().'/assets/video.png';
                echo '<div class="time-gem-single-attachement"><a href="'.$imgUrl.'" target="_blank"><img src="'.$video_thumbnail.'" alt="'.$attachmentId.'" ></a></div>'; 
            }else{
                echo '<div class="time-gem-single-attachement"><img src="'.$imgUrl.'" alt="'.$attachmentId.'" ></div>'; 
            }
        }
        echo '</div>
    </div>
    ';

    echo '
    <div class="timegem_input_full">
        <p><b>Q.1 - Early Life: Where did they grow up, and what were some significant moments from their childhood?</b></p>'; // wp_get_attachment_url($attachment_id);
        $q1 = get_post_meta( $post->ID, 'question_1', true );
        echo $q1.'
    </div>';
    echo '
    <div class="timegem_input_full">
        <p><b>Q.2 - Education & Career: What educational achievements did they have, and what career path did they pursue?</b></p>'; // wp_get_attachment_url($attachment_id);
        $q2 = get_post_meta( $post->ID, 'question_2', true );
        echo $q2.'
    </div>';
    echo '
    <div class="timegem_input_full">
        <p><b>Q.3 - Personal Interests: What were some of their hobbies, interests, or passions?</b></p>'; // wp_get_attachment_url($attachment_id);
        $q3 = get_post_meta( $post->ID, 'question_3', true );
        echo $q3.'
    </div>';
    echo '
    <div class="timegem_input_full">
        <p><b>Q.4 - Achievements & Milestones: What were some of their proudest achievements or milestones in life?</b></p>'; // wp_get_attachment_url($attachment_id);
        $q4 = get_post_meta( $post->ID, 'question_4', true );
        echo $q4.'
    </div>';
    echo '
    <div class="timegem_input_full">
        <p><b>Q.5 - Family & Relationships: Who were their immediate family members, and what were some special relationships they cherished?</b></p>'; // wp_get_attachment_url($attachment_id);
        $q5 = get_post_meta( $post->ID, 'question_5', true );
        echo $q5.'
    </div>';
    echo '
    <div class="timegem_input_full">
        <p><b>Q.6 - Philanthropic Activities: Did they engage in any charitable or community activities?</b></p>'; // wp_get_attachment_url($attachment_id);
        $q6 = get_post_meta( $post->ID, 'question_6', true );
        echo $q6.'
    </div>';
    echo '
    <div class="timegem_input_full">
        <p><b>Q.7 - Lessons Learned: What were some valuable life lessons they imparted to others?</b></p>'; // wp_get_attachment_url($attachment_id);
        $q7 = get_post_meta( $post->ID, 'question_7', true );
        echo $q7.'
    </div>';
    echo '
    <div class="timegem_input_full">
        <p><b>Q.8 - Sense of Humor: Share some humorous or light-hearted stories that reflect their sense of humour.</b></p>'; // wp_get_attachment_url($attachment_id);
        $q8 = get_post_meta( $post->ID, 'question_8', true );
        echo $q8.'
    </div>';
    echo '
    <div class="timegem_input_full">
        <p><b>Q.9 - Inspirational Quotes: Did they have any favourite quotes or sayings that resonated with them?
        </b></p>'; // wp_get_attachment_url($attachment_id);
        $q9 = get_post_meta( $post->ID, 'question_9', true );
        echo $q9.'
    </div>';
    echo '
    <div class="timegem_input_full">
        <p><b>Q.10 - Personal Values: What were some core values they lived by?.</b></p>'; // wp_get_attachment_url($attachment_id);
        $q10 = get_post_meta( $post->ID, 'question_10', true );
        echo $q10.'
    </div>';
    echo '
    <div class="timegem_input_full">
        <p><b>Q.11 - Fond Memories: Share a personal memory that highlights their uniqueness and impact on your life.</b></p>'; // wp_get_attachment_url($attachment_id);
        $q11 = get_post_meta( $post->ID, 'question_11', true );
        echo $q11.'
    </div>';
    
    echo '
    <div class="timegem_form_show_wrap">
        <div class="timegem_input_field">
            <p><b>Add links to which charity you like people to donate to + add image of charity.</b></p>'; 
            $charityLink  = get_post_meta( $post->ID, 'charity_link', true );
            $charityImgId = get_post_meta( $post->ID, 'charity_image', true );
            $charityImgUrl= wp_get_attachment_url($charityImgId);// wp_get_attachment_url($attachment_id);

            echo '
            <a href="'.$charityLink.'" target="_blank" rel="noopener noreferrer">
            <div class="time-gem-single-attachement"><img class="time-gem-single-attachement" src="'.$charityImgUrl.'" alt="'.$charityLink.'"></div>
            </a>
        </div>

        <div class="timegem_input_field">
            <p><b>Background image for header section:</b></p>';
            $bgImg = get_post_meta( $post->ID, 'time_gem_bg_image', true );
            // $bgImg = get_post_meta( $post->ID, 'background-image', true );
            echo '
            <div class="time-gem-single-attachement"><img class="time-gem-single-attachement" src="'.$bgImg.'" alt="background image"></div>
        </div>

    </div>
    ';

    echo '
    <div class="timegem_input_full">
        <p><b>Connect social media profiles:</b></p>
        <ul>';
        $socialUrls = get_post_meta( $post->ID, 'social_media_link', true );
        foreach ($socialUrls as $key => $url) {
            echo '<li><a href="'.$url.'" target="_blank" class="link-btn" >'.$url.'</a></li>';
        }
        echo '
        </ul>
    </div>';
    





}



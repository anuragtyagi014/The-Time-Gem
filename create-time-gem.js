$(document).ready(function(){
    $("#btn2").click(function(){
        let x=`
        <div class="inner-area">
            <a class="close-icon"><i class="fas fa-times-circle"></i></a>
            <div class="fancy-select-wrap">
                <select class="btn btn-select-primary" name="social_icon[]">  
                    <option class="facebook fa" value="fa-facebook">Facebook</option>    
                    <option class="twitter fa" value="fa-x-twitter">X</option>          
                    <option  class="instagram fa" value="fa-instagram">Instagram</option>         
                    <option class="linkedin fa" value="fa-linkedin">Linkedin</option>     
                    <option class="pinterest fa"  value="fa-pinterest">Pinterest</option>            
                    <option class="google-plus fa" value="fa-google-plus">Google Plus</option>             
                    <option class="other fa" value="fa-globe">Other</option>
                </select>
            </div>
            <input type="text" name="social_media_link[]" class="form-control form-group">
        </div>
    `;
        $("#pro-service-div").append(x);
        $("#pro-service-div select:last").select2();

    });
    $("body").on('click','.close-icon',function(){
        $(this).closest("div.inner-area").remove();
    });
    $("#btn3").click(function(){
        $("#add-video-url").append("<div class='inner-area'> <a class='close-icon'><i class='fas fa-times-circle'></i></a><input placeholder='eg:- https://www.youtube.com/watch?v=xxxx' type='text' name='youtube_video_url[]' class='form-control form-group'></div>");
    });
    $("#btn4").click(function(){
        $("#add-vimio-url").append("<div class='inner-area'> <a class='close-icon'><i class='fas fa-times-circle'></i></a><input type='text' name='vimio_video_url[]' class='form-control form-group'></div>");
    });
    $("body").on('click','.close-icon',function(){
        $(this).closest("div.inner-area").remove();
    });
});


jQuery( function($){
    $("#post_private_and_password").on("click",function() {
        $("#time_gem_password_area").toggle(this.checked);
      });

    // on upload button click
    $( 'body' ).on( 'click', '.rudr-upload', function( event ){
        event.preventDefault(); // prevent default link click and page refresh
        
        const button = $(this)
        const imageId ='';
        
        const customUploader = wp.media({
            title: 'Insert images', // modal window title
            library : {
                // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
                type : 'image'
            },
            button: {
                text: 'Insert' // button label text
            },
            multiple: true
        }).on( 'select', function() { // it also has "open" and "close" events
            //const attachment = customUploader.state().get( 'selection' ).first().toJSON();
            const attachments = customUploader.state().get( 'selection' );
            attachments.map( function( attachment ) {
                attachment = attachment.toJSON();
                $("#attachement_images").append(`<div class='time-gem-single-attachement'>
                <img src='${attachment.url}'>
                <button type='button' onclick='woox_remove_selected_img(this)'>X</button>
                <input type='hidden' name='attachments[]' value='${attachment.id}'>
                </div>`);

            });
            
        })
        
        // already selected images
        customUploader.on( 'open', function() {

            if( imageId ) {
                const selection = customUploader.state().get( 'selection' )
                attachment = wp.media.attachment( imageId );
                attachment.fetch();
                selection.add( attachment ? [attachment] : [] );
            }
            
        })

        customUploader.open()
    
    });
    
    // charity image upload
    $( 'body' ).on( 'click', '.profile_image', function( event ){
        event.preventDefault(); // prevent default link click and page refresh
        
        const button = $(this)
        const imageId ='';
        
        const customUploader = wp.media({
            title: 'Insert image', // modal window title
            library : {
                // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
                type : 'image'
            },
            button: {
                text: 'Insert' // button label text
            },
            multiple: false
        }).on( 'select', function() { // it also has "open" and "close" events
            const attachment = customUploader.state().get( 'selection' ).first().toJSON();
            $("#profile_image").html(`<div style="display: none; " class='time-gem-single-attachement'>
                <img src='${attachment.url}'>
                <button type='button' onclick='woox_remove_selected_img(this)'>X</button>
                <input type='hidden' name='profile_image' value='${attachment.id}'>
                </div>`);
            
                $("#profileBtn").css('background-image', 'url(' + attachment.url + ')');
        })
        
        // already selected images
        customUploader.on( 'open', function() {

            if( imageId ) {
                const selection = customUploader.state().get( 'selection' )
                attachment = wp.media.attachment( imageId );
                attachment.fetch();
                selection.add( attachment ? [attachment] : [] );
            }
            
        })

        customUploader.open()
    
    });
    $( 'body' ).on( 'click', '.charity_image', function( event ){
        event.preventDefault(); // prevent default link click and page refresh
        
        const button = $(this)
        const imageId ='';
        
        const customUploader = wp.media({
            title: 'Insert image', // modal window title
            library : {
                // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
                type : 'image'
            },
            button: {
                text: 'Insert' // button label text
            },
            multiple: false
        }).on( 'select', function() { // it also has "open" and "close" events
            const attachment = customUploader.state().get( 'selection' ).first().toJSON();
            $("#charity_image").html(`<div style="display: none;" class='time-gem-single-attachement'>
                <img src='${attachment.url}'>
                <button type='button' onclick='woox_remove_selected_img(this)'>X</button>
                <input type='hidden' name='charity_image' value='${attachment.id}'>
                </div>`);// 

                $("#charityBtn").css('background-image', 'url(' + attachment.url + ')');
            
        })
        
        // already selected images
        customUploader.on( 'open', function() {

            if( imageId ) {
                const selection = customUploader.state().get( 'selection' )
                attachment = wp.media.attachment( imageId );
                attachment.fetch();
                selection.add( attachment ? [attachment] : [] );
            }
            
        })

        customUploader.open()
    
    });
    $( 'body' ).on( 'click', '.background_image_upload', function( event ){
        event.preventDefault(); // prevent default link click and page refresh
        
        const button = $(this)
        const imageId ='';
        
        const customUploader = wp.media({
            title: 'Insert image', // modal window title
            library : {
                // uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
                type : 'image'
            },
            button: {
                text: 'Insert' // button label text
            },
            multiple: false
        }).on( 'select', function() { // it also has "open" and "close" events
            const attachment = customUploader.state().get( 'selection' ).first().toJSON();
            $("#own_background_image").html(`<div style="display: none;" class='time-gem-single-attachement'>
                <img src='${attachment.url}'>
                <button type='button' onclick='woox_remove_selected_img(this)'>X</button>
                <input style="display:none" checked type='radio' name='background-image' value='${attachment.url}'>
                </div>`);
            
                $("#bgOwnBtn").css('background-image', 'url(' + attachment.url + ')');
        })
        
        // already selected images
        customUploader.on( 'open', function() {

            if( imageId ) {
                const selection = customUploader.state().get( 'selection' )
                attachment = wp.media.attachment( imageId );
                attachment.fetch();
                selection.add( attachment ? [attachment] : [] );
            }
            
        })

        customUploader.open()
    
    });
    

    // save & submit
    $("body").on("click","#submit1",function(){
        // validation
        let valid = woox_time_gem_form_validation();
        if(valid){
            let my_time_gem = $("form#time-gem-form").serialize()+'&act=1';
            $("#time-gem-form").block({
                message: "Please wait... processing your request!",
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
            $.post("https://thetimegem.com/wp-admin/admin-ajax.php",my_time_gem,function(response){
                //console.log(response);
                if(response.status==true){
                    response.messages.forEach(element => {
                        $("#messages").append(`<p class="success">${element}</p>`);
                    });
                }else{
                    response.messages.forEach(element => {
                        $("#messages").append(`<p class="error">${element}</p>`);
                    });
                }
                $("#time-gem-form").unblock();
            });
        }
    });
    // update time gem
    $("body").on("click","#submit4",function(){
        // validation
        let valid = woox_time_gem_form_validation();
        if(valid){
            let story=tinyMCE.get('story').getContent();
            $("form#update-time-gem-form").find('textarea#story').val(story);
            let my_time_gem = $("form#update-time-gem-form").serialize();
            $("#update-time-gem-form").block({
                message: "Please wait... processing your request!",
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
            $.post("https://thetimegem.com/wp-admin/admin-ajax.php",my_time_gem,function(response){
                //console.log(response);
                if(response.status==true){
                    response.messages.forEach(element => {
                        $("#messages").append(`<p class="success">${element}</p>`);
                    });
                    setTimeout(function(){
                        window.location.href="https://thetimegem.com/my-account/mytimegem/";
                    },500);
                }else{
                    response.messages.forEach(element => {
                        $("#messages").append(`<p class="error">${element}</p>`);
                    });
                }
                $("#update-time-gem-form").unblock();
            });
        }
    });
    // save & draft
    $("body").on("click","#submit2",function(){
        // validation
        let valid = woox_time_gem_form_validation();
        if(valid){
            let my_time_gem = $("form#time-gem-form").serialize()+'&act=2';
            $("#time-gem-form").block({
                message: "Please wait... processing your request!",
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
            $.post("https://thetimegem.com/wp-admin/admin-ajax.php",my_time_gem,function(response){
                if(response.status==true){
                    response.messages.forEach(element => {
                        $("#messages").append(`<p class="success">${element}</p>`);
                    });
                }else{
                    response.messages.forEach(element => {
                        $("#messages").append(`<p class="error">${element}</p>`);
                    });
                }
                $("#time-gem-form").unblock();
            });
        }
    });

    // reset
    /*$("body").on("click","#submit3",function(){
        $("#time-gem-form").block({
            message: "Please wait... processing your request!",
            overlayCSS: {
                background: '#fff',
                opacity: 0.6
            }
        });
        $.post("https://thetimegem.com/wp-admin/admin-ajax.php",{'action':'delete_my_draft_time_gem'},function(response){
            if(response.status==true){
                response.messages.forEach(element => {
                    $("#messages").append(`<p class="success">${element}</p>`);
                });
            }else{
                response.messages.forEach(element => {
                    $("#messages").append(`<p class="error">${element}</p>`);
                });
            }
            $("#time-gem-form").unblock();
            setTimeout(function(){
            location.reload();
            },1000);
        });
        
    });*/

        
        
        
});

// function woox_remove_selected_img(data){
//     $(data).parent().remove();
// }

function woox_time_gem_form_validation(){
    $("#messages").html('');
    let title = $('body').find('input#Title').val().trim();
    if(!title){
        $("#messages").html(`<p class="error">Full name is required.</p>`);
        return false;
    }
    
    //let story = $('body').find('textarea#story').val().trim();
    // if(!story){
    //     $("#messages").html(`<p class="error">Life story / about is required.</p>`);
    //     return false;
    // }
    
    let date_of_birth = $('body').find('input#date_of_birth').val().trim();
    if(!date_of_birth){
        $("#messages").html(`<p class="error">Birth date is required.</p>`);
        return false;
    }

    let passing_date = $('body').find('input#passing_date').val().trim();
    if(!passing_date){
        $("#messages").html(`<p class="error">Passing date is required.</p>`);
        return false;
    }

    return true;
}
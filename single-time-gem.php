<?php

/**
 * The template for displaying single posts.
 *
 * @package Salient WordPress Theme
 * @version 13.1
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

get_header();


$nectar_options    = get_nectar_theme_options();
$fullscreen_header = (!empty($nectar_options['blog_header_type']) && 'fullscreen' === $nectar_options['blog_header_type'] && is_singular('post')) ? true : false;
$blog_header_type  = (!empty($nectar_options['blog_header_type'])) ? $nectar_options['blog_header_type'] : 'default';
$theme_skin        = NectarThemeManager::$skin;
$header_format     = (!empty($nectar_options['header_format'])) ? $nectar_options['header_format'] : 'default';

$hide_sidebar                      = (!empty($nectar_options['blog_hide_sidebar'])) ? $nectar_options['blog_hide_sidebar'] : '0';
$blog_type                         = $nectar_options['blog_type'];
$blog_social_style                 = (get_option('salient_social_button_style')) ? get_option('salient_social_button_style') : 'fixed';
$enable_ss                         = (!empty($nectar_options['blog_enable_ss'])) ? $nectar_options['blog_enable_ss'] : 'false';
$remove_single_post_date           = (!empty($nectar_options['blog_remove_single_date'])) ? $nectar_options['blog_remove_single_date'] : '0';
$remove_single_post_author         = (!empty($nectar_options['blog_remove_single_author'])) ? $nectar_options['blog_remove_single_author'] : '0';
$remove_single_post_comment_number = (!empty($nectar_options['blog_remove_single_comment_number'])) ? $nectar_options['blog_remove_single_comment_number'] : '0';
$remove_single_post_nectar_love    = (!empty($nectar_options['blog_remove_single_nectar_love'])) ? $nectar_options['blog_remove_single_nectar_love'] : '0';
$container_wrap_class              = (true === $fullscreen_header) ? 'container-wrap fullscreen-blog-header' : 'container-wrap';

// Post header.
if (have_posts()) :
	while (have_posts()) :

		the_post();

		if ('image_under' !== $blog_header_type) {
			nectar_page_header($post->ID);
		}


	endwhile;
endif;


// Post header fullscreen style when no image is supplied.
if (true === $fullscreen_header) {
	get_template_part('includes/partials/single-post/post-header-no-img-fullscreen');
} ?>


<div class="<?php echo esc_attr($container_wrap_class);
			if ($blog_type === 'std-blog-fullwidth' || $hide_sidebar === '1') {
				echo ' no-sidebar';
			} ?>" data-midnight="dark" data-remove-post-date="<?php echo esc_attr($remove_single_post_date); ?>" data-remove-post-author="<?php echo esc_attr($remove_single_post_author); ?>" data-remove-post-comment-number="<?php echo esc_attr($remove_single_post_comment_number); ?>">
	<div class="container main-content">

		<?php
		if ('image_under' === $blog_header_type) {
			//get_template_part( 'includes/partials/single-post/post-header-featured-media-under' );
		} else {
			//get_template_part( 'includes/partials/single-post/post-header-no-img-regular' );
		}
		?>

		<div class="row">

			<?php

			nectar_hook_before_content();

			$blog_standard_type = (!empty($nectar_options['blog_standard_type'])) ? $nectar_options['blog_standard_type'] : 'classic';
			$blog_type          = $nectar_options['blog_type'];

			if (null === $blog_type) {
				$blog_type = 'std-blog-sidebar';
			}

			if ('minimal' === $blog_standard_type && 'std-blog-sidebar' === $blog_type || 'std-blog-fullwidth' === $blog_type) {
				$std_minimal_class = 'standard-minimal';
			} else {
				$std_minimal_class = '';
			}

			$single_post_area_col_class = 'span_9';

			// No sidebar.
			if ('std-blog-fullwidth' === $blog_type || '1' === $hide_sidebar) {
				$single_post_area_col_class = 'span_12 col_last';
			}

			?>

			<div class="post-area col <?php echo esc_attr($std_minimal_class) . ' ' . esc_attr($single_post_area_col_class); ?>" role="main">

				<?php
				// Main content loop.
				if (have_posts()) :
					while (have_posts()) :

						the_post();
					//get_template_part( 'includes/partials/single-post/post-content' );
					//echo do_shortcode('[public-post id="'.get_the_ID().'"]');
					endwhile;
				endif;
				if (post_password_required(get_the_ID())) {
					echo get_the_password_form();
					//echo '<p>This Time Gem is password protected: Please enter it!</p>';
				} else {
					echo do_shortcode('[public-post id="' . get_the_ID() . '"]');
				}
				wp_link_pages();

				nectar_hook_after_content();

				// Bottom social location for default minimal post header style.
				if (
					'default_minimal' === $blog_header_type &&
					'fixed' !== $blog_social_style &&
					'post' === get_post_type()
				) {

					get_template_part('includes/partials/single-post/default-minimal-bottom-social');
				}

				if (true === $fullscreen_header && get_post_type() === 'post') {
					// Bottom meta bar when using fullscreen post header.
					//get_template_part( 'includes/partials/single-post/post-meta-bar-ascend-skin' );
				}

				if ('ascend' !== $theme_skin) {

					// Original/Material Theme Skin Author Bio.
					if (
						!empty($nectar_options['author_bio']) &&
						$nectar_options['author_bio'] === '1' &&
						'post' == get_post_type()
					) {
						//get_template_part( 'includes/partials/single-post/author-bio' );

					}
				}

				?>

			</div><!--/post-area-->



		</div><!--/row-->

		<div class="row">


			<div class="comments-section" data-author-bio="<?php if (!empty($nectar_options['author_bio']) && $nectar_options['author_bio'] === '1') {
																echo 'true';
															} else {
																echo 'false';
															} ?>">
				<?php //comments_template(); 
				?>
			</div>

		</div><!--/row-->

	</div><!--/container main-content-->
	<?php nectar_hook_before_container_wrap_close(); ?>
</div><!--/container-wrap-->

<?php //if ( 'fixed' === $blog_social_style ) {
// Social sharing buttons.
if (function_exists('nectar_social_sharing_output')) {
	//nectar_social_sharing_output('fixed');
}
//}

get_footer(); ?>
<script>
	$('.nav-tabs li').on('shown.bs.tab', function(event) {
		var x = $(event.target).text(); // active tab
		var y = $(event.relatedTarget).text(); // previous tab
		//console.log(x);
	});
	$(document).ready(function() {
		$('#info-tab').click(function() {
			//alert('ok 1');
			if ($('#photo-tab').hasClass('active')) {
				//alert('ok 2');
				$("div.row.portfolio-items.masonry-items.constrain-max-cols").isotope({
					itemSelector: '.element'
				}).isotope('layout');
			} else {
				//alert('ok 3');
				let iframes = $('#tab-5').find('iframe');
				iframes.each(function(i, elem) {
					//alert('ok 4');
					var osrc = $(elem).attr('src');
					$(this).attr('src', osrc);
				});
			}
		});

		$('#photo-tab').click(function() {
			//alert('ok 5');
			$("div.row.portfolio-items.masonry-items.constrain-max-cols").isotope({
				itemSelector: '.element'
			}).isotope('layout');
		});

		$('#video-tab').click(function() {
			//alert('ok 6');
			let iframes = $('#tab-5').find('iframe');
			iframes.each(function(i, elem) {
				//alert('ok 7');
				let osrc = $(elem).attr('src');
				$(this).attr('src', osrc);
			});
		});
	});
</script>
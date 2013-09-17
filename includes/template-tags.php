<?php

/*
 * Template selection
 */

// Check plugin options to decide what to do
$sermonoptions = get_option('wpfc_options');
if ( isset($sermonoptions['template']) == '1' ) {
    add_filter('template_include', 'sermon_template_include');
    add_filter('template_include', 'preacher_template_include');
    add_filter('template_include', 'series_template_include');
    add_filter('template_include', 'service_type_template_include');
    add_filter('template_include', 'bible_book_template_include');
}

// Include template for displaying sermons
function sermon_template_include($template)
{
        if (get_query_var('post_type') == 'wpfc_sermon') {
            if ( is_archive() || is_search() ) :
                if(file_exists(get_stylesheet_directory() . '/archive-wpfc_sermon.php'))

                    return get_stylesheet_directory() . '/archive-wpfc_sermon.php';
                return WPFC_SERMONS . '/views/archive-wpfc_sermon.php';
            else :
                if(file_exists(get_stylesheet_directory() . '/single-wpfc_sermon.php'))

                    return get_stylesheet_directory() . '/single-wpfc_sermon.php';
                return WPFC_SERMONS . '/views/single-wpfc_sermon.php';
            endif;
        }

        return $template;
}

// Include template for displaying sermons by Preacher
function preacher_template_include($template)
{
        if (get_query_var('taxonomy') == 'wpfc_preacher') {
            if(file_exists(get_stylesheet_directory() . '/taxonomy-wpfc_preacher.php'))

                return get_stylesheet_directory() . '/taxonomy-wpfc_preacher.php';
            return WPFC_SERMONS . '/views/taxonomy-wpfc_preacher.php';
        }

        return $template;
}

// Include template for displaying sermon series
function series_template_include($template)
{
        if (get_query_var('taxonomy') == 'wpfc_sermon_series') {
            if(file_exists(get_stylesheet_directory() . '/taxonomy-wpfc_sermon_series.php'))

                return get_stylesheet_directory() . '/taxonomy-wpfc_sermon_series.php';
            return WPFC_SERMONS . '/views/taxonomy-wpfc_sermon_series.php';
        }

        return $template;
}

// Include template for displaying service types
function service_type_template_include($template)
{
        if (get_query_var('taxonomy') == 'wpfc_service_type') {
            if(file_exists(get_stylesheet_directory() . '/taxonomy-wpfc_service_type.php'))

                return get_stylesheet_directory() . '/taxonomy-wpfc_service_type.php';
            return WPFC_SERMONS . '/views/taxonomy-wpfc_service_type.php';
        }

        return $template;
}

// Include template for displaying sermons by book
function bible_book_template_include($template)
{
        if (get_query_var('taxonomy') == 'wpfc_bible_book') {
            if(file_exists(get_stylesheet_directory() . '/taxonomy-wpfc_bible_book.php'))

                return get_stylesheet_directory() . '/taxonomy-wpfc_bible_book.php';
            return WPFC_SERMONS . '/views/taxonomy-wpfc_bible_book.php';
        }

        return $template;
}

// render archive entry; depreciated - use render_wpfc_sermon_excerpt() instead
function render_wpfc_sermon_archive()
{
    global $post; ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <h2 class="sermon-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'sermon-manager' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
        <div class="wpfc_sermon_image">
            <?php render_sermon_image('thumbnail'); ?>
        </div>
        <div class="wpfc_sermon_meta cf">
            <p>
                <?php
                    wpfc_sermon_date(get_option('date_format'), '<span class="sermon_date">', '</span> '); echo the_terms( $post->ID, 'wpfc_service_type',  ' <span class="service_type">(', ' ', ')</span>');
            ?></p><p><?php

                    wpfc_sermon_meta('bible_passage', '<span class="bible_passage">'.__( 'Bible Text: ', 'sermon-manager'), '</span> | ');
                    echo the_terms( $post->ID, 'wpfc_preacher',  '<span class="preacher_name">', ' ', '</span>');
                    echo the_terms( $post->ID, 'wpfc_sermon_series', '<p><span class="sermon_series">'.__( 'Series: ', 'sermon-manager'), ' ', '</span></p>' );
                ?>
            </p>
        </div>
    </div>		<?php
}

// render sermon sorting
function render_wpfc_sorting() { ?>
<div id="wpfc_sermon_sorting">
    <span class="sortPreacher">
    <form action="<?php echo home_url(); ?>" method="get">
        <select name="wpfc_preacher" id="wpfc_preacher" onchange="return this.form.submit()">
            <option value=""><?php _e('Sort by Preacher', 'sermon-manager'); ?></option>
            <?php wpfc_get_term_dropdown('wpfc_preacher'); ?>
        </select>
    <noscript><div><input type="submit" value="Submit" /></div></noscript>
    </form>
    </span>
    <span class="sortSeries">
    <form action="<?php echo home_url(); ?>" method="get">
        <select name="wpfc_sermon_series" id="wpfc_sermon_series" onchange="return this.form.submit()">
            <option value=""><?php _e('Sort by Series', 'sermon-manager'); ?></option>
            <?php wpfc_get_term_dropdown('wpfc_sermon_series'); ?>
        </select>
    <noscript><div><input type="submit" value="Submit" /></div></noscript>
    </form>
    </span>
    <span class="sortTopics">
    <form action="<?php echo home_url(); ?>" method="get">
        <select name="wpfc_sermon_topics" id="wpfc_sermon_topics" onchange="return this.form.submit()">
            <option value=""><?php _e('Sort by Topic', 'sermon-manager'); ?></option>
            <?php wpfc_get_term_dropdown('wpfc_sermon_topics'); ?>
        </select>
    <noscript><div><input type="submit" value="Submit" /></div></noscript>
    </form>
    </span>
    <span class="sortBooks">
    <form action="<?php echo home_url(); ?>" method="get">
        <select name="wpfc_bible_book" id="wpfc_bible_book" onchange="return this.form.submit()">
            <option value=""><?php _e('Sort by Book', 'sermon-manager'); ?></option>
            <?php wpfc_get_term_dropdown('wpfc_bible_book'); ?>
        </select>
    <noscript><div><input type="submit" value="Submit" /></div></noscript>
    </form>
    </span>
</div>
<?php
}

// echo any sermon meta
function wpfc_sermon_meta( $args, $before = '', $after = '' )
{
    global $post;
    $data = get_post_meta($post->ID, $args, 'true');
    if ($data != '')
        echo $before .$data. $after;
}

// return any sermon meta
function get_wpfc_sermon_meta( $args )
{
    global $post;
    $data = get_post_meta($post->ID, $args, 'true');
    if ($data != '')
        return $data;
    return null;
}

// render sermon description
function wpfc_sermon_description( $before = '', $after = '' )
{
    global $post;
    $data = get_post_meta($post->ID, 'sermon_description', 'true');
    if ($data != '')
        echo $before .wpautop($data). $after;
}

// render any sermon date
function wpfc_sermon_date( $args, $before = '', $after = '' )
{
    global $post;
    $ugly_date = get_post_meta($post->ID, 'sermon_date', 'true');
    $date = date_i18n($args, $ugly_date);
        echo $before .$date. $after;
}

// Change published date to sermon date on frontend display
// Disabled in 1.7.2 due to problems with some themes
function wpfc_sermon_date_filter()
{
    global $post;
    if ( 'wpfc_sermon' == get_post_type() ) {
        $ugly_date = get_post_meta($post->ID, 'sermon_date', 'true');
        $date = date(get_option('date_format'), $ugly_date);

            return $date;
    }
}
//add_filter('get_the_date', 'wpfc_sermon_date_filter');

// Change the_author to the preacher on frontend display
function wpfc_sermon_author_filter()
{
    global $post;
    $preacher = the_terms( $post->ID, 'wpfc_preacher', '', ', ', ' ' );

        return $preacher;
}
//add_filter('the_author', 'wpfc_sermon_author_filter');

// render sermon image - loops through featured image, series image, speaker image, none
function render_sermon_image($size)
{
    //$size = any defined image size in WordPress
        if( has_post_thumbnail() ) :
            the_post_thumbnail($size);
        elseif ( apply_filters( 'sermon-images-list-the-terms', '', array( 'taxonomy'     => 'wpfc_sermon_series', ) )) :
            // get series image
            print apply_filters( 'sermon-images-list-the-terms', '', array(
                'image_size'   => $size,
                'taxonomy'     => 'wpfc_sermon_series',
                'after'        => '',
                'after_image'  => '',
                'before'       => '',
                'before_image' => ''
            ) );
        elseif ( !has_post_thumbnail() && !apply_filters( 'sermon-images-list-the-terms', '', array( 'taxonomy'     => 'wpfc_sermon_series',	) ) ) :
            // get speaker image
            print apply_filters( 'sermon-images-list-the-terms', '', array(
                'image_size'   => $size,
                'taxonomy'     => 'wpfc_preacher',
                'after'        => '',
                'after_image'  => '',
                'before'       => '',
                'before_image' => ''
            ) );
        endif;
}

// render files section
function wpfc_sermon_files()
{
    if ( get_wpfc_sermon_meta('sermon_video') ) {
        echo '<div class="wpfc_sermon-video cf">';
            echo do_shortcode( get_wpfc_sermon_meta('sermon_video'));
        echo '</div>';
    } elseif ( !get_wpfc_sermon_meta('sermon_video') && get_wpfc_sermon_meta('sermon_audio') ) {
        echo '<div class="wpfc_sermon-audio cf">';?>
            <script>
                jQuery.noConflict();
                jQuery(document).ready(function(){
                    jQuery('audio').mediaelementplayer();
                });
            </script> <?php
            echo '<audio controls="controls">';
                echo '<source src="' . get_wpfc_sermon_meta('sermon_audio') . '"  type="audio/mp3" />';
            echo '</audio>';
        echo '</div>';
    }
}

// render additional files
<<<<<<< HEAD
function wpfc_sermon_attachments()
{
    global $post;
    $args = array(
        'post_type'   => 'attachment',
        'numberposts' => -1,
        'post_status' => null,
        'post_parent' => $post->ID,
        'exclude'     => get_post_thumbnail_id()
    );
    $attachments = get_posts($args);
    if ($attachments) {
        echo '<div id="wpfc-attachments" class="cf">';
        echo '<p><strong>'.__( 'Download Files', 'sermon-manager').'</strong>';
        foreach ($attachments as $attachment) {
            echo '<br/><a target="_blank" href="'.wp_get_attachment_url($attachment->ID).'">';
            echo $attachment->post_title;
        }
        echo '</a>';
        echo '</p>';
        echo '</div>';
	} else {
		echo '<div id="wpfc-attachments" class="cf">';
		echo '<p><strong>'.__( 'Download Files', 'sermon-manager').'</strong>';
			if ( get_wpfc_sermon_meta('sermon_audio') ) {
					echo '<a href="' . get_wpfc_sermon_meta('sermon_audio') . '" class="sermon-attachments">'.__( 'MP3', 'sermon-manager').'</a>';
			}
			if ( get_wpfc_sermon_meta('sermon_notes') ) {
					echo '<a href="' . get_wpfc_sermon_meta('sermon_notes') . '" class="sermon-attachments">'.__( 'Notes', 'sermon-manager').'</a>';
			}
		echo '</p>';
		echo '</div>';
	}

}

// render single sermon entry
function render_wpfc_sermon_single()
{
    global $post; ?>
    <div class="wpfc_sermon_wrap cf">
        <div class="wpfc_sermon_image">
            <?php render_sermon_image('sermon_small'); ?>
        </div>
        <div class="wpfc_sermon_meta cf">
            <p>
                <?php
                    wpfc_sermon_date(get_option('date_format'), '<span class="sermon_date">', '</span> '); echo the_terms( $post->ID, 'wpfc_service_type',  ' <span class="service_type">(', ' ', ')</span>');
            ?></p><p><?php
                    wpfc_sermon_meta('bible_passage', '<span class="bible_passage">'.__( 'Bible Text: ', 'sermon-manager'), '</span> | ');
                    echo the_terms( $post->ID, 'wpfc_preacher',  '<span class="preacher_name">', ', ', '</span>');
                    echo the_terms( $post->ID, 'wpfc_sermon_series', '<p><span class="sermon_series">'.__( 'Series: ', 'sermon-manager'), ' ', '</span></p>' );
                ?>
            </p>
        </div>
    </div>
    <div class="wpfc_sermon cf">

        <?php wpfc_sermon_files(); ?>

        <?php wpfc_sermon_description(); ?>

        <?php wpfc_sermon_attachments(); ?>

        <?php echo the_terms( $post->ID, 'wpfc_sermon_topics', '<p class="sermon_topics">'.__( 'Topics: ', 'sermon-manager'), ',', '', '</p>' ); ?>
    </div>
<?php
}

// render sermon archive
function render_wpfc_sermon_excerpt()
{
    global $post;?>
    <div class="wpfc_sermon_wrap cf">
        <div class="wpfc_sermon_image">
            <?php render_sermon_image('sermon_small'); ?>
        </div>
        <div class="wpfc_sermon_meta cf">
            <p>
                <?php
                    wpfc_sermon_date(get_option('date_format'), '<span class="sermon_date">', '</span> '); echo the_terms( $post->ID, 'wpfc_service_type',  ' <span class="service_type">(', ' ', ')</span>');
            ?></p><p><?php
                    wpfc_sermon_meta('bible_passage', '<span class="bible_passage">'.__( 'Bible Text: ', 'sermon-manager'), '</span> | ');
                    echo the_terms( $post->ID, 'wpfc_preacher',  '<span class="preacher_name">', ', ', '</span>');
                    echo the_terms( $post->ID, 'wpfc_sermon_series', '<p><span class="sermon_series">'.__( 'Series: ', 'sermon-manager'), ' ', '</span></p>' );
                ?>
            </p>
        </div>
        <?php $sermonoptions = get_option('wpfc_options'); if ( isset($sermonoptions['archive_player']) == '1') { ?>
            <div class="wpfc_sermon cf">
                <?php wpfc_sermon_files(); ?>
            </div>
        <?php } if ( isset($sermonoptions['notes_link']) == '1' && get_wpfc_sermon_meta('sermon_notes') ) { ?>
            <div class="wpfc_sermon-notes cf">
                <?php wpfc_sermon_notes(); ?>
            </div>
        <?php } if ( isset($sermonoptions['force_download_link']) == '1' && get_wpfc_sermon_meta('sermon_audio') || get_wpfc_sermon_meta('sermon_video') ) { ?>
            <div class="wpfc_sermon-notes cf">
                <?php wpfc_sermon_download(); ?>
            </div>
        <?php } ?>

    </div>
    <?php

    // debug();
}

// Add sermon content
add_filter('the_content', 'add_wpfc_sermon_content');
function add_wpfc_sermon_content($content)
{
    if ( 'wpfc_sermon' == get_post_type() ) {
        if ( is_archive() ) {
            $new_content = render_wpfc_sermon_excerpt();
        } elseif ( is_singular() && is_main_query() ) {
            $new_content = render_wpfc_sermon_single();
        }
        $content = $new_content;
    }

    return $content;
}

//Podcast Feed URL
function wpfc_podcast_url($feed_type = false)
{
    if ($feed_type == false) { //return URL to feed page

        return home_url() . '/feed/podcast';
    } else { //return URL to itpc itunes-loaded feed page
        $itunes_url = str_replace("http", "itpc", home_url() );

        return $itunes_url . '/feed/podcast';
    }
}

/**
 * Display series info on an individual sermon
 */
function wpfc_footer_series()
{
    global $post;
    $terms = get_the_terms( $post->ID , 'wpfc_sermon_series' );
    if ($terms) {
        foreach ($terms as $term) {
            if ($term->description) {
                echo '<div class="single_sermon_info_box series clearfix">';
                echo '<div class="sermon-footer-description clearfix">';
                echo '<h3 class="single-preacher-name"><a href="' .get_term_link($term->slug, 'wpfc_sermon_series') .'">'.$term->name.'</a></h3>';
                /* Image */
                print apply_filters( 'sermon-images-list-the-terms', '', array(
                    'attr' => array(
                        'class' => 'alignleft',
                        ),
                    'image_size'   => 'thumbnail',
                    'taxonomy'     => 'wpfc_sermon_series',
                    'after'        => '</div>',
                    'after_image'  => '',
                    'before'       => '<div class="sermon-footer-image">',
                    'before_image' => ''
                ) );
                /* Description */
                echo $term->description.'</div>';
                echo '</div>';
            }
        }
    }
}

/**
 * Display preacher info on an individual sermon
 */
function wpfc_footer_preacher()
{
    global $post;
    $terms = get_the_terms( $post->ID , 'wpfc_preacher' );
    if ($terms) {
        foreach ($terms as $term) {
            if ($term->description) {
                echo '<div class="single_sermon_info_box preacher clearfix">';
                echo '<div class="sermon-footer-description clearfix">';
                echo '<h3 class="single-preacher-name"><a href="' .get_term_link($term->slug, 'wpfc_preacher') .'">'.$term->name.'</a></h3>';
                /* Image */
                print apply_filters( 'sermon-images-list-the-terms', '', array(
                    'attr' => array(
                        'class' => 'alignleft',
                        ),
                    'image_size'   => 'thumbnail',
                    'taxonomy'     => 'wpfc_preacher',
                    'after'        => '</div>',
                    'after_image'  => '',
                    'before'       => '<div class="sermon-footer-image">',
                    'before_image' => ''
                ) );
                /* Description */
                echo $term->description.'</div>';
                echo '</div>';
            }
        }
    }
}

/**
 * Display notes link for sermon excerpt
 *
 * @return void
 * @author khornberg
 **/
function wpfc_sermon_notes()
{
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if ( is_plugin_active( "download-shortcode/download-shortcode.php" )) {
            echo do_shortcode( '[download label="'.__( 'Notes', 'sermon-manager').'"]' . get_wpfc_sermon_meta('sermon_notes') . '[/download]' );
    } else {
        echo '<a target="_blank" href="' . get_wpfc_sermon_meta('sermon_notes') . '">'.__( 'Notes', 'sermon-manager').'</a>';
    }
}

/**
 * Display download link for sermon excerpt
 *
 * @return void
 * @author khornberg
 **/
function wpfc_sermon_download()
{
    $audio = (get_wpfc_sermon_meta('sermon_audio')) ? true : false;
    $video = (get_wpfc_sermon_meta('sermon_video')) ? true : false;

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if ( is_plugin_active( "download-shortcode/download-shortcode.php" )) {
        if ($audio && $video) {
            echo do_shortcode( '[download label="'.__( 'Download Audio', 'sermon-manager').'"]' . get_wpfc_sermon_meta('sermon_audio') . '[/download]' );
            echo do_shortcode( '[download label="'.__( 'Download Video', 'sermon-manager').'"]' . get_wpfc_sermon_meta('sermon_video') . '[/download]' );
        }
        elseif ($audio) {
            echo do_shortcode( '[download label="'.__( 'Download', 'sermon-manager').'"]' . get_wpfc_sermon_meta('sermon_audio') . '[/download]' );
        }
        elseif ($video) {
            echo do_shortcode( '[download label="'.__( 'Download', 'sermon-manager').'"]' . get_wpfc_sermon_meta('sermon_video') . '[/download]' );
        }
    } else {
        if ($audio && $video) {
            echo '<a target="_blank" href="' . get_wpfc_sermon_meta('sermon_audio') . '">'.__( 'Download Audio', 'sermon-manager').'</a>';
            echo '<a target="_blank" href="' . get_wpfc_sermon_meta('sermon_video') . '">'.__( 'Download Video', 'sermon-manager').'</a>';
        }
        elseif ($audio) {
            echo '<a target="_blank" href="' . get_wpfc_sermon_meta('sermon_audio') . '">'.__( 'Download Audio', 'sermon-manager').'</a>';
        }
        elseif ($video) {
            echo '<a target="_blank" href="' . get_wpfc_sermon_meta('sermon_video') . '">'.__( 'Download', 'sermon-manager').'</a>';
        }
    }
}


/**
 * debug function
 *
 * Output all custom post data so I can create a plugin to import sermons
 *
 * @return void
 * @author khornberg
 **/
function debug()
{
    $getPostCustom = get_post_custom();
    foreach($getPostCustom as $name=>$value){
        echo "<b>".$name."</b>"." => ";
        foreach ($value as $namedarray => $valuearray) {
            echo "<br />&nbsp;&nbsp;";
            echo $namedarray." => ";
            echo var_dump($valuearray);
        }
        echo "<br /><br />";
    }

    // the_meta();
    // echo get_the_term_list( $post->ID, 'wpfc_preacher');
    // $p = wp_get_object_terms($post->ID, 'wpfc_preacher');
    // print_r($p);
    // print_r(wp_get_object_terms($post->ID, 'wpfc_sermon_series'));
    // print_r(wp_get_object_terms($post->ID, 'wpfc_sermon_topics'));

    print_r(get_the_terms($post->ID, 'wpfc_preacher'));

    print_r(get_object_taxonomies( 'wpfc_sermon' ));
}

<?php
if (! defined('WP_DEBUG')) {
	die( 'Direct access forbidden.' );
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
});


function enqueue_bootstrap_icons() {
    wp_enqueue_style( 'bootstrap-icons', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css' );
}

add_action( 'wp_enqueue_scripts', 'enqueue_bootstrap_icons' );




function truncate_bio($text, $words = 15) {
    $text = wp_strip_all_tags($text); // Remove HTML tags
    $text = explode(' ', $text, $words + 1); // Break the text into words
    if (count($text) > $words) {
        array_pop($text); // Remove the last word if it exceeds the word limit
        $text = implode(' ', $text) . '...'; // Join the words and add "..."
    } else {
        $text = implode(' ', $text);
    }
    return $text;
}


function display_board_members() {
    // Query arguments
    $args = array(
        'post_type' => 'member', // Replace with your custom post type
        'posts_per_page' => -1,
		'orderby' => 'date',
		 'order' => 'ASC'
    );

    $the_query = new WP_Query($args);

    // Output variable
    $output = '<div class="member-grid">';

    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();

            // Get custom fields
            $position = get_field('position');
            $bio = get_the_content();

            // Start generating the member HTML
            $output .= '<div class="member-item">';

			
			$default_image_id = 4559;
			$default_image = wp_get_attachment_image_src($default_image_id, 'full');

			if (has_post_thumbnail()) {
				$output .= '<div class="member-image-box">';
				$output .= '<a href="' . get_permalink() . '">';
				$output .= get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'member-image', 'alt' => get_the_title()]);
				$output .= '</a>';
				$output .= '</div>';
			} else {
				$output .= '<div class="member-image-box">';
				$output .= '<a href="' . get_permalink() . '">';
				$output .= '<img src="' . esc_url($default_image[0]) . '" class="member-image" alt="Default Image" />';
				$output .= '</a>';
				$output .= '</div>';
			}

			
// 			if (has_post_thumbnail()) {
// 				$output .= '<div class="member-image-box">';
// 				$output .= '<a href="' . get_permalink() . '">';
// 				$output .= get_the_post_thumbnail(get_the_ID(), 'full', ['class' => 'member-image', 'alt' => get_the_title()]);
// 				$output .= '</a>';
// 				$output .= '</div>';
// 			}

			
			$output .= '<h5 class="member-name"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h5>';
            $output .= '<p class="member-position">' . esc_html($position) . '</p>';
            $output .= '<div class="member-bio">' . apply_filters('the_content', truncate_bio($bio)) . ' <a href="' . get_permalink() . '" class="read-more-link">Read More</a></div>';
			
			
            $output .= '</div>'; // Close .member-item
        }
        wp_reset_postdata();
    } else {
        $output .= '<p>No members found.</p>';
    }

    $output .= '</div>'; // Close .member-grid

    return $output;
}

// Register the shortcode
add_shortcode('board_members', 'display_board_members');

// Resouces shortcode
// function display_resources_shortcode() {
//     // Query arguments
//     $args = array(
//         'post_type' => 'resource', // Replace with your custom post type
//         'posts_per_page' => -1
//     );

//     $the_query = new WP_Query($args);
//     $output = '';

//     if ($the_query->have_posts()) :
//         $output .= '<div class="resource-list">';
//         while ($the_query->have_posts()) : $the_query->the_post();
//             $resource_type = get_post_meta(get_the_ID(), 'resource_type', true); // Assuming 'resource_type' is a custom field
//             $resource_link = get_field('resource_link'); // Get the ACF field 'resource_link'
//             $tags = get_the_terms(get_the_ID(), 'post_tag'); // Assuming 'post_tag' is used for tags

//             $output .= '<div class="resource-item">';
//             $output .= '<div class="resource-icon">';
//             if (has_post_thumbnail()) {
//                 $output .= get_the_post_thumbnail(get_the_ID(), 'thumbnail');
//             } else {
//                 $output .= '<img src="path/to/default-icon.png" alt="Default Icon">'; // Default icon if no featured image
//             }
//             $output .= '</div>';
//             $output .= '<div class="resource-content">';
//             $output .= '<span class="resource-type">' . esc_html($resource_type) . '</span>';
//             if ($resource_link) {
//                 $output .= '<h3><a href="' . esc_url($resource_link) . '" target="_blank"  class="resource-title">' . get_the_title() . '</a></h3>';
//             } else {
//                 $output .= '<h3 class="resource-title">' . get_the_title() . '</h3>';
//             }
//             $output .= '<p class="resource-description">' . get_the_excerpt() . '</p>';
//             $output .= '<p class="resource-tags">TAGS: ';
//             if ($tags) {
//                 $tag_list = [];
//                 foreach ($tags as $tag) {
//                     $tag_list[] = $tag->name;
//                 }
//                 $output .= implode(', ', $tag_list);
//             }
//             $output .= '</p>';
//             $output .= '</div>';
//             $output .= '</div>';
//         endwhile;
//         $output .= '</div>';
//         wp_reset_postdata();
//     else:
//         $output .= '<p>No resources found.</p>';
//     endif;

//     return $output;
// }

// // Register the shortcode
// add_shortcode('display_resources', 'display_resources_shortcode');


// Try Stuff
function display_resources_with_groups($atts) {
    ob_start();

    // Get the selected group from the URL
    $selected_group = isset($_GET['group']) ? sanitize_text_field($_GET['group']) : '';

    // Start resource grid
    echo '<div class="resource-grid">';

    // Query resources
    $args = array(
        'post_type' => 'resource', // Replace with your custom post type
        'posts_per_page' => -1,
    );

    if ($selected_group && $selected_group != 'all') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'group',
                'field'    => 'slug',
                'terms'    => $selected_group,
            ),
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get the resource groups associated with this resource
            $resource_groups = get_the_terms(get_the_ID(), 'group');

            // Output each resource
            echo '<div class="resource-item">';
            echo '<div class="resource-content">';
			
			// Display resource groups
            if ($resource_groups && !is_wp_error($resource_groups)) {
                echo '<span class="resource-span">';
                $group_names = array();
                foreach ($resource_groups as $group) {
                    $group_names[] = esc_html($group->name);
                }
                echo implode(', ', $group_names);
                echo '</span>';
            }
			
            echo '<h4><a class="resource-title" href="' . esc_url(get_field('resource_link')) . '" target="_blank">' . get_the_title() . '</a></h4>';
            echo '<p>' . get_the_content() . '</p>';

            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No resources found.</p>';
    }

    // Close resource grid
    echo '</div>';

    wp_reset_postdata();

    return ob_get_clean();
}

// Register the shortcode
add_shortcode('resources_page', 'display_resources_with_groups');




function resources_group_sidebar() {
    ob_start();

    // Get all terms in the custom taxonomy 'group'
    $groups = get_terms(array(
        'taxonomy' => 'group',
        'orderby' => 'name',
        'order'   => 'ASC',
        'hide_empty' => false, // Show even if no resources are assigned
    ));

    // Display group filter links
    echo '<ul class="resource-groups">';
    echo '<li><a href="' . esc_url(get_permalink()) . '?group=all">All</a></li>'; // Link to show all resources
    foreach ($groups as $group) {
        echo '<li><a href="' . esc_url(get_permalink()) . '?group=' . esc_attr($group->slug) . '">' . esc_html($group->name) . '</a></li>';
    }
    echo '</ul>';

    return ob_get_clean();
}

// Register the shortcode
add_shortcode('resources_group_sidebar', 'resources_group_sidebar');



function custom_blog_layout_shortcode() {
    ob_start();

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 4,
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<div class="custom-blog-layout">';

        $counter = 0;
        while ($query->have_posts()) {
            $query->the_post();
            $counter++;
			// Divider
                echo '<hr class="post-divider">';

            if ($counter === 1) {
                // First post layout
                echo '<div class="blog-post first-post">';
                echo '<div class="post-meta">';
                echo '<span class="post-date">' . get_the_date() . '</span>';
                echo '</div>';
                echo '<div class="post-content">';
                echo '<h2 class="post-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h2>';
                echo '<p class="post-excerpt">' . wp_trim_words(get_the_excerpt(), 30, '...') . '</p>';
                echo '</div>';
                if (has_post_thumbnail()) {
                    echo '<div class="post-thumbnail"><a href="' . get_the_permalink() . '">';
                    echo get_the_post_thumbnail(get_the_ID(), 'medium');
                    echo '</a></div>';
                }
                echo '</div>'; // Close .blog-post

                // Divider
                echo '<hr class="post-divider">';

                // Start the grid for the next 3 posts
                echo '<div class="post-grid">';
            } else {
                // Next three posts layout
                echo '<div class="blog-post grid-post">';
                if (has_post_thumbnail()) {
                    echo '<div class="post-thumbnail"><a href="' . get_the_permalink() . '">';
                    echo get_the_post_thumbnail(get_the_ID(), 'medium');
                    echo '</a></div>';
                }
                echo '<div class="post-content">';
                echo '<h3 class="post-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
                echo '<p class="post-excerpt">' . wp_trim_words(get_the_excerpt(), 20, '...') . '</p>';
                echo '</div>';
                echo '</div>'; // Close .blog-post
            }
        }

        echo '</div>'; // Close .post-grid
        echo '</div>'; // Close .custom-blog-layout
    } else {
        echo '<p>No posts found.</p>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('custom_blog_layout', 'custom_blog_layout_shortcode');

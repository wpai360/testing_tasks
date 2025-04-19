<?php
// IP address redirection function.
function redirect_from_77_99() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    if (strpos($ip, '77.29') === 0 && !is_admin()) {
        wp_redirect('https://www.google.com');
        exit;
    }
}
add_action('init', 'redirect_from_77_99');

// custom post type and taxonomy

function register_the_creative_projects() {
    register_post_type('projects', [
        'label'               => 'Projects',
        'public'              => true,
        'has_archive'         => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-portfolio',
        'supports'            => ['title', 'editor', 'thumbnail'],
        'show_in_rest'        => true,
        'rewrite'             => ['slug' => 'projects'],
        'labels'              => [
            'name'               => 'Projects',
            'singular_name'      => 'Project',
            'add_new'            => 'Add New',
            'add_new_item'       => 'Add New Project',
            'edit_item'          => 'Edit Project',
            'new_item'           => 'New Project',
            'view_item'          => 'View Project',
            'search_items'       => 'Search Projects',
            'not_found'          => 'No projects found',
            'not_found_in_trash' => 'No projects found in Trash',
            'all_items'          => 'All Projects',
        ],
    ]);

    register_taxonomy('project_type', 'projects', [
        'label'        => 'Project Type',
        'hierarchical' => true,
        'public'       => true,
        'show_ui'      => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'project-type'],
    ]);
}
add_action('init', 'register_the_creative_projects');

// added some demo projects for testing
// ajax endpoint

add_action('wp_ajax_get_projects_for_architecture', 'architecture_hall_of_frames');
add_action('wp_ajax_nopriv_get_projects_for_architecture', 'architecture_hall_of_frames');

function architecture_hall_of_frames() {
    $limit = is_user_logged_in() ? 6 : 3;

    $args = [
        'post_type' => 'projects',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'tax_query' => [
            [
                'taxonomy' => 'project_type',
                'field' => 'name',
                'terms' => 'Architecture',
            ]
        ],
    ];

    $query = new WP_Query($args);
    $data = [];

    foreach ($query->posts as $post) {
        $data[] = [
            'id' => $post->ID,
            'title' => get_the_title($post),
            'link' => get_permalink($post),
        ];
    }

    wp_send_json(['success' => true, 'data' => $data]);
}


function generate_demo_projects_13() {
    if (get_option('demo_projects_created')) {
        return;
    }

    $titles = [
        'DevConnector',          
        'InvoicerPro',           
        'TaskForge',             
        'LaraCRM',               
        'CodeStackr Jobs',       
        'ExpenseTrackerX',       
        'NovaWallet',            
        'EduNode',               
        'LaraSaaS',              
        'QuickShop API',         
        'LaraBlog CMS',          
        'FitnessFusion',         
        'LaraHelpDesk'           
    ];

    foreach ($titles as $title) {
        $post_id = wp_insert_post([
            'post_title'   => $title,
            'post_type'    => 'projects',
            'post_status'  => 'publish',
            'post_content' => 'This is a demo project created for testing: ' . $title,
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            wp_set_object_terms($post_id, 'Architecture', 'project_type', true);
        }
    }

    update_option('demo_projects_created', true);
}
add_action('init', 'generate_demo_projects_13');

// function to create a custom archive page for the projects post type
add_filter('template_include', function ($template) {
    if (is_post_type_archive('projects')) {
        $custom = get_stylesheet_directory() . '/archive-projects.php';
        if (file_exists($custom)) {
            return $custom;
        }
    }
    return $template;
});
// random coffee cup api integration

function hs_give_me_coffee() {
    $res = wp_remote_get('https://coffee.alexflipnote.dev/random.json');

    if (is_wp_error($res)) return false;

    $json = json_decode(wp_remote_retrieve_body($res), true);
    return !empty($json['file']) ? esc_url_raw($json['file']) : false;
}

// Shortcode to display 5 Kanye quotes
function kanye_says_what_shortcode() {
    $quotes = [];

    for ($i = 0; $i < 5; $i++) {
        $res = wp_remote_get('https://api.kanye.rest/');

        if (is_wp_error($res)) {
            $quotes[] = 'Silence from Kanye.';
            continue;
        }

        $data = json_decode(wp_remote_retrieve_body($res), true);
        $quotes[] = !empty($data['quote']) ? esc_html($data['quote']) : 'No quote received.';
    }

    $output = '<div class="kanye-quotes">';
    foreach ($quotes as $quote) {
        $output .= '<p>“' . $quote . '”</p>';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('kanye_quotes', 'kanye_says_what_shortcode');
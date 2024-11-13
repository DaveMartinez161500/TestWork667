<?php
function storefront_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', ['parent-style']);
}
add_action('wp_enqueue_scripts', 'storefront_child_enqueue_styles');




function register_city_post_type() {
    $args = [
        'labels' => [
            'name' => 'Cities',
            'singular_name' => 'City',
            'add_new_item' => 'Add New City',
            'edit_item' => 'Edit City',
            'new_item' => 'New City',
            'view_item' => 'View City',
            'search_items' => 'Search Cities',
            'not_found' => 'No cities found',
            'not_found_in_trash' => 'No cities found in Trash',
        ],
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'menu_icon' => 'dashicons-location-alt',
    ];
    register_post_type('city', $args);
}
add_action('init', 'register_city_post_type');


function add_city_meta_box() {
    add_meta_box(
        'city_coordinates',
        'City Coordinates',
        'city_meta_box_callback',
        'city',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'add_city_meta_box');

function city_meta_box_callback($post) {
    
    wp_nonce_field('save_city_meta_box', 'city_meta_box_nonce');

    $latitude = get_post_meta($post->ID, '_city_latitude', true);
    $longitude = get_post_meta($post->ID, '_city_longitude', true);

    echo '<p><label for="city_latitude">Latitude:</label></p>';
    echo '<input type="text" id="city_latitude" name="city_latitude" value="' . esc_attr($latitude) . '" size="25" />';
    echo '<p><label for="city_longitude">Longitude:</label></p>';
    echo '<input type="text" id="city_longitude" name="city_longitude" value="' . esc_attr($longitude) . '" size="25" />';
}

function save_city_meta_box($post_id) {
    
    if (!isset($_POST['city_meta_box_nonce']) || !wp_verify_nonce($_POST['city_meta_box_nonce'], 'save_city_meta_box')) {
        return;
    }

    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    
    if (isset($_POST['post_type']) && 'city' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

   
    if (isset($_POST['city_latitude'])) {
        $latitude = sanitize_text_field($_POST['city_latitude']);
        update_post_meta($post_id, '_city_latitude', $latitude);
    }

   
    if (isset($_POST['city_longitude'])) {
        $longitude = sanitize_text_field($_POST['city_longitude']);
        update_post_meta($post_id, '_city_longitude', $longitude);
    }
}
add_action('save_post', 'save_city_meta_box');


function register_countries_taxonomy() {
    $args = [
        'labels' => [
            'name' => 'Countries',
            'singular_name' => 'Country',
            'search_items' => 'Search Countries',
            'all_items' => 'All Countries',
            'parent_item' => 'Parent Country',
            'parent_item_colon' => 'Parent Country:',
            'edit_item' => 'Edit Country',
            'update_item' => 'Update Country',
            'add_new_item' => 'Add New Country',
            'new_item_name' => 'New Country Name',
            'menu_name' => 'Countries',
        ],
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'country'],
    ];
    register_taxonomy('country', 'city', $args);
}
add_action('init', 'register_countries_taxonomy');


class City_Temperature_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'city_temperature_widget', 
            'City Temperature',        
            ['description' => 'Displays the city name and temperature from WeatherAPI.']
        );
    }

    
    public function widget($args, $instance) {
        $city_id = $instance['city'];
        $city_name = get_the_title($city_id);
        $latitude = get_post_meta($city_id, '_city_latitude', true);
        $longitude = get_post_meta($city_id, '_city_longitude', true);
    
       
        $api_url = "http://api.weatherapi.com/v1/current.json?key=21cd366ba9fd4680b1e122110241311={$latitude},{$longitude}&aqi=no";
        $response = wp_remote_get($api_url);
        $temp = '';
    
        
        if (is_array($response) && !is_wp_error($response)) {
            $body = json_decode($response['body']);
            
            
            if (isset($body->current) && isset($body->current->temp_c)) {
                $temp = $body->current->temp_c;
            } else {
                $temp = 'Temperature data unavailable'; 
            }
        } else {
            $temp = 'Unable to retrieve temperature data'; 
        }
    
        
        echo $args['before_widget'];
        echo $args['before_title'] . esc_html($city_name) . $args['after_title'];
        echo '<p>Current Temperature: ' . esc_html($temp) . '°C</p>';
        echo $args['after_widget'];
    }
    

    
    public function form($instance) {
        $city_id = !empty($instance['city']) ? $instance['city'] : '';

        
        $cities = get_posts(['post_type' => 'city', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('city'); ?>">Select City:</label>
            <select name="<?php echo $this->get_field_name('city'); ?>" id="<?php echo $this->get_field_id('city'); ?>" class="widefat">
                <option value="">--Select--</option>
                <?php foreach ($cities as $city) : ?>
                    <option value="<?php echo esc_attr($city->ID); ?>" <?php selected($city_id, $city->ID); ?>><?php echo esc_html($city->post_title); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['city'] = (!empty($new_instance['city'])) ? sanitize_text_field($new_instance['city']) : '';
        return $instance;
    }
}
add_action('widgets_init', function() {
    register_widget('City_Temperature_Widget');
});
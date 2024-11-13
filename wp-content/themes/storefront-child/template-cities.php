<?php
/*
Template Name: City Table
*/

global $wpdb;


add_action('before_city_table', function() {
    echo '<div class="before-table-message">';
    echo '<p><strong>Welcome to the City Table! You can search for cities below.</strong></p>';
    echo '</div>';
});

add_action('after_city_table', function() {
    echo '<div class="after-table-message">';
    echo '<p><strong>Thank you for viewing the City Table. Feel free to explore more cities!</strong></p>';
    echo '</div>';
});


$search = isset($_POST['city_search']) ? esc_sql($_POST['city_search']) : '';

$query = "
    SELECT p.ID, p.post_title AS city_name, t.name AS country_name
    FROM {$wpdb->posts} p
    LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
    LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    LEFT JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
    WHERE p.post_type = 'city'
    AND tt.taxonomy = 'country'
    AND p.post_title LIKE '%$search%'
";
$cities = $wpdb->get_results($query);


get_header();
?>

<div id="city-table">
    <h2>City Table</h2>

    
    <form id="city-search-form" method="post">
        <input type="text" name="city_search" placeholder="Search cities..." value="<?php echo isset($_POST['city_search']) ? esc_attr($_POST['city_search']) : ''; ?>" />
        <button type="submit">Search</button>
    </form>

   
    <?php do_action('before_city_table'); ?>

    
    <table>
        <thead>
            <tr>
                <th>Country</th>
                <th>City</th>
                <th>Temperature</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cities as $city) :
               
                $latitude = get_post_meta($city->ID, '_city_latitude', true);
                $longitude = get_post_meta($city->ID, '_city_longitude', true);
                
                
                $api_url = "http://api.weatherapi.com/v1/current.json?key=21cd366ba9fd4680b1e122110241311&q={$latitude},{$longitude}&aqi=no";
                $response = wp_remote_get($api_url);
                $temp = '';

                
                if (is_array($response) && !is_wp_error($response)) {
                    $body = json_decode($response['body']);
                    $temp = isset($body->current->temp_c) ? $body->current->temp_c : 'Data unavailable';
                }
            ?>
                <tr>
                    <td><?php echo esc_html($city->country_name); ?></td>
                    <td><?php echo esc_html($city->city_name); ?></td>
                    <td><?php echo esc_html($temp); ?> °C</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

   
    <?php do_action('after_city_table'); ?>
</div>

<?php

get_footer();
?>

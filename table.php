<?php
/*
Plugin Name: Edamam Nutrition Plugin
Description: A simple plugin to get nutritional information using the Edamam Food Database API.
Version: 1.0
Author: Your Name
*/

// Enqueue jQuery for AJAX
function edamam_enqueue_scripts() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'edamam_enqueue_scripts');

// Create a shortcode for the form
function edamam_nutrition_form_shortcode() {
    ob_start();
    ?>
   <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div style="width: 100vh; text-align: center;">
        <form id="edamam-nutrition-form">
            <label for="food-input">Enter food item:</label>
            <input type="text" id="food-input" name="foodInput" required style="width: 100%;">
            <button type="button" style="width: 100%; margin-top: 1vh;" onclick="getNutritionalInfo()">Get Nutritional Info</button>
        </form>
        <div id="nutrition-results" style="margin-top: 20px;"></div>
    </div>
</div>


    <script>
        function getNutritionalInfo() {
            var foodInput = jQuery('#food-input').val();

            jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                action: 'get_nutritional_info',
                foodInput: foodInput
            }, function(response) {
                jQuery('#nutrition-results').html(response);
            });
        }
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('edamam_nutrition_form', 'edamam_nutrition_form_shortcode');


// AJAX handler to fetch nutritional information using Edamam API
function edamam_get_nutritional_info() {
    $api_url = 'https://api.edamam.com/api/food-database/v2/parser';
    $app_id = '8c157af1';
    $api_key = 'f7df98ab71a2993d1c24aa2e13981313';

    $food_input = sanitize_text_field($_POST['foodInput']);

    $response = wp_safe_remote_get($api_url, array(
        'headers' => array(
            'Accept-Encoding' => 'gzip',
        ),
        'body' => array(
            'app_id' => $app_id,
            'app_key' => $api_key,
            'ingr' => $food_input,
        ),
    ));

    if (is_wp_error($response)) {
        // Log the error to the PHP error log
        error_log('Edamam API request error: ' . $response->get_error_message());
        echo 'Error connecting to the Edamam API.';
    } else {
        $body = wp_remote_retrieve_body($response);
        $result = json_decode($body, true);

        if ($result && isset($result['hints']) && !empty($result['hints'])) {
            // Assuming the first hint contains the most relevant information
            $food = $result['hints'][0]['food'];
            echo 'Calories: ' . $food['nutrients']['ENERC_KCAL'] . '<br>';
            echo 'Protein: ' . $food['nutrients']['PROCNT'] . $food['nutrients']['unit']['PROCNT'] . '<br>';
            echo 'Fat: ' . $food['nutrients']['FAT'] . $food['nutrients']['unit']['FAT'] . '<br>';
            echo 'Carbohydrates: ' . $food['nutrients']['CHOCDF'] . $food['nutrients']['unit']['CHOCDF'] . '<br>';
        } else {
            // Log the error to the PHP error log
            error_log('Edamam API response error: ' . $body);
            echo 'Error getting nutritional information.';
        }
    }

    die();
}
add_action('wp_ajax_get_nutritional_info', 'edamam_get_nutritional_info');
add_action('wp_ajax_nopriv_get_nutritional_info', 'edamam_get_nutritional_info');

?>
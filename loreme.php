<?php

/*
Plugin Name: Loreme Dummy Text Generator
Description: Use this shortcode [loremeX] (e.g., [loreme12]) to generate dummy text with X paragraphs or sentences.
Version: 1.2
Author: Jafar Mirzapoor
*/

// Function to fetch dummy text from Loripsum.net API
function loreme_fetch_text_from_api($number)
{
    $api_url = "http://loripsum.net/api/" . $number;
    $response = wp_remote_get($api_url);
    if (is_wp_error($response)) {
        return "Error: Unable to fetch dummy text from Loripsum.net.";
    }
    return wp_remote_retrieve_body($response);
}

// Shortcode handler function
function loreme_shortcode_handler($atts, $content = null, $tag = '')
{
    preg_match('/loreme(\d+)/', $tag, $matches);

    // Default is 1 paragraph if no number is provided 
    $number = isset($matches[1]) ? (float)$matches[1] : 1;

    // Round the number with PHP_ROUND_HALF_UP to ensure 0.5 rounds up
    $roundedNumber = round($number, 0, PHP_ROUND_HALF_UP);

    // Fetch dummy text from API
    $dummy_text = loreme_fetch_text_from_api($roundedNumber);

    // Return the fetched dummy text
    return $dummy_text;
}

// Register dynamic shortcodes
function loreme_register_shortcodes()
{
    foreach (range(1, 100) as $number) {  // Using 'number' for better clarity
        add_shortcode('loreme' . $number, 'loreme_shortcode_handler');
    }
}
add_action('init', 'loreme_register_shortcodes');

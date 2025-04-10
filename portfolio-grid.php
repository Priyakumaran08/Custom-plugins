<?php

/*
*Plugin Name: Portfolio Grid
*Description: A grid structured Portfolio
*Version:1.0
*Author: Devymuse
*/



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Register Portfolio Custom Post Type
function pg_register_portfolio_post_type() {
    register_post_type('portfolio', [
        'labels' => [
            'name' => 'Portfolio',
            'singular_name' => 'Portfolio Item',
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'menu_icon' => 'dashicons-portfolio',
    ]);
}
add_action('init', 'pg_register_portfolio_post_type');

// Shortcode to display portfolio grid
function pg_portfolio_grid_shortcode($atts) {
    $atts = shortcode_atts([
        'columns' => 3,
        'posts_per_page' => -1,
    ], $atts);

    $query = new WP_Query([
        'post_type' => 'portfolio',
        'posts_per_page' => $atts['posts_per_page']
    ]);

    ob_start();

    if ( $query->have_posts() ) {
        echo '<div class="pg-grid pg-columns-' . esc_attr($atts['columns']) . '">';
        while ( $query->have_posts() ) {
            $query->the_post();
            echo '<div class="pg-item">';
            if ( has_post_thumbnail() ) {
                echo '<div class="pg-thumb"><a href="' . get_permalink() . '">';
                the_post_thumbnail('medium');
                echo '</a></div>';
            }
            echo '<h3 class="pg-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            echo '</div>';
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p>No portfolio items found.</p>';
    }

    return ob_get_clean();
}
add_shortcode('portfolio_grid', 'pg_portfolio_grid_shortcode');

// Enqueue CSS
function pg_enqueue_styles() {
    wp_enqueue_style('pg-style', plugin_dir_url(__FILE__) . 'style.css');
}
add_action('wp_enqueue_scripts', 'pg_enqueue_styles');
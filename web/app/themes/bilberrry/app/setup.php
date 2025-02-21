<?php

/**
 * Theme setup.
 */

namespace App;

use App\GravityForms\GF_Field_Test_Product;
use function Roots\bundle;

use GF_Field;

/**
 * Register the theme assets.
 *
 * @return void
 */
add_action('wp_enqueue_scripts', function () {
    bundle('app')->enqueue();
}, 100);

/**
 * Register the theme assets with the block editor.
 *
 * @return void
 */
add_action('enqueue_block_editor_assets', function () {
    bundle('editor')->enqueue();
}, 100);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
            'name' => __('Primary', 'sage'),
            'id' => 'sidebar-primary',
        ] + $config);

    register_sidebar([
            'name' => __('Footer', 'sage'),
            'id' => 'sidebar-footer',
        ] + $config);
});


add_action('init', function () {
    if (class_exists('App\GravityForms\GF_Field_Test_Product') && !isset(\GF_Fields::$field_types['test_product'])) {
        \GF_Fields::register(new GF_Field_Test_Product());
    }
});


// Create Custom Post Type
add_action('init', function () {
    register_post_type('job', [
        'labels' => [
            'name'          => __('Job', 'sage'),
            'singular_name' => __('Job', 'sage'),
            'add_new'       => __('Add New Job', 'sage'),
            'add_new_item'  => __('Add New Job', 'sage'),
            'edit_item'     => __('Edit Job', 'sage'),
            'new_item'      => __('New Job', 'sage'),
            'view_item'     => __('View Job', 'sage'),
            'all_items'     => __('All Jobs', 'sage'),
        ],
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-businessman',
        'supports'      => ['title', 'editor', 'thumbnail'],
        'show_in_rest'  => true,
        'rewrite'       => ['slug' => 'jobs'],
    ]);
});



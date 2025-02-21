<?php

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

if (! function_exists('\Roots\bootloader')) {
    wp_die(
        __('You need to install Acorn to use this theme.', 'sage'),
        '',
        [
            'link_url' => 'https://roots.io/acorn/docs/installation/',
            'link_text' => __('Acorn Docs: Installation', 'sage'),
        ]
    );
}

\Roots\bootloader()->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/

collect(['setup', 'filters'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });



// Custom SQL query to get jobs with the custom_url ACF field
if (!function_exists('get_sorted_jobs_by_title')) {
    function get_sorted_jobs_by_title(): array
    {
        global $wpdb;

        $query = "
        SELECT p.post_title, pm.meta_value AS custom_url
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'job'
        AND pm.meta_key = 'custom_url'
        AND p.post_status = 'publish'
        ORDER BY p.post_title
    ";

        $results = $wpdb->get_results($query);

        $sorted_jobs = [];

        // Process the results
        foreach ($results as $job) {
            // Get the first letter of the job title
            $first_letter = strtoupper(substr($job->post_title, 0, 1));

            // If the key doesn't exist, init empty array
            if (!isset($sorted_jobs[$first_letter])) {
                $sorted_jobs[$first_letter] = [];
            }

            // Store title as key and custom_url as value
            $sorted_jobs[$first_letter][$job->post_title] = $job->custom_url;
        }

        echo '<pre>';
        var_dump($sorted_jobs);
        echo '</pre>';

        return $sorted_jobs;
    }
}

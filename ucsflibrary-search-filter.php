<?php
/**
 * Plugin Name: UCSF Library Search Filter
 * Plugin URI: https://github.com/ucsf-ckm/ucsflibrary-search-filter
 * Description: Fixes interoperability issues between the Elementor and Relevanssi plugins.
 * Author: Stefan Topfstedt
 * Version: 1.0.0
 * Author URI: https://github.com/stopfstedt
 * License: MIT
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// @link https://www.relevanssi.com/user-manual/filter-hooks/
add_filter('relevanssi_pre_excerpt_content', 'ucsflibrary_search_filter_excerpt_content');

function ucsflibrary_search_filter_excerpt_content($content, $post = null, $query = null) {

    if (false === strpos($content, '<pre><code>')) {
        return $content;
    }

    $filteredChunks = array();

    $chunks = explode('<pre><code>', $content);
    for ($i = 0; $i < count($chunks); $i++) {
        $chunk = $chunks[$i];
        if (false !== strpos($chunk, '</code></pre>')) {
            $subChunks = explode('</code></pre>', $chunk);
            $filteredChunks[] = html_entity_decode($subChunks[0]);
            $filteredChunks[] = $subChunks[1];
        } else {
            $filteredChunks[] = $chunk;
        }
    }

    return implode(' ', $filteredChunks);
}

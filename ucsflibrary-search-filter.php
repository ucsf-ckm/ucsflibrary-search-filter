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

/**
 * Callback function, invoked when the <code>relevanssi_pre_excerpt_content</code> filter-hook fires.
 * 
 * This finds and decodes any markup that Elementor widgets may embed in a given post content,
 * then puts the decoded markup back into place, without its <code>&lt;pre&gt;&ltcode&gt;<code> bookends.
 * Pre-processing the given content in such a way is necessary so that Relevanssi can strip all markup from it
 * when creating search result excerpts.
 *
 * @param string $content The post content to filter
 * @param \WP_Post $post The post object
 * @param string $query The search query
 */
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

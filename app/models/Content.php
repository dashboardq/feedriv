<?php

namespace app\models;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkRenderer;
use League\CommonMark\MarkdownConverter;

class Content {
    public static function get($slug) {
        $content = false;
        $dir = ao()->env('AO_MARKDOWN_DIR') . DIRECTORY_SEPARATOR . 'content';
        $path = $dir . DIRECTORY_SEPARATOR . $slug . '.md';

        if(is_file($path)) {
            $content = Content::md(file_get_contents($path));
        }

        return $content;
    }

    public static function md($input) {
        // Extension defaults are shown below
        // If you're happy with the defaults, feel free to remove them from this array
        $config = [
            'heading_permalink' => [
                'html_class' => 'heading-permalink',
                'id_prefix' => 'section',
                'fragment_prefix' => 'section',
                'insert' => 'before',
                'min_heading_level' => 1,
                'max_heading_level' => 6,
                'title' => 'Permalink',
                //'symbol' => HeadingPermalinkRenderer::DEFAULT_SYMBOL,
                'symbol' => '',
                'aria_hidden' => true,
            ],  
        ];  

        // Configure the Environment with all the CommonMark parsers/renderers
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());

        // Add this extension
        $environment->addExtension(new HeadingPermalinkExtension());

        // Instantiate the converter engine and start converting some Markdown!
        $converter = new MarkdownConverter($environment);
        $output = $converter->convert($input)->getContent();
        return $output;
    }
}


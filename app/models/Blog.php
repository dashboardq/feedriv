<?php

namespace app\models;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkRenderer;
use League\CommonMark\MarkdownConverter;

class Blog {
    public static function all($only_content = false) {
        $items = Blog::list();

        foreach($items as $i => $item) {
            $items[$i]['content'] = Blog::md(file_get_contents($item['file']));

            // Strip out header and footer (this is very rough code for now but it does the job).
            if($only_content) {
                $items[$i]['content'] = preg_replace('/<h1>.*<\/h1>/', '', $items[$i]['content']);
                $items[$i]['content'] = preg_replace('/<hr>.*/sm', '', $items[$i]['content']);
            }
        }

        return $items;
    }

    public static function list() {
        $items = [];
        $dir = ao()->env('AO_MARKDOWN_DIR') . DIRECTORY_SEPARATOR . 'blog';
        // Don't load the file if it starts with an underscore.
        foreach(array_reverse(glob($dir . DIRECTORY_SEPARATOR . '????-??-??_*.md')) as $path) {
            if(is_file($path)) {
                $parts = pathinfo($path);
                $file = $parts['basename'];

                $slug = substr(substr($file, 0, -3), 11);
                $date = substr($file, 0, 10);

                if($date > now()) {
                    continue;
                }


                $temp = [];
                $temp['permalink'] = _uri('/blog/' . $slug);
                $temp['title'] = wordify($slug);
                $temp['date'] = $date;
                $temp['published_at'] = new \DateTime($date);
                $temp['file'] = $path;
                $items[] = $temp;
            }
        }

        return $items;
    }

    public static function get($slug, $draft_key) {
        $item = false;
        $dir = ao()->env('AO_MARKDOWN_DIR') . DIRECTORY_SEPARATOR . 'blog';

        $show_draft = '';

        if(
            $draft_key 
            && $draft_key != 'ENTER_A_SECRET_VALUE_TO_VIEW_DRAFTS' 
            && ao()->env('APP_BLOG_DRAFT_KEY') == $draft_key
        ) {
            $show_draft = '_';
        }

        foreach(glob($dir . DIRECTORY_SEPARATOR . $show_draft . '????-??-??_' . $slug . '.md') as $path) {
            if(is_file($path)) {
                $parts = pathinfo($path);
                $file = $parts['basename'];

                if($show_draft) {
                    $slug = substr(substr($file, 0, -3), 12);
                    $date = substr($file, 1, 10);
                } else {
                    $slug = substr(substr($file, 0, -3), 11);
                    $date = substr($file, 0, 10);
                }

                if($date > now()) {
                    break;
                }

                $item = [];
                $item['permalink'] = _uri('/blog/' . $slug);
                $item['title'] = wordify($slug);
                $item['date'] = $date;
                $item['published_at'] = new \DateTime($date);
                $item['file'] = $path;
                $item['content'] = Blog::md(file_get_contents($item['file']));
                break;
            }
        }


        return $item;
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


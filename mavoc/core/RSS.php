<?php

namespace mavoc\core;

use HTMLPurifier_Config;
use HTMLPurifier;

use DateTime;
use DateTimeZone;

class RSS {
    public $title = '';
    public $link = '';
    public $description = '';
    public $language = '';
    public $url = '';

    public $data = [];
    public $items = [];

    // Right now, only accepting URLs as input but may accept strings or files in the future.
    public function __construct($input) {
        $rest = new REST();
        // Set response type to string
        $response = $rest->get($input, [], ['string']); 

        // Don't output parse warnings
        libxml_use_internal_errors (true);
        $this->data = simplexml_load_string($response);
        if($this->data === false) {
            throw new \Exception('The URL does not appear to be valid XML. Please enter a valid RSS feed.');
        }

        if(!isset($this->data->channel->title)) {
            throw new \Exception('The URL does not appear to be a valid RSS feed. Please enter a valid RSS feed.');
        }

        $this->title = $this->meta('title');
        $this->link = $this->meta('link');
        $this->description = $this->meta('description');
        $this->language = $this->meta('language');

        foreach($this->data->channel->item as $item) {
            $temp = [];
            $temp['title'] = $this->item($item, 'title');
            $temp['link'] = $this->item($item, 'link');
            $temp['guid'] = $this->item($item, 'guid');
            $temp['pub_date'] = $this->item($item, 'pubDate');
            $temp['description'] = $this->item($item, 'description');

            $utc = new DateTimeZone('UTC');
            $temp['published_at'] = new DateTime($temp['pub_date'], $utc);

            $this->items[] = $temp;
        }

        // Later will need to parse the URL and find the real RSS feed (if the passed in URL is not the real feed).
        $this->url = $input;
    }

    public function item($item, $type) {
        $output = '';
        if(isset($item->{$type})) {
            $temp = (string) $item->{$type};
            if($type == 'description') {
                $config = HTMLPurifier_Config::createDefault();
                $purifier = new HTMLPurifier($config);
                $temp = $purifier->purify($temp);
            } else {
                $temp = strip_tags($temp);
            }

            $output = $temp;
        }

        return $output;
    }

    public function meta($type) {
        $output = '';
        if(isset($this->data->channel->{$type})) {
            $temp = (string) $this->data->channel->{$type};
            $temp = strip_tags($temp);

            $output = $temp;
        }

        return $output;
    }
}


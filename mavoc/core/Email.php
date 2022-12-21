<?php

namespace mavoc\core;

// This is not chainable for future development reasons and to promote one way of doing things.
class Email {
    public $req;
    public $res;
    public $session;

    public $_to;
    public $_from;
    public $_reply_to;
    public $_subject;
    public $_message;
    public $_html;

    public function __construct() {
        $this->to(ao()->env('EMAIL_ADMIN'));
        $this->from(ao()->env('EMAIL_FROM'));
    }   

    public function init() {
    }

    public function to($email) {
        $this->_to = $email;
    }

    public function from($email) {
        $this->_from = $email;
    }

    public function html($html) {
        $this->_html = $html;
    }

    public function replyTo($email) {
        $this->_reply_to = $email;
    }

    public function send() {
        if(ao()->env('EMAIL_SEND') === true) {
            $headers = '';
            if($this->_from) {
                $headers .= 'From: ' . $this->_from . "\r\n";
            }
            if($this->_reply_to) {
                $headers .= 'Reply-To: ' . $this->_reply_to . "\r\n";
            }

            // ENV takes priority and cannot be changed.
            $override_env_to = ao()->env('EMAIL_OVERRIDE_TO');
            $override_hook_to = ao()->hook('ao_email_override_to', '');
            if($override_env_to) {
                $this->_to = $override_env_to;
            } elseif($override_hook_to) {
                $this->_to = $override_hook_to;
            }
            return mail($this->_to, $this->_subject, $this->_message, $headers);
        } elseif(ao()->env('EMAIL_SEND') === false) {
            ao()->error('Email failed to send. Please contact support.');
        } else {
            // If environment set to anything other than true or false (like null or a string), pretend it sent.
            return true;
        }
    }

    public function subject($subject) {
        $this->_subject = $subject;
    }

    public function message($message) {
        $this->_message = $message;
    }

}

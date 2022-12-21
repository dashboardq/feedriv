<?php

namespace app\controllers;

use mavoc\core\Email;

class ContactController {
    public function contact($req, $res) {
        return ['title' => 'Contact'];
        //$res->view('contact/contact');
    }

    public function contactPost($req, $res) {
        $val = $req->val($req->data, [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'message' => ['required'],
        ]);

        $email = new Email();
        $email->replyTo($val['email']);
        $email->subject('Contact Form: ' . $val['email']);
        $email->message($val['message']);
        $email->send();

        $res->success('Thank you for contacting us.');
    }
}

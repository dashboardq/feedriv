<?php

namespace app\models;

use mavoc\core\Email;
use mavoc\core\Model;

class User extends Model {
    public static $table = 'users';

    public function changePassword($old_password, $new_password) {
        if(ao()->env('APP_LOGIN_TYPE') == 'db') {
            if(password_verify($old_password, $this->data['password'])) {
                $this->data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
                $this->save();
                return true;
            }
        }

        throw new \Exception('There was a problem updating the password. Please confirm the information entered is correct.');
    }

    public static function create($args) {
        $login_type = ao()->env('APP_LOGIN_TYPE');
        if($login_type == 'db') {
            // Bcrypt the password
            $args['password'] = password_hash($args['password'], PASSWORD_DEFAULT);
        } else {
            $args['password'] = '';
        }

        $item = new User($args);
        $item->save();
        return $item;
    }

    public static function forgotPassword($email) {
        if(ao()->env('APP_LOGIN_TYPE') == 'db') {
            $length = 32;
            $token = sodium_bin2hex(random_bytes($length));
            $reset_url = '';

            $user = User::by('email', $email);
            if($user) {
                // Mark old resets as used.
                PasswordReset::updateWhere(['used' => 1], ['user_id' => $user->id]);

                // Set expiration of new token.
                $dt = new \DateTime();
                $expires_at = $dt->modify('+4 hours');

                $hash = password_hash($token, PASSWORD_DEFAULT);

                $args = [];
                $args['user_id'] = $user->id;
                $args['token'] = $hash;
                $args['created_ip'] = ao()->request->ip;
                $args['expires_at'] = $expires_at->format('Y-m-d H:i:s');
                PasswordReset::create($args);

                // Build email
                $reset_url = '';
                $reset_url .= ao()->env('APP_SITE') . '/reset-password';
                $reset_url .= '?id=' . $user->id;
                $reset_url .= '&reset=' . $token;

                $app_name = ao()->env('APP_NAME');
                $subject = $app_name . ': Reset Password';

                $message = '';
                $message .= "Hi,\n\nA password reset request has been made for your $app_name account. Please follow the link below to reset your password:";
                $message .= "\n";
                $message .= $reset_url;

                //echo $message;die;

                $mail = new Email();
                $mail->replyTo($email);
                $mail->subject($subject);
                $mail->message($message);
                $mail->send();
            }

        }
    }

    // Return a user based on the list in .env.php
    public static function local($user_id) {
        $item = null;
        if(ao()->env('APP_LOGIN_TYPE') == 'list') {
            $users = ao()->env('APP_LOGIN_USERS');
            if(isset($users[$user_id])) {
                $args = [];
                $args['id'] = $user_id;
                $args['email'] = $users[$user_id]['email'];
                $item = new User($args);
            }
        }
        return $item;
    }

    public static function login($email, $password) {
        if(ao()->env('APP_LOGIN_TYPE') == 'list') {
            $users = ao()->env('APP_LOGIN_USERS');
            foreach($users as $id => $user) {
                if(
                    isset($user['email'])
                    && isset($user['password']) 
                    && $user['email'] == $email
                    && $user['password'] == $password
                ) {
                    $user = User::local($id);

                    $user->session();
                    return true;
                }
            }
        } elseif(ao()->env('APP_LOGIN_TYPE') == 'db' && ao()->env('DB_USE')) {
            $user = User::by('email', $email);

            if($user) {
                if(password_verify($password, $user->data['password'])) {

                    // TODO: Need to make this more robust.
                    unset($user->data['password']);

                    $user->session();
                    return true;
                }
            }
        }

        return false;
    }

    public function logout() {
        ao()->session->logout();
    }

    public static function resetPassword($user_id, $token, $new_password) {
        if(ao()->env('APP_LOGIN_TYPE') == 'db') {
            $user = User::find($user_id);
            $reset = PasswordReset::by([
                'user_id' => $user_id, 
                'used' => 0, 
                'expires_at' => ['>', 'NOW()'],
            ]);
            if($user && $reset) {
                // Verify the $token hash matches.
                if(password_verify($token, $reset->data['token'])) {
                    // Mark token as used.
                    $reset->data['used_ip'] = ao()->request->ip;
                    $reset->data['used'] = 1;
                    $reset->save();

                    $user->data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
                    $user->save();
                    return true;
                }
            }

            throw new \Exception('There was a problem resetting your password. The token may have expired or is not valid. If the problem continues, please contact support.');
        }

        throw new \Exception('There was a problem updating the password. Please confirm the information entered is correct.');
    }

    public function session() {
        ao()->session->user = $this;
        ao()->session->user_id = $this->data['id'];
    }
}

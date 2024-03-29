<?php

namespace mavoc\core;

class HTML {
    public $req;
    public $res;
    public $session;

    public function __construct() {
    }   

    public function init() {
    }

    public function _a($href, $value = '', $class = '') {
        if($value == '') {
            $value = 'Link';
        }
        $output = '';
        $output .= '<a href="' . _uri($href) . '" ';
        $output .= 'class="' . $class . '" ';
        $output .= '>';
        $output .= _esc($value);
        $output .= '</a>';
        $output .= "\n";

        return $output;
    }
    public function a($action, $value = '', $class = '') {
        $output = $this->_a($action, $value, $class);
        echo $output;
    }

    public function _checkbox($label, $name = '', $value = '', $checked = null, $class = '', $extra = '') {
        if(!$name) {
            $name = underscorify($label);
        }
        if($value === '') {
            $value = 1;
        }

        $checked = '';
        if($checked === true || $checked === 1 || $checked === '1') {
            $checked = 'checked ';
        } elseif($checked === false || $checked === 0 || $checked === '0') {
            $checked = ' ';
        } else {
            if(
                isset($this->session->flash['fields'][$name]) 
                && $value == $this->session->flash['fields'][$name]
            ) {
                $checked = 'checked ';
            } elseif(
                isset($this->res->fields[$name])
                && $value == $this->res->fields[$name]
            ) {
                $checked = 'checked ';
            }
        }

        $output = '';
        $output .= '<label>';
        $output .= '<input type="checkbox" name="' . _esc($name) . '" value="' . _esc($value) . '" ';
        $output .= 'class="' . $class . '" ';
        $output .= $checked;
        // Be careful with $extra values - they are not escaped.
        // Do not use untrusted data.
        if($extra) {
            $output .= $extra;
        }
        $output .= ' /> ';
        $output .= _esc($label);
        $output .= '</label>';
        $output .= "\n";

        return $output;
    }
    public function checkbox($label, $name = '', $value = '', $check = null, $class = '', $extra = '') {
        $output = $this->_checkbox($label, $name, $value, $check, $class, $extra);
        echo $output;
    }
    public function _checkboxRaw($name, $value = '', $check = null, $class = '', $extra = '') {
        if($value === '') {
            $value = 1;
        }

        $checked = '';
        if($check === true || $check === 1 || $check === '1') {
            $checked = 'checked ';
        } elseif($check === false || $check === 0 || $check === '0') {
            $checked = ' ';
        } else {
            if(
                isset($this->session->flash['fields'][$name]) 
                && $value == $this->session->flash['fields'][$name]
            ) {
                $checked = 'checked ';
            } elseif(
                isset($this->res->fields[$name])
                && $value == $this->res->fields[$name]
            ) {
                $checked = 'checked ';
            }
        }

        $output = '';
        $output .= '<input type="checkbox" name="' . _esc($name) . '" value="' . _esc($value) . '" ';
        $output .= 'class="' . $class . '" ';
        $output .= $checked;
        // Be careful with $extra values - they are not escaped.
        // Do not use untrusted data.
        if($extra) {
            $output .= $extra;
        }
        $output .= ' /> ';
        $output .= "\n";

        return $output;
    }
    public function checkboxRaw($name, $value = '', $check = null, $class = '', $extra = '') {
        $output = $this->_checkboxRaw($name, $value, $check, $class, $extra);
        echo $output;
    }

    public function _checkboxes($label, $name = '', $data = []) {
        $error = false;
        if(isset($this->session->flash['error'][$name])) {
            $error = true;
        }

        $output = '';
        if($error) {
            $output .= '<div class="field -error">';
        } else {
            $output .= '<div class="field">';
        }
        $output .= "\n";
        $output .= '<label>' . _esc($label) . '</label>';
        $output .= "\n";

        foreach($data as $item) {
            $output .= $this->_checkbox($item['label'], $name, $item['value'] ?? '', $item['class'] ?? '', $item['extra'] ?? '');
        }

        $output .= '</div>';
        $output .= "\n";


        return $output;
    }
    public function checkboxes($label, $name = '', $data = []) {
        $output = $this->_checkboxes($label, $name, $data);
        echo $output;
    }

    public function _color($label, $name = '', $value = '', $class = '', $extra = '') {
        if(!$name) {
            $name = underscorify($label);
        }

        if(isset($this->session->flash['fields'][$name])) {
            $value = $this->session->flash['fields'][$name];
        } elseif(isset($this->res->fields[$name])) {
            $value = $this->res->fields[$name];
        }

        $error = false;
        if(isset($this->session->flash['error'][$name])) {
            $error = true;
        }

        $output = '';
        if($error) {
            $output .= '<div class="field -error">';
        } else {
            $output .= '<div class="field">';
        }
        $output .= "\n";
        $output .= '<label>' . _esc($label) . '</label>';
        $output .= "\n";
        $output .= '<input type="color" name="' . _esc($name) . '" value="' . _esc($value) . '" placeholder="' . _esc($label) . '" ';
        $output .= 'class="' . $class . '" ';
        // Be careful with $extra values - they are not escaped.
        // Do not use untrusted data.
        if($extra) {
            $output .= $extra;
        }
        $output .= ' /> ';
        $output .= "\n";
        $output .= '</div>';
        $output .= "\n";

        return $output;
    }
    public function color($label, $name = '', $value = '', $class = '', $extra = '') {
        $output = $this->_color($label, $name, $value, $class, $extra);
        echo $output;
    }

    public function _delete($action, $value = '', $class = '', $warning = '') {
        if($value == '') {
            $value = 'Delete';
        }
        if($warning == '') {
            $warning = 'Are you sure you want to delete this item?';
        }
        $output = '';
        $output .= '<form action="' . _uri($action) . '" method="POST">';
        $output .= "\n";
        $output .= $this->_submit($value, $class, 'onclick="return confirm(\'' . $warning . '\');"');
        $output .= "\n";
        $output .= '</form>';
        $output .= "\n";

        return $output;
    }
    public function delete($action, $value = '', $class = '', $warning = '') {
        $output = $this->_delete($action, $value, $class, $warning);
        echo $output;
    }

    public function _hidden($name, $value) {
        $output = '';
        $output .= '<input type="hidden" name="' . _esc($name) . '" value="' . _esc($value) . '" ';
        $output .= '/> ';
        $output .= "\n";

        return $output;
    }
    public function hidden($name, $value) {
        $output = $this->_hidden($name, $value);
        echo $output;
    }

    public function _messages() {
        $output = '';
        if(isset($this->session->flash['error'])) {
            $output .= '<div class="notice error">';
            $output .= "\n";
            foreach($this->session->flash['error'] as $field => $messages) {
                foreach($messages as $message) {
                    $output .= '<p>' . _esc($message) . '</p>';
                    $output .= "\n";
                }
            }
            $output .= '</div>';
            $output .= "\n";
        }
        if(isset($this->session->flash['success'])) {
            $output .= '<div class="notice success">';
            $output .= "\n";
            foreach($this->session->flash['success'] as $field => $messages) {
                foreach($messages as $message) {
                    $output .= '<p>' . _esc($message) . '</p>';
                    $output .= "\n";
                }
            }
            $output .= '</div>';
            $output .= "\n";
        }

        return $output;
    }

    public function messages() {
        $output = $this->_messages();
        echo $output;
    }

    public function _option($label, $name = null, $value = null, $current_value = null, $class = null, $extra = null) {
        if($value === null) {
            $value = $label;
        }

        $selected = '';
        if(
            isset($this->session->flash['fields'][$name]) 
            && $value == $this->session->flash['fields'][$name]
        ) {
            $selected = ' selected';
        } elseif(
            isset($this->res->fields[$name])
            && $value == $this->res->fields[$name]
        ) {
            $selected = ' selected';
        } elseif(
            !isset($this->session->flash['fields'][$name]) 
            && !isset($this->res->fields[$name])
            && $value == $current_value
        ) {
            $selected = ' selected';
        }

        $output = '';
        $output .= '<option value="' . _esc($value) . '"';
        if($class) {
            $output .= ' class="' . $class . '" ';  
        }
        $output .= $selected;
        // Be careful with $extra values - they are not escaped.
        // Do not use untrusted data.
        if($extra) {
            $output .= ' ' . $extra;
        }
        $output .= ' />';
        $output .= _esc($label);
        $output .= '</option>';
        $output .= "\n";

        return $output;
    }
    public function option($label, $name = null, $value = null, $current_value = null, $class = null, $extra = null) {
        $output = $this->_option($label, $name, $value, $current_value, $class, $extra);
        echo $output;
    }

    public function _password($label, $name = '') {
        if(!$name) {
            $name = underscorify($label);
        }

        $value = '';
        if(isset($this->session->flash['fields'][$name])) {
            $value = $this->session->flash['fields'][$name];
        }

        $error = false;
        if(isset($this->session->flash['error'][$name])) {
            $error = true;
        }

        $output = '';
        if($error) {
            $output .= '<div class="field -error">';
        } else {
            $output .= '<div class="field">';
        }
        $output .= "\n";
        $output .= '<label>' . _esc($label) . '</label>';
        $output .= "\n";
        $output .= '<input type="password" name="' . _esc($name) . '" value="' . _esc($value) . '" placeholder="' . _esc($label) . '" />';
        $output .= "\n";
        $output .= '</div>';
        $output .= "\n";

        return $output;
    }

    public function password($label, $name = '') {
        $output = $this->_password($label, $name);
        echo $output;
    }

    public function _radio($label, $name = '', $value = '', $class = '', $extra = '') {
        if($value === '') {
            $value = underscorify($label);
        }

        $checked = '';
        if(
            isset($this->session->flash['fields'][$name]) 
            && $value == $this->session->flash['fields'][$name]
        ) {
            $checked = 'checked ';
        }

        $output = '';
        $output .= '<label>';
        $output .= '<input type="radio" name="' . _esc($name) . '" value="' . _esc($value) . '" ';
        $output .= 'class="' . $class . '" ';
        $output .= $checked;
        // Be careful with $extra values - they are not escaped.
        // Do not use untrusted data.
        if($extra) {
            $output .= $extra;
        }
        $output .= ' /> ';
        $output .= _esc($label);
        $output .= '</label>';
        $output .= "\n";

        return $output;
    }
    public function radio($label, $name = '', $value = '', $class = '', $extra = '') {
        $output = $this->_radio($label, $name, $value, $class, $extra);
        echo $output;
    }

    public function _radios($label, $name = '', $data = []) {
        $error = false;
        if(isset($this->session->flash['error'][$name])) {
            $error = true;
        }

        $output = '';
        if($error) {
            $output .= '<div class="field -error">';
        } else {
            $output .= '<div class="field">';
        }
        $output .= "\n";
        $output .= '<label>' . _esc($label) . '</label>';
        $output .= "\n";

        foreach($data as $item) {
            $output .= $this->_radio($item['label'], $name, $item['value'] ?? '', $item['class'] ?? '', $item['extra'] ?? '');
        }

        $output .= '</div>';
        $output .= "\n";


        return $output;
    }
    public function radios($label, $name = '', $data = []) {
        $output = $this->_radios($label, $name, $data);
        echo $output;
    }

    public function _select($label, $name = null, $data = [], $current_value = null, $class = null, $extra = null) {
        if(is_array($name)) {
            $data = $name;
            $name = null;
        }
        if(!$name) {
            $name = underscorify($label);
        }

        if(isset($this->session->flash['fields'][$name])) {
            $current_value = $this->session->flash['fields'][$name];
        } elseif(isset($this->res->fields[$name])) {
            $current_value = $this->res->fields[$name];
        }

        $error = false;
        if(isset($this->session->flash['error'][$name])) {
            $error = true;
        }

        $output = '';
        // Don't show label, errors, or div if there is no label
        if($label) {
            if($error) {
                $output .= '<div class="field -error">';
            } else {
                $output .= '<div class="field">';
            }
            $output .= "\n";

            $output .= '<label>' . _esc($label) . '</label>';
            $output .= "\n";
        }

        $output .= $this->_selectRaw($name, $data, $current_value, $class, $extra);

        if($label) {
            $output .= '</div>';
            $output .= "\n";
        }


        return $output;
    }
    public function select($label, $name = null, $data = [], $value = null, $class = null, $extra = null) {
        $output = $this->_select($label, $name, $data, $value, $class, $extra);
        echo $output;
    }

    public function _selectRaw($name = '', $data = [], $current_value = null, $class = null, $extra = null) {
        $error = false;
        if(isset($this->session->flash['error'][$name])) {
            $error = true;
        }

        $output = '';
        $output .= '<select name="' . _esc($name) . '"';
        if($class) {
            $output .= ' class="' . $class . '" ';
        }
        // Be careful with $extra values - they are not escaped.
        // Do not use untrusted data.
        if($extra) {
            $output .= ' ' . $extra;
        }
        $output .= '>';
        $output .= "\n";

        foreach($data as $item) {
            if(isset($item['label'])) {
                $output .= $this->_option($item['label'], $name, $item['value'] ?? '', $current_value, null, null);
            } else {
                $output .= $this->_option($item, $name, $item, $current_value, null, null);
            }
        }

        $output .= '</select>';
        $output .= "\n";


        return $output;
    }
    public function selectRaw($name = '', $data = [], $current_value = null, $class = null, $extra = null) {
        $output = $this->_selectRaw($name, $data, $current_value, $class, $extra);
        echo $output;
    }

    public function _submit($value, $class = '', $extra = '') {
        $output = '';
        $output .= '<div class="field">';
        $output .= "\n";
        $output .= '<input type="submit" ';
        if($class) {
            $output .= ' class="' . $class . '"';
        }
        $output .= ' value="' . _esc($value) . '"';
        // Be careful with $extra values - they are not escaped.
        // Do not use untrusted data.
        if($extra) {
            $output .= ' ' . $extra;
        }
        $output .= ' />';
        $output .= "\n";
        $output .= '</div>';
        $output .= "\n";

        return $output;
    }

    public function submit($value, $class = '', $extra = '') {
        $output = $this->_submit($value, $class, $extra);
        echo $output;
    }

    public function _text($label, $name = '', $value = '', $class = '', $extra = '') {
        if(!$name) {
            $name = underscorify($label);
        }

        if(isset($this->session->flash['fields'][$name])) {
            $value = $this->session->flash['fields'][$name];
        } elseif(isset($this->res->fields[$name])) {
            $value = $this->res->fields[$name];
        }

        $error = false;
        if(isset($this->session->flash['error'][$name])) {
            $error = true;
        }

        $output = '';
        if($error) {
            $output .= '<div class="field -error">';
        } else {
            $output .= '<div class="field">';
        }
        $output .= "\n";
        $output .= '<label>' . _esc($label) . '</label>';
        $output .= "\n";
        $output .= '<input type="text" name="' . _esc($name) . '" value="' . _esc($value) . '" placeholder="' . _esc($label) . '" ';
        $output .= 'class="' . $class . '" ';
        // Be careful with $extra values - they are not escaped.
        // Do not use untrusted data.
        if($extra) {
            $output .= $extra;
        }
        $output .= ' /> ';
        $output .= "\n";
        $output .= '</div>';
        $output .= "\n";

        return $output;
    }
    public function text($label, $name = '', $value = '', $class = '', $extra = '') {
        $output = $this->_text($label, $name, $value, $class, $extra);
        echo $output;
    }

    public function _textarea($label, $name = '', $value = '') {
        if(!$name) {
            $name = underscorify($label);
        }

        if(isset($this->session->flash['fields'][$name])) {
            $value = $this->session->flash['fields'][$name];
        }

        $error = false;
        if(isset($this->session->flash['error'][$name])) {
            $error = true;
        }

        $output = '';
        if($error) {
            $output .= '<div class="field -error">';
        } else {
            $output .= '<div class="field">';
        }
        $output .= "\n";
        $output .= '<label>' . _esc($label) . '</label>';
        $output .= "\n";
        $output .= '<textarea type="text" name="' . _esc($name) . '" placeholder="' . _esc($label) . '">';
        $output .= _esc($value);
        $output .= '</textarea>';
        $output .= "\n";
        $output .= '</div>';
        $output .= "\n";

        return $output;
    }

    public function textarea($label, $name = '', $value = '') {
        $output = $this->_textarea($label, $name, $value);
        echo $output;
    }

}

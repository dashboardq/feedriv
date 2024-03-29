// These are experimental features that may be dangerous to use.
window._ao = {};

(function() {
    // Not set up yet but use to chain calls together on elements.
    _ao.action = function(el) {
        var i;
        var output = '';

        if(el instanceof Event) {
            el = el.target;
        }

        el.cla
        for(i = 0; i < el.classList.length; i++) {
            if(el.classList[i].startsWith('_')) {
                output = el.classList[i].slice(1);
                break;
            }
        }

        console.log('output: ' + output);
        return output;
    };

    _ao.click = function(selector, parent) {
        var $item;
        var list = [];
        var output = [];

        if(selector instanceof Element) {
            list.push(selector);
        } else if(selector instanceof Array) {
            list = selector;
        } else if($parent instanceof Element) {
            list = Array.from($parent.querySelectorAll(selector));
        } else {
            list = Array.from(ao.qsa(selector));
        }

        for($item of list) {
            $item.click();
            output.push($item);
        }

        return output;
    };

    _ao._closest = function(selector) {
        return function(e) {
            return _ao.closest(e, selector, e.detail.response);
        }
    };
    // Unsafe with untrusted data.
    _ao.closest = function($item, selector, content) {
        var e;
        var $el;
        if($item instanceof Event) {
            e = $item;
            $item = e.target;
        }

        $el = $item.closest(selector);

        $el.innerHTML = content;
    };

    // Modifiers are ao method names like "visible" to see if an item is visible.
    /*
    _ao.click = function(selector, parent) {
        var i;
        var items = [];
        var j;
        var list = [];
        var mod;
        var mods = [];
        var pass = true;
        var $el;
        var $item;

        if(modifiers instanceof String) {
            mods = [modifiers];
        }

        if(selector instanceof Element) {
            items.push(selector);
        } else if(parent instanceof Element) {
            list = parent.querySelectorAll(selector);
            list = _ao.visible(list);
            for($item of list) {
                items.push($item);
            }
        }

        for($item of items) {
            $item.click();
        }
    };
    */

    _ao.defaultCb = function(err, response) {
        var messages = [];
        var data;
        if(err) {
            // This parses the JSON twice so would not be optimal for large JSON payloads.
            if(_ao.isJSON(response)) {
                data = JSON.parse(response);

                if(data.messages) {
                    _ao.error(data.messages);
                } else {
                    _ao.error('There was a problem processing the submission.');
                }
            }


            /*
            if(data.messages) {
                alert(data.messages.join("\n"));
            } else {
                alert(data);
            }
            */
        }
    };

    _ao.empty = function() {};

    _ao.error = function(messages, title) {
        var content = '';
        var e;
        var i = 0;
        var j = 0;
        var response = {};
        var message;
        var key;
        var json;
        if(messages instanceof Event) {
            e = messages;
            response = JSON.parse(e.detail.response);
            content = '';
            i = 0;
            for(message of response.messages) {
                if(i != 0) {
                    content += '<br>';
                }
                content += _ao.esc(message);
                i++;
            }
            _ao.dangerousHTML('#message.overlay .content', content);
            ao.qs('#message.overlay').classList.add('show');
            ao.qs('#processing.overlay').classList.remove('show');
        } else if(Array.isArray(messages)) {
            for(i = 0; i < messages.length; i++) {
            }
        } else if(typeof messages == 'object') {
            i = 0;
            for(key in messages) {
                if(i != 0) {
                    content += '<br>';
                }

                if(typeof messages[key] == 'array') {
                    j = 0;
                    for(message of messages[key]) {
                        if(j != 0) {
                            content += '<br>';
                        }
                        content += _ao.esc(messages);
                        j++;
                    }
                    i++;
                } else {
                    content += _ao.esc(messages[key]);
                    i++;
                }
            }
            _ao.dangerousHTML('#message.overlay .content', content);
            ao.qs('#message.overlay').classList.add('show');
            ao.qs('#processing.overlay').classList.remove('show');
        } else {
            if(_ao.isJSON(messages)) {
                json = JSON.parse(messages);

                if(json.messages) {
                    content = '';
                    i = 0;
                    for(key in json.messages) {
                        if(i != 0) {
                            content += '<br>';
                        }
                        content += _ao.esc(json.messages[key]);
                        i++;
                    }
                } else {
                    content += _ao.esc(messages[key]);
                    i++;
                }
            } else {
                content = _ao.esc(messages);
                content = _ao.nl2br(content);
            }

            _ao.dangerousHTML('#message.overlay .content', content);
            if(title) {
                _ao.text('#message.overlay h2', title);
            }
            ao.qs('#message.overlay').classList.add('show');
            ao.qs('#processing.overlay').classList.remove('show');
        }
    };

    // A clever way to get HTML encoded text.
    _ao.esc = function(input) {
        var $div = document.createElement('div');
        $div.textContent = input;
        return $div.innerHTML;
    };

    _ao.dangerousHTML = function(selector, content) {
        ao.qs(selector).innerHTML = content;
    };

    _ao.isJSON = function(input) {
        try {
            var json = JSON.parse(input);
            if(typeof json == 'object') {
                return true;
            } else {
                return false;
            }
        } catch(e) {
            return false;
        }
    }

    _ao.nl2br = function(input) {
        var output = input.replace(/(\r\n|\n\r|\r|\n)/g, '<br>');
        return output;
    };

    _ao.reload = function() {
        window.location.href = window.location.href;
    };

    // https://stackoverflow.com/questions/494143/creating-a-new-dom-element-from-an-html-string-using-built-in-dom-methods-or-pro
    _ao.replaceWith = function($old_el, $new_el) {
        var $tpl;
        if(!($new_el instanceof Element)) {
            $tpl = document.createElement('template');
            $tpl.innerHTML = $new_el;
            $new_el = $tpl.content.firstChild;
        }
        var $parent = $old_el.parentNode; 
        $parent.replaceChild($new_el, $old_el);

        return $new_el;
    }  


    _ao.submit = function($form, cb) {
        console.log('_ao.submit');
        if($form instanceof Event) {
            var e = $form;
            e.preventDefault();
            $form = e.target;
        }
        if($form.nodeName != 'FORM') {
            _ao.error('The form was not able to process the submission.');
            return;
        }

        var action = $form.getAttribute('action');
        var data = new FormData($form);
        var method = $form.getAttribute('method');
        var $fieldset = $form.querySelector('fieldset');
        var $button = $form.querySelector('input[type=submit]');

        method = method.toUpperCase();

        if($fieldset) {
            $fieldset.disabled = true;
        }
        //ao.qs('.overlay.processing').classList.add('show');

        if($button) {
            $button.disabled = true;
        }

        if(method == 'POST') {
            if(cb) {
                ao.post(action, data, cb);
            } else {
                ao.post(action, data, _ao.responseProcess.bind(_ao, $form));
            }
        }
    };

    _ao.responseError = function($form, err, response) {
        console.log('_ao.responseError');
        var error = $form.dataset.error || '';

        if(!error) {
            _ao.error('There was a problem processing the submission.');
        }

		$form.querySelector('fieldset').disabled = false;
    };

    _ao.responseProcess = function($form, err, response) {
        var event;
        if(err) {
            event = new CustomEvent('error', {
                bubbles: true,
                detail: {error: err, response: response},
            });
            $form.dispatchEvent(event);
        } else {
            event = new CustomEvent('success', {
                bubbles: true,
                detail: {response: response},
            });
            $form.dispatchEvent(event);
        }

        var $fieldset = $form.querySelector('fieldset');
        if($fieldset) {
            $fieldset.disabled = false;
        }
        var $button = $form.querySelector('input[type=submit]');
        if($button) {
            $button.disabled = false;
        }
        ao.qs('.overlay.processing').classList.remove('show');
    };

    _ao.responseSuccess = function($form, err, response) {
		$form.querySelector('fieldset').disabled = false;
        ao.qs('.overlay.message').classList.remove('show');
        ao.qs('.overlay.processing').classList.remove('show');
    };

    _ao.text = function(selector, content) {
        if(selector instanceof Element) {
            selector.textContent = content;
        } else {
            ao.qs(selector).textContent = content;
        }
    };

    _ao._toggleSuffixClosest = function(suffix, selector) {
        return function(e) {
            return _ao.toggleSuffixClosest(e, suffix, selector);
        };
    };
    _ao.toggleSuffixClosest = function($item, suffix, selector) {
        var cls;
        var e;
        var $el;
        if($item instanceof Event) {
            e = $item;
            $item = e.target;
        }

        if(!selector) {
            selector = '.' + suffix;
        }

        $el = $item.closest(selector);

        if($el) {
            for(cls of $el.classList) {
                if(cls.endsWith(suffix)) {
                    $el.classList.toggle(cls);
                }
            }
        }
    };

    // Accepts a selector or an element
    _ao.visible = function(selector, $parent) {
        var $item;
        var list = [];
        var output = [];
        if(selector instanceof Element) {
            list.push(selector);
        } else if(selector instanceof Array) {
            list = selector;
        } else if($parent instanceof Element) {
            list = Array.from($parent.querySelectorAll(selector));
        } else {
            list = Array.from(ao.qsa(selector));
        }

        for($item of list) {
            if($item.offsetWidth || $item.offsetHeight) {
                output.push($item);
            }
        }

        return output;
    };

    // Eventually build out
    _ao.$ = function(selector, $parent) {
        if(selector instanceof Element) {
        }
    }

    // Eventually build out
    _ao.Chainable = function() {
    };

    function ajaxForm(e) {
        console.log('ajaxForm');
		var $form = e.target;

		_ao.submit(e.target);

        e.preventDefault();
    }

    function clickClose(e) {
        var $overlay = e.target.closest('.overlay');
        $overlay.classList.remove('show');
        _ao.text($overlay.querySelector('h2'), 'Error');
        _ao.text($overlay.querySelector('.content'), '');
    }

    function showError(messages) {
	}

    function init() {
        //ao.listen('click', '.overlay [aria-label=Close]', clickClose);

        //ao.listen('submit', 'form[data-success]', ajaxForm);
    }

    init();
})();

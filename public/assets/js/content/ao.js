window.ao = {};
(function() {
    ao.qs = document.querySelector.bind(document);
    ao.qsa = document.querySelectorAll.bind(document);

    ao._caller = function(func, e) {
        var fn;
        var cont = false;
        if(!(func instanceof Array)) {
            func = [func];
        }

        for(fn of func) {
            if(fn == ao.continue) {
                cont = true;
            } else {
                fn(e);
            }
        }

        if(!cont) {
            e.preventDefault();
        }
    };

    // Used as a placeholder to keep e.preventDefault() from being triggered.
    ao.continue = function(e) {
    };

    ao.get = function(url, cb) {
        var request = new XMLHttpRequest();
        request.open('GET', url, true);
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        request.onload = function() {
            if (request.status >= 200 && request.status < 400) {
                var data = request.responseText;
                cb(null, data);
            } else {
                cb(request.status);

            }
        };

        request.onerror = function() {
            cb('Error');
        };

        request.send();
    };

    // ao.listen('click', 'article .edit ._cancel', closeTool);
    // OR
    // ao.listen('click', 'article .edit ._cancel', closeTool, resetTool);
    // OR
    // ao.listen('click', 'article .edit ._cancel', [closeTool, resetTool]);
    ao.listen = function(event, selector, func, ...funcs) {
        if (selector instanceof Function) {
            func = [selector];
            selector = '';
        } else if(selector instanceof Array) {
            func = selector;
            selector = '';
        }

        func = [].concat(func, funcs);

        //document.addEventListener('click', clickDocument.bind(null, selector, func));
        //document.addEventListener('submit', submitDocument.bind(null, selector, func));

        if(event == 'success') {
            //document.addEventListener('submit', submitDocument.bind(null, selector));
            document.addEventListener('submit', processEvent.bind(null, selector, _ao.submit));
            document.addEventListener('success', processEvent.bind(null, selector, ao._caller.bind(null, func)));
            document.addEventListener('error', processEvent.bind(null, selector, _ao.error));
        } else {
            document.addEventListener(event, processEvent.bind(null, selector, ao._caller.bind(null, func)));
        }
    };

    ao.post = function(url, data, cb) {
        var request = new XMLHttpRequest();
        request.open('POST', url, true);

        request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        data = new URLSearchParams(data).toString();

        request.onload = function() {
            if (request.status >= 200 && request.status < 400) {
                var data = request.responseText;
                cb(null, data);
            } else {
                cb(request.status, request.responseText);
            }
        };

        request.onerror = function() {
            cb('Error');
        };

        request.send(data);
    };

	ao.ready = function(func) {
		// see if DOM is already available
		if (document.readyState === "complete" || document.readyState === "interactive") {
			// call on next available tick
			setTimeout(func, 1);
		} else {
			document.addEventListener("DOMContentLoaded", func);
		}
	}

	ao.submit = function(element) {
    }

    // Not currently used.
    function clickDocument(selector, func, e) {
        //if(selector && e.target.matches(selector)) {
        //}
        if(selector && e.currentTarget.matches(selector)) {
            func(e);
        } else if(!selector) {
            func(e);
        }
    }

    function processEvent(selector, func, e) {
        if(selector && e.target.matches(selector)) {
            e.ao = {};
            e.ao.target = e.target;
            console.log(e);
            func(e);
        } else if(selector && e.target.closest(selector)) {
            e.ao = {};
            e.ao.target = e.target.closest(selector);
            console.log(e);
            func(e);
        } else if(!selector) {
            func(e);
        }
    }

    function submitDocument(selector, e) {
        if(selector && e.target.matches(selector)) {
            e.preventDefault();
        }
    }
})();

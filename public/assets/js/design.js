(function() {

    function clickAdd(e) {
        var $aim;
        var $target = e.target;

        var data_aim = $target.getAttribute('data-aim');
        var data_add = $target.getAttribute('data-add');

        if(data_aim) {
            $aim = ao.qs(data_aim);
        } else {
            $aim = $target;
        }

        $aim.classList.add(data_add);
    }

    function clickRemove(e) {
        var $aim;
        var $target = e.target;

        var data_aim = $target.getAttribute('data-aim');
        var data_remove = $target.getAttribute('data-remove');

        if(data_aim) {
            $aim = ao.qs(data_aim);
        } else if($target.classList.contains(data_remove)) {
            $aim = $target;
        } else {
            $aim = $target.closest('.' + data_remove);
        }

        if($aim) {
            $aim.classList.remove(data_remove);
        }
    }

    function close(e) {
        var $el = e.target.closest('.show');

        if($el) {
            $el.classList.remove('show');
        }
    }

    function reset(e) {
        var $el = e.target.closest('div');
        var $textarea;

        if($el) {
            $textarea = $el.querySelector('textarea');

            if($textarea) {
                $textarea.value = '';
            }
        }
    }

    function toggleMenu(e) {
        var $body = ao.qs('body');
        if(e.ao.target.checked) {
            $body.classList.add('menu_active');
        } else {
            $body.classList.remove('menu_active');
        }
    }

    function init() {
        ao.listen('change', '#toggle_menu', toggleMenu);

        ao.listen('click', '[data-add]', clickAdd);
        ao.listen('click', '[data-remove]', clickRemove);
    }

    init();

})();

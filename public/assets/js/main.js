(function() {

    function changeStar(e) {
        var $target = e.ao.target;
        var $stars = $target.closest('.stars');
        var id = $target.getAttribute('data-id');
        var args;
        var url = '/ajax/rate/' + id;


        //$stars.classList.add('process');

        args = {}; 
        args.rating = $target.value;

        ao.post(url, args, function(err, response) {
            //$stars.classList.remove('process');
        });
    }

    function changeTag(e) {
        var $target = e.ao.target;
        var item_id = $target.getAttribute('data-item-id');
        var tag_id = $target.value;

        var args;
        var url = '/ajax/tag';

        //$target.classList.add('process');

        args = {}; 
        args.item_id = item_id;
        args.tag_id = tag_id;

        if(!$target.checked) {
            args.action = 'remove';
        } else {
            args.action = 'add';
        }

        ao.post(url, args, function(err, response) {
            //$target.classList.remove('process');
        });
    }

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

    function dropped(e) {
        orderChanged(e.target);
    }

    function moveDown(e) {
        console.log('moveDown');
        console.log(e);
        var $target = e.target;
        var $table = $target.closest('table');
        var $tbody = $target.closest('tbody');
        var $tr = $target.closest('tr');
        var $next = $tr.nextElementSibling;
        if($next) {
            $tr.before($next);
            orderChanged($table);
        }
    }

    function moveUp(e) {
        console.log('moveUp');
        console.log(e);
        var $target = e.target;
        var $table = $target.closest('table');
        var $tbody = $target.closest('tbody');
        var $tr = $target.closest('tr');
        var $prev = $tr.previousElementSibling;
        if($prev) {
            $tr.after($prev);
            orderChanged($table);
        }
    } 

    function orderChanged($table) {
        var ids;
        var list;
        var url;
        $tr = $table.querySelectorAll('[data-id]');

        url = $table.getAttribute('data-action');

        list = []; 
        $tr.forEach(function($item) {
            list.push($item.getAttribute('data-id'));
        }); 

        list.reverse();
        ids = list.join(',');
        args = {}; 
        args.ids = ids;

        ao.post(url, args, function(err, data) {
            //console.log(data);
        }); 
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

    function toggleArrow(e) {
        var $target = e.ao.target;
        var category_id = $target.getAttribute('data-toggle');

        if($target.checked) {
            ao.post('/ajax/toggle/' + category_id, {'opened': 1});
        } else {
            ao.post('/ajax/toggle/' + category_id, {'opened': 0});
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

        ao.listen('change', '[data-toggle]', toggleArrow);

        ao.listen('change', '.star', changeStar);
        ao.listen('change', '.tag', changeTag);

        ao.listen('click', '[data-add]', clickAdd);
        ao.listen('click', '[data-remove]', clickRemove);

        ao.listen('click', '.sort_down', moveDown);
        ao.listen('click', '.sort_up', moveUp);

        ao.listen('dropped', 'table', dropped);
    }

    init();

})();

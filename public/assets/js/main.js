(function() {
    var step_continue = true;

    function changeSort(e) {
        var $target = e.ao.target;
        var $stars = $target.closest('.stars');
        var id = $target.getAttribute('data-id');
        var args;
        var url = '/ajax/sort';


        //$stars.classList.add('process');

        args = {}; 
        args.sort = $target.value;

        ao.post(url, args, function(err, response) {
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
            } else {
                _ao.reload();
            }
        });
    }

    function changeStar(e) {
        var $target = e.ao.target;
        var $stars = $target.closest('.stars');
        var id = $target.getAttribute('data-id');
        var args;
        var url = '/ajax/rate/' + id;


        $stars.classList.add('process');

        args = {}; 
        args.rating = $target.value;

        ao.post(url, args, function(err, response) {
            $stars.classList.remove('process');
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

    function clickRefreshCancel(e) {
        step_continue = false;

        var $button = ao.qs('#refresh form button');
        $button.classList.remove('process');
        $button.disabled = false;
        $button.textContent = 'Refresh';

        $button.closest('.show').classList.remove('show');
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

    function clickArchive(e) {
        var $button = e.ao.target;
        var $article = $button.closest('article');
        var id = $article.getAttribute('data-id');
        var args = {};

        $button.classList.add('process');

        ao.post('/ajax/archive/' + id, args, function(err, response) {
            var data = JSON.parse(response);
            if(data.status && data.status == 'success') {
                $article.remove();
            } else {
                // There is an error. Need to improve error response.
                $button.classList.remove('process');
            }
        });
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

    function refresh($form, index, feed_ids) {
        var $button = $form.querySelector('button');

        if(index != -1 && feed_ids[index]) {
            $button.textContent = 'Processing ' + (index + 1) + ' of ' + feed_ids.length + '...';
            ao.post('/ajax/refresh/' + feed_ids[index], {index: index, feed_ids: JSON.stringify(feed_ids)}, function(err, response) {
                var data;
                if(err) {
                    _ao.error(response);
                } else {
                    data = JSON.parse(response);
                    if(typeof data.index !== 'undefined' && data.index != -1 && typeof data.feed_ids == 'object') {
                        if(step_continue) {
                            refresh($form, data.index, data.feed_ids);
                        }
                    } else {
                        if(step_continue) {
                            _ao.reload();
                        }
                    }
                }
            });
        } else {
            _ao.reload();
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

    function submitRefresh(e) {
        var $form = e.target;
        var $button = $form.querySelector('button');
        $button.disabled = true;
        $button.classList.add('process');
        $button.textContent = 'Processing...';

        step_continue = true;

        _ao.submit(e, function(err, response) {
            var data;
            if(err) {
                _ao.error(response);
            } else {
                data = JSON.parse(response);

                if(typeof data.index !== 'undefined' && typeof data.feed_ids == 'object') {
                    refresh($form, data.index, data.feed_ids);
                }
            }
        });
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

        ao.listen('change', '[name=sort]', changeSort);

        ao.listen('change', '.star', changeStar);
        ao.listen('change', '.tag', changeTag);

        ao.listen('click', '.archive', clickArchive);

        ao.listen('click', '#refresh .cancel', clickRefreshCancel);
        ao.listen('click', '#refresh .close', clickRefreshCancel);

        ao.listen('success', '#archive_all_form', _ao.reload);

        ao.listen('click', '[data-add]', clickAdd);
        ao.listen('click', '[data-remove]', clickRemove);

        ao.listen('click', '.sort_down', moveDown);
        ao.listen('click', '.sort_up', moveUp);

        ao.listen('dropped', 'table', dropped);

        ao.listen('submit', '#refresh_form', submitRefresh);
    }

    init();

})();

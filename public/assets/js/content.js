(function() {

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

    function init() {
        ao.listen('click', '.close', close, reset);
    }

    init();

})();

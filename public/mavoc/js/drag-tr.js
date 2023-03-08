(function() {
    var $table;
    var $row;

    function start(e) {
        $row = e.target.closest('tr');
        $table = e.target.closest('table');
    }

    function over(e) {
        var $tbody = e.target.closest('tbody');
        var $tr = e.target.closest('tr');

        var children = Array.from($tbody.children);
        if(children.indexOf($tr) > children.indexOf($row)) {
            $tr.after($row);
        } else {
            $tr.before($row);
        }

        e.preventDefault();
    }

    function end(e) {
        var event = new CustomEvent('dropped', {bubbles: true, cancelable: true});
        $table.dispatchEvent(event);
    }

    function resizeWindow(media) {
        var $trs = document.querySelectorAll('.draggable tbody tr');
        $trs.forEach(function($item) {
            if(media.matches) {
                $item.draggable = false;
            } else {
                $item.draggable = true;
            }
        });
    }

    function init() {
        var i;
        var $tr = document.querySelectorAll('.draggable tbody tr');

        $tr.forEach(function($item) {
            $item.ondragstart = start;
            $item.ondragover = over;
            $item.ondragend = end;
        });

        var media = window.matchMedia('(max-width: 1200px)');
        resizeWindow(media);
        media.addEventListener('change', resizeWindow);

        /*
        MobileDragDrop.polyfill({
            // use this to make use of the scroll behaviour
            // dragImageTranslateOverride: MobileDragDrop.scrollBehaviourDragImageTranslateOverride
        });
        */

    }

    function ready(fn) {
        // see if DOM is already available
        if (document.readyState === "complete" || document.readyState === "interactive") {
            // call on next available tick
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    ready(init);
})();

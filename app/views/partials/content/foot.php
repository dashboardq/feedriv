        <div id="archive_all" class="overlay">
            <div class="popup">
                <h2>Clear All</h2>
                <p>Are you sure you want to archive all items for the current filter?</p>
                <div>
                    <form id="archive_all_form" action="/ajax/archive" method="POST">
                        <input type="submit" value="Archive All" />
                    </form>
                    <button class="cancel">Cancel</button>
                </div>
                <button class="close">Close</button>
            </div>
        </div>

        <div class="overlay processing" hidden>
            <div class="loading"><span></span></div>
        </div>

        <div class="overlay modal" hidden>
            <div class="box">
                <h2>Error</h2>
                <div class="content"></div>
                <button class="_close" aria-label="Close">&times;</button>
            </div>
        </div>

        <?php if($user): ?>
        <form id="logout" action="/logout" method="POST" class="hidden"></form>
        <?php endif; ?>

        <script src="/assets/js/content/ao.js?cache-date=<?php esc($cache_date); ?>"></script>
        <script src="/assets/js/content/_ao.js?cache-date=<?php esc($cache_date); ?>"></script>
        <script src="/assets/js/content.js?cache-date=<?php esc($cache_date); ?>"></script>

        <?php echo ao()->env('APP_ANALYTICS'); ?>

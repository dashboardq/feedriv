        <div id="archive_all" class="overlay">
            <div class="modal">
                <h2>Archive All</h2>
                <p>Are you sure you want to archive all items for the current filters?</p>
                <div>
                    <form id="archive_all_form" action="/ajax/archive" method="POST">
                        <input type="submit" value="Archive All" />
                    </form>
                    <button class="cancel" data-remove="show">Cancel</button>
                </div>
                <button aria-label="Close" data-remove="show">&times;</button>
            </div>
        </div>

        <div id="refresh" class="overlay">
            <div class="modal">
                <h2>Refresh</h2>
                <p>Reload each of the currently selected feeds.</p>
                <div>
                    <form id="refresh_form" action="/ajax/refresh" method="POST">
                        <button>Refresh</button>
                    </form>
                    <button class="cancel">Cancel</button>
                </div>
                <button aria-label="Close" class="close">&times;</button>
            </div>
        </div>

        <div id="error" class="overlay message" hidden>
            <div class="box">
                <h2>Error</h2>
                <div class="content"></div>
                <button class="_close" aria-label="Close">&times;</button>
            </div>
        </div>

        <div id="message" class="overlay">
            <div class="modal">
                <h2>Error</h2>
                <div class="content"></div>
                <button aria-label="Close" data-remove="show">&times;</button>
            </div>
        </div>

        <div id="processing" class="overlay processing" hidden>
            <div class="loading"><span></span></div>
        </div>

        <?php if($user): ?>
        <form id="logout" action="/logout" method="POST" class="hidden"></form>
        <?php endif; ?>

        <script src="/mavoc/js/ao.js?cache-date=<?php esc($cache_date); ?>"></script>
        <script src="/mavoc/js/_ao.js?cache-date=<?php esc($cache_date); ?>"></script>
        <script src="/assets/js/main.js?cache-date=<?php esc($cache_date); ?>"></script>

        <?php echo ao()->env('APP_ANALYTICS'); ?>

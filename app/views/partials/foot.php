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

        <script src="/mavoc/js/ao.js?cache-date=<?php esc($cache_date); ?>"></script>
        <script src="/mavoc/js/_ao.js?cache-date=<?php esc($cache_date); ?>"></script>
        <script src="/assets/js/main.js?cache-date=<?php esc($cache_date); ?>"></script>

        <?php if(in_array(ao()->env('APP_ENV'), ['prod', 'production'])): ?>
             <?php ao()->hook('prod_analytics'); ?>
         <?php endif; ?>

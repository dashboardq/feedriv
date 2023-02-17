        <meta charset="utf-8">     
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?php esc(ao()->hook('app_html_head_title', $title)); ?></title>

        <link href="/assets/css/design/ao.css?cache-date=<?php esc($cache_date ?? '2022-07-15'); ?>" rel="stylesheet">
        <link href="/assets/css/design.css?cache-date=<?php esc($cache_date ?? '2022-07-15'); ?>" rel="stylesheet">

        <link rel="canonical" href="<?php esc($req->canonical); ?>" />

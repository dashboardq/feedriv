        <meta charset="utf-8">     
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?php esc(ao()->hook('app_html_head_title', $title)); ?></title>

        <link href="/mavoc/css/ao.css?cache-date=<?php esc($cache_date); ?>" rel="stylesheet">
        <link href="/assets/css/main.css?cache-date=<?php esc($cache_date); ?>" rel="stylesheet">

        <link rel="canonical" href="<?php esc($req->canonical); ?>" />

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#00a300">
        <meta name="theme-color" content="#ffffff">

		<meta property="og:title" content="FeedRiv Feed Reader" />
		<meta property="og:description" content="A simple feed reader that lets you tag and rate items." />
		<meta property="og:type" content="website" />
		<meta property="og:url" content="https://www.feedriv.com" />
		<meta property="og:image" itemprop="image" content="https://www.feedriv.com/assets/images/share_1200x630.png" />
		<meta property="og:image:secure_url" itemprop="image" content="https://www.feedriv.com/assets/images/share_1200x630.png" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />

		<link itemprop="thumbnailUrl" href="https://www.feedriv.com/assets/images/share_1200x630.png"> 

		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:image" content="https://www.feedriv.com/assets/images/share_1200x600.png">

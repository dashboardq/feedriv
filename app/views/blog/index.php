<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="page_blog <?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('header'); ?>
            <main>
                <section class="page">
                    <h1>Blog</h1>
                    <ul>
                    <?php foreach($items as $item): ?>
                        <li><?php esc($item['date']); ?> <a href="<?php esc($item['permalink']); ?>"><?php esc($item['title']); ?></a></li>
                    <?php endforeach; ?>
                    </ul>
                </section>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>

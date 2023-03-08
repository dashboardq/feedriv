<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('header'); ?>
            <main>
                <div class="page">
                    <h1><?php esc($title); ?></h1>
                    <p><a href="/category/edit/<?php esc($item->data['category_id']); ?>">&lt; Back</a></p>

                    <?php $res->html->messages(); ?>
                    <form method="POST">
                        <?php $res->html->text('Word', 'word', $item->data['word'], '', 'disabled'); ?>
                        <?php $res->html->checkbox('Use Manual Score', 'locked'); ?>
                        <?php $res->html->text('Manual Score', 'locked_score'); ?>
                        <?php $res->html->submit('Update'); ?>
                    </form>
                </div>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>

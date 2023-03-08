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
                        <?php $res->html->select('Category', 'category_id', $categories); ?>

                        <p><?php $res->html->a('/category-add', 'Add Category'); ?></p>

                        <?php //$res->html->text('Feed URL / @Twitter / @Mastadon@Account', 'url'); ?>
                        <?php $res->html->text('Feed URL', 'url'); ?>
                        <?php $res->html->submit('Update'); ?>
                    </form>
                </div>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>

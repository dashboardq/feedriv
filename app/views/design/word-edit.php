<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('design/head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('design/header'); ?>
            <main>
                <div class="page">
                    <h1><?php esc($title); ?></h1>
                    <p><a href="/design/category-edit">&lt; Back</a></p>

                    <form>
                        <?php $res->html->text('Word'); ?>
                        <?php $res->html->text('Score'); ?>
                        <?php $res->html->submit('Update'); ?>
                    </form>
                </div>
            </main>
            <?php $res->partial('design/footer'); ?>
        </div>
		<?php $res->partial('design/foot'); ?>
    </body>
</html>

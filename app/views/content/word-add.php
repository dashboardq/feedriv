<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('content/head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('content/header'); ?>
            <main>
                <div class="page">
                    <h1><?php esc($title); ?></h1>
                    <p><a href="/content/category-edit">&lt; Back</a></p>

                    <form>
                        <?php $res->html->text('Word'); ?>
                        <?php $res->html->text('Score'); ?>
                        <?php $res->html->submit('Add'); ?>
                    </form>
                </div>
            </main>
            <?php $res->partial('content/footer'); ?>
        </div>
		<?php $res->partial('content/foot'); ?>
    </body>
</html>

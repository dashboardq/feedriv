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
                    <p><a href="/content/settings">&lt; Back</a></p>

                    <form>
                        <?php $res->html->color('Color'); ?>
                        <?php $res->html->submit('Update'); ?>
                    </form>
                </div>
            </main>
            <?php $res->partial('content/footer'); ?>
        </div>
		<?php $res->partial('content/foot'); ?>
    </body>
</html>

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
                    <p><a href="/content/categories-edit">&lt; Back</a></p>

                    <p>If you have any questions, problems, or concerns about anything, please do not hesitate to make contact.</p> 
                    <?php $res->html->messages(); ?>

                    <form method="POST">
                        <?php $res->html->text('Name'); ?>

                        <?php $res->html->text('Email'); ?>

                        <?php $res->html->textarea('Message'); ?>

                        <?php $res->html->submit('Send', 'button button_invert'); ?>
                    </form>
                </div>
            </main>
            <?php $res->partial('content/footer'); ?>
        </div>
		<?php $res->partial('content/foot'); ?>
    </body>
</html>

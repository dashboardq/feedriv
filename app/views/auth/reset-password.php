<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('header'); ?>
            <main class="page">
                <section class="box">
                    <?php $res->html->messages(); ?>

                    <section class="reset_password">
                        <h2><?php esc($title); ?></h2>
                        <form method="POST">
                            <?php $res->html->hidden('user_id', $user_id); ?>
                            <?php $res->html->hidden('token', $token); ?>

                            <?php $res->html->password('New Password'); ?>

                            <?php $res->html->submit('Submit'); ?>
                        </form>
                    </section>

                </section>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>


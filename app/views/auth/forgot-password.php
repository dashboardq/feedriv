<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('header'); ?>
            <main>
                <section class="box">
                    <?php $res->html->messages(); ?>

                    <section class="forgot_password">
                        <h2><?php esc($title); ?></h2>
                        <form method="POST">
                            <p>Please enter your email below to reset your password.</p>
                            <?php $res->html->text('Email'); ?>

                            <?php $res->html->submit('Submit'); ?>
                            
                            <div>
                                <a href="/login">&lt; Back to login</a>
                            </div>
                        </form>
                    </section>

                </section>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>


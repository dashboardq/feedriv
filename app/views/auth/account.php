<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('header'); ?>
            <main>
                <section class="page">
                    <h2>Account</h2>

                    <?php if(ao()->env('APP_LOGIN_TYPE') == 'db'): ?>
                        <?php $res->html->messages(); ?>
                        <form method="POST">
                            <?php $res->html->text('Full Name', 'name'); ?>

                            <?php $res->html->text('Email'); ?>
                            
                            <div>
                                <a href="/change-password">Change Password</a>
                            </div>

                            <?php $res->html->submit('Update'); ?>
                        </form>
                    <?php else: ?>
                        <form method="POST">
                            <?php $res->html->text('Email', '', '', '', 'disabled'); ?>
                        </form>
                    <?php endif; ?>

                </section>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>


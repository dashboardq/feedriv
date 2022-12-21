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
                    <section class="full">
                        <?php $res->html->messages(); ?>
                    </section>

                    <section class="card login">
                        <h2>Login</h2>
                        <form method="POST">
                            <?php $res->html->text('Email', 'login_email'); ?>

                            <?php $res->html->password('Password', 'login_password'); ?>

                            <?php $res->html->submit('Login'); ?>
                            
                            <div>
                                <a href="/forgot-password">Forgot password?</a>
                            </div>
                        </form>
                    </section>

                    <?php if(ao()->env('APP_REGISTER_ALLOW') && ao()->env('APP_LOGIN_TYPE') == 'db'): ?>
                    <section class="card register">
                        <h2>Register</h2>
                        <form action="<?php uri('register'); ?>" method="POST">
                            <?php $res->html->text('Full Name', 'name'); ?>

                            <?php $res->html->text('Email'); ?>

                            <?php $res->html->password('Password'); ?>

                            <div>
                                <p>By submitting this form you are agreeing to the <a href="<?php uri('terms'); ?>">Terms of Service</a> and the <a href="<?php uri('privacy'); ?>">Privacy Policy</a>.</p>
                            </div>

                            <?php $res->html->submit('Register'); ?>
                        </form>
                    </section>
                    <?php endif; ?>

                </section>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>


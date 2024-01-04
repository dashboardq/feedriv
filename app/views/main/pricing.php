<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('header'); ?>
            <main>
                <div class="box">
                    <h1>Pricing</h1>
                    <ul class="cards">
                        <li class="card">
                            <h2>Free</h2>
                            <h3>$0</h3>
                            <ul>
                                <li>Manually refresh feeds.</li>
                            </ul>
                            <a href="/login" class="button">Get Started</a>
                        </li>
                        <li class="card -highlight">
                            <h2>Premium</h2>
                            <h3>$12/mo</h3>
                            <ul>
                                <li>Feeds automatically refresh every hour.</li>
                            </ul>
                            <a href="/login" class="button">Get Started</a>
                        </li>
                    </ul>
                </div>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>

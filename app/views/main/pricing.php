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
                    <ul>
                        <li>
                            <h2>Free</h2>
                            <h3>$0</h3>
                            <ul>
                                <li>Benefit <strong>1 listed</strong> here</li>
                            </ul>
                            <a href="/login" class="button">Get Started</a>
                        </li>
                        <li class="card -highlight">
                            <h2>Basic</h2>
                            <h3>$12/mo</h3>
                            <ul>
                                <li>Benefit <strong>1 listed</strong> here</li>
                                <li>Benefit <strong>2 listed</strong> here</li>
                            </ul>
                            <a href="/login" class="button">Get Started</a>
                        </li>
                        <li class="card">
                            <h2>Intermediate</h2>
                            <h3>$48/mo</h3>
                            <ul>
                                <li>Benefit <strong>1 listed</strong> here</li>
                                <li>Benefit <strong>2 listed</strong> here</li>
                                <li>Benefit <strong>3 listed</strong> here</li>
                            </ul>
                            <a href="/login" class="button">Get Started</a>
                        </li>
                        <li class="card">
                            <h2>Advanced</h2>
                            <h3>$98/mo</h3>
                            <ul>
                                <li>Benefit <strong>1 listed</strong> here</li>
                                <li>Benefit <strong>2 listed</strong> here</li>
                                <li>Benefit <strong>3 listed</strong> here</li>
                                <li>Benefit <strong>4 listed</strong> here</li>
                            </ul>
                            <a href="/login" class="button">Get Started</a>
                        </li>
                        <li class="card">
                            <h2>Custom</h2>
                            <h3>Get In Touch</h3>
                            <ul>
                                <li>If you are not seeing a plan that meets your needs, please feel free to reach out.</li>
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

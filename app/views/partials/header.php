        <header>
            <div class="box">
                <h2><a href="/"><?php esc(ao()->env('APP_NAME')); ?></a></h2>
                <nav>
                    <menu>
                        <li><a href="/">Home</a></li>
                        <li><a href="/blog">Blog</a></li>
                        <li><a href="/rss">RSS</a></li>
                        <?php if($user): ?>
                        <li><a href="/account">Account</a></li>
                        <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout').submit();">Logout</a></li>
                        <?php else: ?>
<?php /*
                        <li><a href="/login">Login</a></li>
 */ ?>
                        <?php endif; ?>
                    </menu>
                </nav>
            </div>
        </header>
        <div class="notice error">
            <div class="box">
                <p>FeedRiv is an RSS Feed Reader being built as part of the <a href="https://www.agraddy.com/12-startups-in-12-months-open-source-edition">12 Startups in 12 Months (Open Source Edition)</a> challenge.</p>
                <p>FeedRiv is currently in development and being built in public. This site is the latest version of the development process. Feel free to test any and all features you see.</p>
                <p>You can stay updated on the progress by visiting the <a href="https://feedriv.com/blog">FeedRiv Blog</a>, checking out the code on the <a href="https://github.com/dashboardq/feedriv">FeedRiv Github Repo</a>, and following my <a href="https://twiter.com/agraddy">Anthony Graddy Twitter account</a>.</p>
                <p><strong>Note that due to heavy development, this site could break or lose data at anytime.</strong></p>
            </div>
        </div>

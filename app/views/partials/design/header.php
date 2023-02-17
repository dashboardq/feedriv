        <header>
            <div class="box">
                <h2><img src="/assets/images/logo.svg" class="logo" alt="Logo"><a href="/"><?php esc(ao()->env('APP_NAME')); ?></a></h2>
                <nav>
                    <input id="toggle_menu" class="custom" type="checkbox" />
                    <label for="toggle_menu">Toggle Menu</label>
                    <menu>
                        <li><a href="/">Home</a></li>
                        <li><a href="/blog">Blog</a></li>
                        <li><a href="/rss">RSS</a></li>
                        <li><a href="/design/feed">Feed</a></li>
                        <li><a href="/design/account">Account</a></li>
                        <li><a href="/design/settings">Settings</a></li>
                        <li><a href="#">Logout</a></li>
                    </menu>
                </nav>
            </div>
        </header>

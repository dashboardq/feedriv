        <header>
            <div class="box">
                <h2><img src="/assets/images/logo.svg" class="logo" alt="Logo"><a href="/"><?php esc(ao()->env('APP_NAME')); ?></a></h2>
                <nav>
                    <input id="toggle_menu" class="custom" type="checkbox" />
                    <label for="toggle_menu">Toggle Menu</label>
                    <menu>
                        <li><a href="/">Home</a></li>
                        <li><a href="/pricing">Pricing</a></li>
                        <li><a href="/blog">Blog</a></li>
                        <?php if($user): ?>
                        <li><a href="/feeds">Feeds</a></li>
                        <li><a href="/account">Account</a></li>
                        <li><a href="/settings">Settings</a></li>
                        <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout').submit();">Logout</a></li>
                        <?php else: ?>
                        <li><a href="/login">Login</a></li>
                        <?php endif; ?>
                    </menu>
                </nav>
            </div>
        </header>

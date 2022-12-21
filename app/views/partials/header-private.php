        <header class="private">
            <div class="box">
                <h2><a href="/"><?php esc(ao()->env('APP_NAME')); ?></a></h2>
                <nav>
                    <menu>
                        <li><a href="/">Home</a></li>
                        <li><a href="/pricing">Pricing</a></li>
                        <li><a href="/contact">Contact</a></li>
                        <?php if($user): ?>
                        <li><a href="/account">Account</a></li>
                        <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout').submit();">Logout</a></li>
                        <?php else: ?>
                        <li><a href="/login">Login</a></li>
                        <?php endif; ?>
                    </menu>
                </nav>
            </div>
        </header>

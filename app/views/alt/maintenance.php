<!DOCTYPE html>                
<html>
    <head>                     
        <meta charset="utf-8">     
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?php echo htmlspecialchars($title); ?></title>

        <link href="/assets/css/ao.css" rel="stylesheet">
        <link href="/assets/css/main.css" rel="stylesheet">
    </head> 
    <body class="page_home">
        <div id="app">
            <header>
                <div class="container">
                    <h2><a href="/"><?php echo htmlspecialchars($app_name); ?></a></h2>
                </div>
            </header>   
            <main>
                <section class="box">
                    <h1><?php echo htmlspecialchars($title); ?></h1>
                    <?php if($ending_relative): ?>
                        <p>The site is currently undergoing maintenance. It started at <?php echo htmlspecialchars($started); ?> and should last about <?php echo htmlspecialchars($ending); ?>.</p>   
                    <?php else: ?>
                        <p>The site is currently undergoing maintenance. It started at <?php echo htmlspecialchars($started); ?> and should end around <?php echo htmlspecialchars($ending); ?>.</p>
                    <?php endif; ?>
                </section>
            </main>
            <footer>
                <div class="container">
                    <p>&copy; <?php echo htmlspecialchars(date('Y') . ' ' . $app_name); ?></p>
                    <nav>
                        <ul>
                            <li><a href="/terms">Terms of Service</a></li>
                            <li><a href="/privacy">Privacy Policy</a></li>
                        </ul>
                    </nav>
                </div>
            </footer>
        </div>
    </body>
</html>

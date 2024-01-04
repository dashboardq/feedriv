<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
        <link href="/assets/css/home.css?cache-date=2023-12-23" rel="stylesheet">
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('header'); ?>
            <main>
                <?php $res->html->messages(); ?>

                <section class="welcome">
                    <div class="box">
                        <div class="primary">
                            <h1>Do you need a simple RSS feed reader?</h1>
                            <p>FeedRiv is a simple RSS feed reader that specializes in rating, tagging, and auto organizing feed items. If you are using an RSS feed to pull out important information, FeedRiv has the tools to make sorting by priority easy.</p>
                            <p>
                            <a href="/login" class="button">Get Started</a>
                            </p>
                        </div>
                        <div class="preview">
                            <img src="/assets/images/screenshot2.png" alt="App Screenshot" />
                        </div>
                    </div>
                </section>
                <section class="details">
                    <div class="box">
                        <h2>FeedRiv focuses on giving you the tools you need to filter what you see
                            <br>and quickly find, sort, and organize the information in your feed.</h2>
                        <div class="features">
                            <div class="feature icon_categories">
                                <h3>Set up different categories.</h3>
                                <p>Organize the feeds into categories that make sense for you.</p>
                                <img src="/assets/images/categories.png" alt="Categories Screenshot" />
                            </div>
                            <div class="feature icon_rate">
                                <h3>Rate items that are important.</h3>
                                <p>When an item is important you can mark it with a star rating to save it for later.</p>
                                <img src="/assets/images/rate.png" alt="Rate Screenshot" />
                            </div>
                            <div class="feature icon_auto_rating">
                                <h3>Star ratings build an automated rating system.</h3>
                                <p>As you rate different items, the system will remember your scores and can then apply those scores automatically to future items. You can then sort the feed by the most important items.</p>
                                <img src="/assets/images/auto_ratings.png" alt="Auto Ratings Screenshot" />
                            </div>

                            <div class="feature icon_tags">
                                <h3>Group items with tags.</h3>
                                <p>Tag the feed items and organize them into categories. You may want to save something to read later or you may want to make sure you reply to an item. The tags are completely customizable so you can set up tags for whatever categories you need.</p>
                                <img src="/assets/images/tags.png" alt="Tags Screenshot" />
                            </div>

                            <div class="feature icon_filter">
                                <h3>Filter items by categories, tags, ratings, and auto ratings.</h3>
                                <p>Quickly be able to drill down into the specific items you are looking to find. Whether you are wanting to only look at highly rated items or you want to see all the items marked with a specific tag, you can always quickly filter down the feeds to the items you need.</p>
                                <img src="/assets/images/filters.png" alt="Filters Screenshot" />
                            </div>

                            <div class="feature icon_more">
                                <h3>Not seeing a feature you need?</h3>
                                <p>Get in touch to see if what you are wanting can be added. Always open to hearing feedback and feature requests.</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="testimonials">
                    <div class="box">
                        <p>As a freelance developer, one of the main features I use RSS for today is looking for new freelance jobs posted online. I have different RSS feeds that I have subscribed to over the years. FeedRiv's auto rating system allows me to quickly find the jobs that are most suitable to my skills.</p>
                        <div class="bio">
                            <img src="/assets/images/profile.jpg" alt="Profile Image of Anthony Graddy" />
                            <cite><strong>Anthony Graddy</strong>Founder of FeedRiv</cite>
                        </div>
                    </div>
                </section>
<?php /*
                <section class="services">
                    <div class="box">
                        <h2>No additional services to list.</h2>
                        <p></p>
                        <p></p>
                    </div>
                </section>
 */ ?>
                <section class="ready">
                    <div class="box">
                        <h2>Ready to take control of your RSS experience?</h2>
                        <p>Get started today and start seeing the content that you want to see.</p>
                        <p><a href="/login" class="button">Get Started</a></p>
                    </div>
                </section>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
        <?php $res->partial('foot'); ?>
    </body>
</html>

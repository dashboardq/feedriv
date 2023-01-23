<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('content/head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('content/header'); ?>
            <?php $res->partial('content/sidebar_left'); ?>
            <main>
                <div class="filters">
                    <?php $res->html->select('', 'sort', [
                        ['label' => 'Date Asc', 'value' => 'date-asc'],
                        ['label' => 'Date Desc', 'value' => 'date-desc'],
                        ['label' => 'Auto Rate Asc', 'value' => 'date-asc'],
                        ['label' => 'Auto Rate Desc', 'value' => 'date-desc'],
                    ]); ?>
                    <button>Archive All</button>
                </div>
                <?php $res->html->messages(); ?>

                <div class="feed">
                    <article>
                        <h2>Feed Item</h2>
                        <div class="stars">
                            <label><input type="radio" name="star_[ID]" value="1" /> Star 1</label>
                            <label><input type="radio" name="star_[ID]" value="2" /> Star 2</label>
                            <label><input type="radio" name="star_[ID]" value="3" /> Star 3</label>
                            <label><input type="radio" name="star_[ID]" value="4" /> Star 4</label>
                            <label><input type="radio" name="star_[ID]" value="5" /> Star 5</label>
                            <span>(Auto Rating: 2.3)</span>
                        </div>
                        <div class="content">
                            <p>This is some example content from the feed item.</p>
                        </div>
                        <div class="tags">
                            <label><input type="checkbox" name="star_[ID]_[SLUG]" value="1" />To Read</label>
                            <label><input type="checkbox" name="star_[ID]_[SLUG]" value="1" />To Reply</label>
                            <label><input type="checkbox" name="star_[ID]_[SLUG]" value="1" />To Listen</label>
                        </div>
                    </article>
                    <article>
                        <h2>Feed Item</h2>
                        <div class="stars">
                            <label><input type="radio" name="star_[ID]" value="1" /> Star 1</label>
                            <label><input type="radio" name="star_[ID]" value="2" /> Star 2</label>
                            <label><input type="radio" name="star_[ID]" value="3" /> Star 3</label>
                            <label><input type="radio" name="star_[ID]" value="4" /> Star 4</label>
                            <label><input type="radio" name="star_[ID]" value="5" /> Star 5</label>
                            <span>(Auto Rating: 4.3)</span>
                        </div>
                        <div class="content">
                            <p>This is some example content from the feed item.</p>
                        </div>
                        <div class="tags">
                            <label><input type="checkbox" name="star_[ID]_[SLUG]" value="1" />To Read</label>
                            <label><input type="checkbox" name="star_[ID]_[SLUG]" value="1" />To Reply</label>
                            <label><input type="checkbox" name="star_[ID]_[SLUG]" value="1" />To Listen</label>
                        </div>
                    </article>
                </div>
            </main>
            <?php $res->partial('content/sidebar_right'); ?>
            <?php $res->partial('content/footer'); ?>
        </div>
		<?php $res->partial('content/foot'); ?>
    </body>
</html>

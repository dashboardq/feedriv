<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('design/head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('design/header'); ?>
            <?php $res->partial('design/sidebar_left'); ?>
            <main>
                <div class="box">
                    <div class="filters">
                        <div class="sort_box">
                            <h3>Sort</h3>
                            <?php $res->html->select('', 'sort', [
                                ['label' => 'Newest First', 'value' => 'date-desc'],
                                ['label' => 'Oldest First', 'value' => 'date-asc'],
                                ['label' => 'Highest Auto Rating First', 'value' => 'date-desc'],
                                ['label' => 'Lowest Auto Rating First', 'value' => 'date-asc'],
                            ]); ?>
                        </div>
                        <button data-aim="#refresh" data-add="show">Refresh</button>
                        <button data-aim="#archive_all" data-add="show">Archive All</button>
                    </div>
                    <?php $res->html->messages(); ?>

                    <div class="feed">
                        <article>
                            <h2>Feed Item</h2>
                            <div class="stars">
                                <input type="radio" id="star_ID_5" class="custom" name="star_ID" value="5" />
                                <label for="star_ID_5">Star 5</label>
                                <input type="radio" id="star_ID_4" class="custom" name="star_ID" value="4" />
                                <label for="star_ID_4"> Star 4</label>
                                <input type="radio" id="star_ID_3" class="custom" name="star_ID" value="3" />
                                <label for="star_ID_3">Star 3</label>
                                <input type="radio" id="star_ID_2" class="custom" name="star_ID" value="2" />
                                <label for="star_ID_2">Star 2</label>
                                <input type="radio" id="star_ID_1" class="custom" name="star_ID" value="1" />
                                <label for="star_ID_1">Star 1</label>
                            </div>
                            <span>(Auto Rating: 2.3)</span>
                            <div class="content">
                                <p>This is some example content from the feed item.</p>
                            </div>
                            <div class="tags">
                                <input type="checkbox" id="star_ID1_SLUG" class="custom Xprocess" name="star_ID1_SLUG" value="ID1" checked /><label for="star_ID1_SLUG">To Read</label>
                                <input type="checkbox" id="star_ID2_SLUG" class="custom" name="star_ID2_SLUG" value="ID2" checked /><label for="star_ID2_SLUG">To Reply</label>
                                <input type="checkbox" id="star_ID3_SLUG" class="custom" name="star_ID3_SLUG" value="ID3" /><label for="star_ID3_SLUG">To Listen</label>
                            </div>
                            <button>Archive</button>
                        </article>
                        <article>
                            <h2>Feed Item</h2>
                            <div class="stars">
                                <input type="radio" id="star_ID2_5" class="custom" name="star_ID2" value="5" />
                                <label for="star_ID2_5">Star 5</label>
                                <input type="radio" id="star_ID2_4" class="custom" name="star_ID2" value="4" />
                                <label for="star_ID2_4"> Star 4</label>
                                <input type="radio" id="star_ID2_3" class="custom" name="star_ID2" value="3" />
                                <label for="star_ID2_3">Star 3</label>
                                <input type="radio" id="star_ID2_2" class="custom" name="star_ID2" value="2" />
                                <label for="star_ID2_2">Star 2</label>
                                <input type="radio" id="star_ID2_1" class="custom" name="star_ID2" value="1" />
                                <label for="star_ID2_1">Star 1</label>
                            </div>
                            <span>(Auto Rating: 4.3)</span>
                            <div class="content">
                                <p>This is some example content from the feed item.</p>
                            </div>
                            <div class="tags">
                                <input type="checkbox" id="star_ID4_SLUG" class="custom" name="star_ID4_SLUG"`value="ID1" checked /><label for="star_ID4_SLUG">To Read</label>
                                <input type="checkbox" id="star_ID5_SLUG" class="custom" name="star_ID5_SLUG" value="ID2" /><label for="star_ID5_SLUG">To Reply</label>
                                <input type="checkbox" id="star_ID6_SLUG" class="custom" name="star_ID6_SLUG" value="ID3" /><label for="star_ID6_SLUG">To Listen</label>
                            </div>
                            <button>Archive</button>
                        </article>
                    </div>
                </div>
            </main>
            <?php $res->partial('design/sidebar_right'); ?>
            <?php $res->partial('design/footer'); ?>
        </div>
		<?php $res->partial('design/foot'); ?>
    </body>
</html>

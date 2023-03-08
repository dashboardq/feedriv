<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="page_feeds <?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('header'); ?>
            <?php $res->partial('sidebar_left'); ?>
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
                        <?php foreach($list as $item): ?>
                        <article>
                            <h2><?php esc($item->data['title']); ?></h2>
                            <div class="stars">
                            <input type="radio" id="star_<?php esc($item->id); ?>_5" class="custom star" name="star_<?php esc($item->id); ?>" value="5" data-id="<?php esc($item->id); ?>" <?php echo ($item->data['rating'] == 5) ? 'checked' : ''; ?> />
                                <label for="star_<?php esc($item->id); ?>_5">Star 5</label>
                                <input type="radio" id="star_<?php esc($item->id); ?>_4" class="custom star" name="star_<?php esc($item->id); ?>" value="4" data-id="<?php esc($item->id); ?>" <?php echo ($item->data['rating'] == 4) ? 'checked' : ''; ?> />
                                <label for="star_<?php esc($item->id); ?>_4"> Star 4</label>
                                <input type="radio" id="star_<?php esc($item->id); ?>_3" class="custom star" name="star_<?php esc($item->id); ?>" value="3" data-id="<?php esc($item->id); ?>" <?php echo ($item->data['rating'] == 3) ? 'checked' : ''; ?> />
                                <label for="star_<?php esc($item->id); ?>_3">Star 3</label>
                                <input type="radio" id="star_<?php esc($item->id); ?>_2" class="custom star" name="star_<?php esc($item->id); ?>" value="2" data-id="<?php esc($item->id); ?>" <?php echo ($item->data['rating'] == 2) ? 'checked' : ''; ?> />
                                <label for="star_<?php esc($item->id); ?>_2">Star 2</label>
                                <input type="radio" id="star_<?php esc($item->id); ?>_1" class="custom star" name="star_<?php esc($item->id); ?>" value="1" data-id="<?php esc($item->id); ?>" <?php echo ($item->data['rating'] == 1) ? 'checked' : ''; ?> />
                                <label for="star_<?php esc($item->id); ?>_1">Star 1</label>
                            </div>
                            <span>(Auto Rating: 2.3)</span>
                            <div class="content">
                                <?php dangerous($item->data['description']); ?>
                            </div>
                            <div class="tags">
                                <?php foreach($tags as $tag): ?>
                                <input type="checkbox" id="tag_<?php esc($item->id); ?>_<?php esc($tag->id); ?>" class="custom tag" name="tag_<?php esc($item->id); ?>_<?php esc($tag->id); ?>" value="<?php esc($tag->id); ?>" data-item-id="<?php esc($item->id); ?>" /><label for="tag_<?php esc($item->id); ?>_<?php esc($tag->id); ?>"><?php esc($tag->data['name']); ?></label>
                                <?php endforeach; ?>
                            </div>
                            <button>Archive</button>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </main>
            <?php $res->partial('sidebar_right'); ?>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>

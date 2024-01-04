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
                                ['label' => 'Highest Auto Rating First', 'value' => 'auto-desc'],
                                ['label' => 'Lowest Auto Rating First', 'value' => 'auto-asc'],
                            ], $feed_sort); ?>
                        </div>
                        <button data-aim="#refresh" data-add="show">Refresh</button>
                        <button data-aim="#archive_all" data-add="show">Archive All</button>
                    </div>
                    <?php $res->html->messages(); ?>

                    <div class="feed">
                        <?php if($pagination): ?>
                        <div class="pagination">
                        <p>Results <?php esc($pagination['current_result'] . '-' . $pagination['current_result_last'] . ' of ' . $pagination['total_results']); ?> <?php if($pagination['page_previous'] != $pagination['page_current']): ?>&lt; <a href="<?php esc($pagination['url_previous']); ?>">Prev</a><?php endif; ?> <?php if($pagination['page_next'] != $pagination['page_current']): ?><a href="<?php esc($pagination['url_next']); ?>">Next</a> &gt;<?php endif; ?></p>
                        </div>
                        <?php endif; ?>
                        <?php foreach($list as $item): ?>
                        <?php
                            $style = '';
                            if($item->data['category']['show_colors'] && $item->data['auto_rating']) {
                                $style = ' style="background: ' . _esc($item->data['color']) . ';"';
                            }
                        ?>
                        <article data-id="<?php esc($item->data['id']); ?>" <?php echo $style; ?>>
                            <h2><a href="<?php esc($item->data['link']); ?>" target="_blank"><?php esc($item->data['title']); ?></a></h2>

                            <?php if($item->data['category']['show_ratings']): ?>
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
                            <?php endif; ?>

                            <?php if($item->data['category']['show_auto_ratings']): ?>
                            <span>(Auto Rating: <?php esc($item->data['auto_rating']); ?>)</span>
                            <?php endif; ?>

                            <p><?php esc($item->data['published_tz']->format('M j, Y, g:i a')); ?></p>

                            <div class="content">
                                <?php dangerous($item->data['description']); ?>
                            </div>
                            <?php if($item->data['category']['show_tags']): ?>
                            <div class="tags">
                                <?php foreach($tags as $tag): ?>
                                <?php if(in_array($tag->id, $item->data['available_tag_ids'])): ?>
                                <input type="checkbox" id="tag_<?php esc($item->id); ?>_<?php esc($tag->id); ?>" class="custom tag" name="tag_<?php esc($item->id); ?>_<?php esc($tag->id); ?>" value="<?php esc($tag->id); ?>" data-item-id="<?php esc($item->id); ?>" <?php echo in_array($tag->id, $item->data['checked_tag_ids']) ? 'checked' : ''; ?> /><label for="tag_<?php esc($item->id); ?>_<?php esc($tag->id); ?>"><?php esc($tag->data['name']); ?></label>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <button class="archive">Archive</button>
                        </article>
                        <?php endforeach; ?>

                        <?php if($pagination && $pagination['total_results'] > 0): ?>
                        <div class="pagination">
                        <p>Results <?php esc($pagination['current_result'] . '-' . $pagination['current_result_last'] . ' of ' . $pagination['total_results']); ?> <?php if($pagination['page_previous'] != $pagination['page_current']): ?>&lt; <a href="<?php esc($pagination['url_previous']); ?>">Prev</a><?php endif; ?> <?php if($pagination['page_next'] != $pagination['page_current']): ?><a href="<?php esc($pagination['url_next']); ?>">Next</a> &gt;<?php endif; ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
            <?php $res->partial('sidebar_right'); ?>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>

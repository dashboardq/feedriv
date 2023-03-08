
            <div id="sidebar_left" class="sidebars">
                <div class="feeds">
                    <h3>Categories <a href="/categories">Edit</a></h3>
                    <p><a href="/feed/add" class="button">Add Feed</a></p>

                    <ul>
                        <?php foreach($categories as $category): ?>
                        <li>
                            <a href="<?php url($category['link']); ?>" class="<?php esc($category['class']); ?>"><?php esc($category['label']); ?></a> <span>(<?php esc($category['count']); ?>)</span>
                            <?php if($category['id']): ?>
                            <input id="toggle_arrow_<?php esc($category['id']); ?>" class="custom" type="checkbox" <?php echo ($category['opened']) ? 'checked' : ''; ?> data-toggle="<?php esc($category['id']); ?>" />
                            <label class="toggle_arrow" for="toggle_arrow_<?php esc($category['id']); ?>" aria-label="Toggle Subitems">▼</label>
                            <ul>
                                <?php foreach($category['feeds'] as $feed): ?>
                                <li>
                                    <a href="/feeds/feed/<?php esc($feed->id); ?>" class="<?php esc(('/feeds/feed/' . $feed->id == $feed_link) ? 'active' : ''); ?>"><?php esc($feed->data['title']); ?></a> <span>(<?php esc($feed->total()); ?>)</span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

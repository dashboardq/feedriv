
            <div id="sidebar_right" class="sidebars">
                <div class="tags">
                    <h3>Tags</h3>
                    <ul>
                        <?php foreach($tags as $item): ?>
                        <li>
                            <a href="<?php url($item['link']); ?>" class="<?php esc($item['class']); ?>"><?php esc($item['label']); ?></a> <span>(<?php esc($item['count']); ?>)</span>
                            <?php if($item['active']): ?>
                            <a href="/feeds/clear" class="active">&times;</a>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="ratings">
                    <h3>Manual Ratings</h3>
                    <ul>
                        <?php foreach($ratings as $item): ?>
                        <li>
                            <a href="<?php url($item['link']); ?>" class="<?php esc($item['class']); ?>"><?php esc($item['label']); ?></a> <span>(<?php esc($item['count']); ?>)</span>
                            <?php if($item['active']): ?>
                            <a href="/feeds/clear" class="active">&times;</a>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="ratings">
                    <h3>Auto Ratings</h3>
                    <ul>
                        <?php foreach($auto_ratings as $item): ?>
                        <li>
                            <a href="<?php url($item['link']); ?>" class="<?php esc($item['class']); ?>"><?php esc($item['label']); ?></a> <span>(<?php esc($item['count']); ?>)</span>
                            <?php if($item['active']): ?>
                            <a href="/feeds/clear" class="active">&times;</a>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="archive">
                    <h3>Archived Items</h3>
                    <ul>
                        <li><a href="/feeds/archive" class="<?php esc(('/feeds/archive' == $feed_link) ? 'active' : ''); ?>">Archived Items</a>  <span>(<?php esc($archive['count']); ?>)</span></li>
                    </ul>
                </div>
            </div>

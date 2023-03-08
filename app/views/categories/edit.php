<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('header'); ?>
            <main>
                <div class="page">
                    <h1><?php esc($title); ?></h1>
                    <p><a href="/categories">&lt; Back</a></p>

                    <?php $res->html->messages(); ?>
                    <form method="POST">
                        <?php $res->html->text('Name', 'name', 'General'); ?>

                        <div class="field">
                            <?php $res->html->checkbox('Show Tags'); ?>
                            <?php $res->html->checkbox('Show Ratings'); ?>
                            <?php $res->html->checkbox('Show Colors'); ?>
                            <?php $res->html->checkbox('Save Rating Scores For Training Auto Rating', 'save_ratings'); ?>
                        </div>

                        <?php $res->html->submit('Save'); ?>
                    </form>

                    <h2>Feeds</h2>
                    <p><a href="/feed/add/<?php esc($category->id); ?>" class="button">Add Feed</a></p>
                    <table class="draggable" data-action="/ajax/feed-sort/<?php esc($category->id); ?>">
                        <thead>
                            <tr>
                                <th>Sort</th>
                                <th>Name</th>
                                <th>URL</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($feeds as $feed): ?>
                            <tr data-id="<?php esc($feed->id); ?>">
                                <td data-label="Sort">
                                    <button class="sort_order sort_up">&#x25b2;</button>
                                    <button class="sort_order sort_down">&#x25bc;</button>
                                </td>
                                <td data-label="Name"><?php esc($feed->data['title']); ?></td>
                                <td data-label="URL"><?php esc($feed->data['original_url']); ?></td>
                                <td data-label="Action">
                                    <a href="/feed/edit/<?php esc($feed->id); ?>" class="button">Edit</a>
                                    <?php $res->html->delete('/feed/delete/' . $feed->id, 'Delete'); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(count($feeds) == 0): ?>
                            <tr>
                                <td data-label="Status" colspan="4">No feeds at this time.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <h2>Tags</h2>
                    <p><a href="/tag/add/<?php esc($category->id); ?>" class="button">Add Tag</a></p>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($tags as $tag): ?>
                            <tr>
                                <td data-label="Name"><?php esc($tag->data['name']); ?></td>
                                <td data-label="Action">
                                    <a href="/tag/edit/<?php esc($tag->id); ?>" class="button">Edit</a>
                                    <?php $res->html->delete('/tag/delete/' . $tag->id, 'Delete'); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(count($tags) == 0): ?>
                            <tr>
                                <td data-label="Status" colspan="3">No tags at this time.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <h2>Auto Rating Colors</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Score</th>
                                <th>Color</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($colors as $color): ?>
                            <tr>
                                <td data-label="Score"><?php esc($color->data['range']); ?></td>
                                <td data-label="Color"><span style="background: <?php esc($color->data['color']); ?>;"><?php esc($color->data['color']); ?></span></td>
                                <td data-label="Action">
                                    <a href="/color/edit/<?php esc($color->id); ?>" class="button">Edit</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <h2>Ratings</h2>
                    <p><a href="/auto-rating/add/<?php esc($category->id); ?>" class="button">Add Word</a></p>
                    <table>
                        <thead>
                            <tr>
                                <th>Word</th>
                                <th>Score</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($ratings as $rating): ?>
                            <tr>
                                <td data-label="Word"><?php esc($rating->data['word']); ?></td>
                                <td data-label="Score"><?php esc($rating->data['score']); ?></td>
                                <td data-label="Action">
                                    <a href="/auto-rating/edit/<?php esc($rating->id); ?>" class="button">Edit</a>
                                    <?php $res->html->delete('/auto-rating/delete/' . $rating->id, 'Delete'); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(count($ratings) == 0): ?>
                            <tr>
                                <td data-label="Status" colspan="3">No ratings at this time.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
        <script src="/mavoc/js/drag-tr.js"></script>
    </body>
</html>

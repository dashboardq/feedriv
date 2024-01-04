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
                    <p><a href="/feeds">&lt; Back</a></p>

                    <p><a href="/category/add" class="button">Add Category</a></p>
                    <?php $res->html->messages(); ?>
                    <table class="draggable" data-action="/ajax/category-sort">
                        <thead>
                            <tr>
                                <th>Sort</td>
                                <th>Name</td>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($list as $item): ?>
                            <tr data-id="<?php esc($item->id); ?>">
                                <td data-label="Sort">
                                    <button class="sort_order sort_up">&#x25b2;</button>
                                    <button class="sort_order sort_down">&#x25bc;</button>
                                </td>
                                <td data-label="Name"><?php esc($item->data['name']); ?></td>
                                <td data-label="Actions">
                                    <a href="/category/edit/<?php esc($item->id); ?>" class="button">Edit</a>
                                    <?php $res->html->delete('/category/delete/' . $item->id, 'Delete'); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(count($list) == 0): ?>
                            <tr>
                                <td data-label="Status" colspan="3">There are currently no categories. </td>
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

<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('design/head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('design/header'); ?>
            <main>
                <div class="page">
                    <h1><?php esc($title); ?></h1>
                    <p><a href="/design/feed">&lt; Back</a></p>

                    <p><a href="/design/category-add" class="button">Add Category</a></p>
                    <table>
                        <thead>
                            <tr>
                                <th>Sort</td>
                                <th>Name</td>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="Sort">
                                    <button class="sort_order sort_up">&#x25b2;</button>
                                    <button class="sort_order sort_down">&#x25bc;</button>
                                </td>
                                <td data-label="Name">General</td>
                                <td data-label="Actions">
                                    <a href="/design/category-edit" class="button">Edit</a>
                                    <?php $res->html->delete('/design/delete', 'Delete'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td data-label="Sort">
                                    <button class="sort_order sort_up">&#x25b2;</button>
                                    <button class="sort_order sort_down">&#x25bc;</button>
                                </td>
                                <td data-label="Name">Jobs</td>
                                <td data-label="Actions">
                                    <a href="/design/category-edit" class="button">Edit</a>
                                    <?php $res->html->delete('/design/delete', 'Delete'); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
            <?php $res->partial('design/footer'); ?>
        </div>
		<?php $res->partial('design/foot'); ?>
    </body>
</html>

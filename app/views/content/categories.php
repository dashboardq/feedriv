<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('content/head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div id="app">
            <?php $res->partial('content/header'); ?>
            <main>
                <div class="page">
                    <h1><?php esc($title); ?></h1>
                    <p><a href="/content/feed">&lt; Back</a></p>

                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th><a href="/content/category-add" class="button">Add Category</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="sorter"><span class="sort_up">&#x25b2;</span><span class="sort_down">&#x25bc;</span></span></td>
                                <td>General</td>
                                <td>
                                    <a href="/content/category-edit" class="button">Edit</a>
                                    <?php $res->html->delete('/content/delete', 'Delete'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="sorter"><span class="sort_up">&#x25b2;</span><span class="sort_down">&#x25bc;</span></span></td>
                                <td>Jobs</td>
                                <td>
                                    <a href="/content/category-edit" class="button">Edit</a>
                                    <?php $res->html->delete('/content/delete', 'Delete'); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
            <?php $res->partial('content/footer'); ?>
        </div>
		<?php $res->partial('content/foot'); ?>
    </body>
</html>

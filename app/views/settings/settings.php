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

                    <h2>Default Settings For New Category</h2>
                    <?php $res->html->messages(); ?>
                    <form method="POST">
                        <div class="field">
                            <?php $res->html->checkbox('Show Tags'); ?>
                            <?php $res->html->checkbox('Show Ratings'); ?>
                            <?php $res->html->checkbox('Show Colors'); ?>
                            <?php $res->html->checkbox('Save Rating Scores For Training Auto Rating', 'save_ratings'); ?>
                        </div>

                        <?php $res->html->select('Timezone', 'timezone', $timezones); ?>

                        <?php $res->html->submit('Save'); ?>
                    </form>

                    <h2>Default Tags</h2>
                    <p><a href="/default-tag/add" class="button">Add Tag</a></p>
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
                                    <a href="/default-tag/edit/<?php esc($tag->id); ?>" class="button">Edit</a>
                                    <?php $res->html->delete('/default-tag/delete/' . $tag->id, 'Delete'); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <h2>Default Auto Rating Colors</h2>
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
                                    <a href="/default-color/edit/<?php esc($color->id); ?>" class="button">Edit</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>

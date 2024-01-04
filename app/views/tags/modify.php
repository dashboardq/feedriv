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
                    <p><a href="/category/edit/<?php esc($req->params['category_id']); ?>">&lt; Back</a></p>

                    <?php $res->html->messages(); ?>
                    <form method="POST">
                        <table>
                            <thead>
                                <tr>
                                    <th>Active</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($tags as $tag): ?>
                                <tr>
                                    <td data-label="Default">
                                        <?php $res->html->checkboxRaw('ids[]', $tag->id, $tag->data['active'] > 0); ?>
                                    </td>
                                    <td data-label="Name"><?php esc($tag->data['name']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(count($tags) == 0): ?>
                                <tr>
                                    <td data-label="Status" colspan="2">No tags at this time.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php $res->html->submit('Update'); ?>
                    </form>

                </div>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>

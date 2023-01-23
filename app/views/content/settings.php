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

                    <h2>Default Settings For New Category</h2>
                    <form>
                        <?php $res->html->checkbox('Show Tags'); ?>
                        <?php $res->html->checkbox('Show Ratings'); ?>
                        <?php $res->html->checkbox('Show Colors'); ?>
                        <?php $res->html->checkbox('Save Rating Scores For Training Auto Rating'); ?>
                        <?php $res->html->select('Default Sort Order', [
                            ['label' => 'Date Asc', 'value' => 'date-asc'],
                            ['label' => 'Date Desc', 'value' => 'date-desc'],
                            ['label' => 'Auto Rate Asc', 'value' => 'date-asc'],
                            ['label' => 'Auto Rate Desc', 'value' => 'date-desc'],
                        ]); ?>
                        <?php $res->html->submit('Save'); ?>
                    </form>

                    <h2>Default Tags</h2>
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th><a href="/content/default-tag-add" class="button">Add Tag</a></th>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Read</td>
                                <td>
                                    <a href="/content/default-tag-edit" class="button">Edit</a>
                                    <?php $res->html->delete('/content/delete', 'Delete'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Listen</td>
                                <td>
                                    <a href="/content/default-tag-edit" class="button">Edit</a>
                                    <?php $res->html->delete('/content/delete', 'Delete'); ?>
                                </td>
                            </tr>
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
                            <tr>
                                <td>1-2</td>
                                <td><span style="background: #ff000077;">Red</span></td>
                                <td>
                                    <a href="/content/default-color-edit" class="button">Edit</a>
                                </td>
                            </tr>
                            <tr>
                                <td>2-3</td>
                                <td><span style="background: #ffff0077;">Yellow</span></td>
                                <td>
                                    <a href="/content/default-color-edit" class="button">Edit</a>
                                </td>
                            </tr>
                            <tr>
                                <td>3-4</td>
                                <td><span style="background: #ffff0077;">Yellow</span></td>
                                <td>
                                    <a href="/content/default-color-edit" class="button">Edit</a>
                                </td>
                            </tr>
                            <tr>
                                <td>4-5</td>
                                <td><span style="background: #00ff0077;">Green</span></td>
                                <td>
                                    <a href="/content/default-color-edit" class="button">Edit</a>
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

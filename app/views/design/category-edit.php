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
                    <p><a href="/design/categories">&lt; Back</a></p>

                    <form>
                        <?php $res->html->text('Name', 'name', 'General'); ?>

                        <div class="field">
                            <?php $res->html->checkbox('Show Tags'); ?>
                            <?php $res->html->checkbox('Show Ratings'); ?>
                            <?php $res->html->checkbox('Show Colors'); ?>
                            <?php $res->html->checkbox('Save Rating Scores For Training Auto Rating'); ?>
                        </div>

                        <?php $res->html->select('Default Sort Order', [
                            ['label' => 'Date Asc', 'value' => 'date-asc'],
                            ['label' => 'Date Desc', 'value' => 'date-desc'],
                            ['label' => 'Auto Rate Asc', 'value' => 'date-asc'],
                            ['label' => 'Auto Rate Desc', 'value' => 'date-desc'],
                        ]); ?>
                        <?php $res->html->submit('Save'); ?>
                    </form>

                    <h2>Feeds</h2>
                    <p><a href="/design/feed-add" class="button">Add Feed</a></p>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>URL</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="Name">Example Blog</td>
                                <td data-label="URL"><a href="https://example.com">https://example.com</a></td>
                                <td data-label="Action">
                                    <a href="/design/feed-edit" class="button">Edit</a>
                                    <?php $res->html->delete('/design/delete', 'Delete'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td data-label="Name">News Blog</td>
                                <td data-label="URL"><a href="https://example.net">https://example.net</a></td>
                                <td data-label="Action">
                                    <a href="/design/feed-edit" class="button">Edit</a>
                                    <?php $res->html->delete('/design/delete', 'Delete'); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <h2>Tags</h2>
                    <p><a href="/design/tag-add" class="button">Add Tag</a></p>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="Name">Read</td>
                                <td data-label="Action">
                                    <a href="/design/tag-edit" class="button">Edit</a>
                                    <?php $res->html->delete('/design/delete', 'Delete'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td data-label="Name">Listen</td>
                                <td data-label="Action">
                                    <a href="/design/tag-edit" class="button">Edit</a>
                                    <?php $res->html->delete('/design/delete', 'Delete'); ?>
                                </td>
                            </tr>
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
                            <tr>
                                <td data-label="Score">1-2</td>
                                <td data-label="Color"><span style="background: #ff000077;">Red</span></td>
                                <td data-label="Action">
                                    <a href="/design/color-edit" class="button">Edit</a>
                                </td>
                            </tr>
                            <tr>
                                <td data-label="Score">2-3</td>
                                <td data-label="Color"><span style="background: #ffff0077;">Yellow</span></td>
                                <td data-label="Action">
                                    <a href="/design/color-edit" class="button">Edit</a>
                                </td>
                            </tr>
                            <tr>
                                <td data-label="Score">3-4</td>
                                <td data-label="Color"><span style="background: #ffff0077;">Yellow</span></td>
                                <td data-label="Action">
                                    <a href="/design/color-edit" class="button">Edit</a>
                                </td>
                            </tr>
                            <tr>
                                <td data-label="Score">4-5</td>
                                <td data-label="Color"><span style="background: #00ff0077;">Green</span></td>
                                <td data-label="Action">
                                    <a href="/design/color-edit" class="button">Edit</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <h2>Ratings</h2>
                    <p><a href="/design/word-add" class="button">Add Word</a></p>
                    <table>
                        <thead>
                            <tr>
                                <th>Word</th>
                                <th>Score</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="Word">javascript</td>
                                <td data-label="Score">5</td>
                                <td data-label="Action">
                                    <a href="/design/word-edit" class="button">Edit</a>
                                    <?php $res->html->delete('/design/delete', 'Delete'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td data-label="Word">html</td>
                                <td data-label="Score">4</td>
                                <td data-label="Action">
                                    <a href="/design/word-edit" class="button">Edit</a>
                                    <?php $res->html->delete('/design/delete', 'Delete'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td data-label="Word">css</td>
                                <td data-label="Score">5</td>
                                <td data-label="Action">
                                    <a href="/design/word-edit" class="button">Edit</a>
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

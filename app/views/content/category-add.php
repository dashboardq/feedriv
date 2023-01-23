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
                    <p><a href="/content/categories">&lt; Back</a></p>

                    <form>
                        <?php $res->html->text('Name', 'name', 'General'); ?>
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
                </div>
            </main>
            <?php $res->partial('content/footer'); ?>
        </div>
		<?php $res->partial('content/foot'); ?>
    </body>
</html>

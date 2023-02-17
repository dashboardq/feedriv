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
                </div>
            </main>
            <?php $res->partial('design/footer'); ?>
        </div>
		<?php $res->partial('design/foot'); ?>
    </body>
</html>

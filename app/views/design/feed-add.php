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
                        <?php $res->html->select('Category', [
                            ['label' => 'Please select...', 'value' => ''],
                            ['label' => 'General', 'value' => '1'],
                            ['label' => 'Jobs', 'value' => '2'],
                        ]); ?>

                        <p><?php $res->html->a('/design/category-add', 'Add Category'); ?></p>


                        <?php $res->html->text('Feed URL / @Twitter / @Mastadon@Account', 'feed', 'Feed'); ?>
                        <?php $res->html->submit('Add'); ?>
                    </form>
                </div>
            </main>
            <?php $res->partial('design/footer'); ?>
        </div>
		<?php $res->partial('design/foot'); ?>
    </body>
</html>

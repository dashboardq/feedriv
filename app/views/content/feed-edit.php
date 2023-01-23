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
                    <p><a href="/content/category-edit">&lt; Back</a></p>

                    <form>
                        <?php $res->html->select('Category', [
                            ['label' => 'Please select...', 'value' => ''],
                            ['label' => 'General', 'value' => '1'],
                            ['label' => 'Jobs', 'value' => '2'],
                        ]); ?>
                        <?php $res->html->a('/content/category-add', 'Add Category'); ?>


                        <?php $res->html->text('Feed URL / @Twitter / @Mastadon@Account', 'feed', 'Feed'); ?>
                        <?php $res->html->submit('Update'); ?>
                    </form>
                </div>
            </main>
            <?php $res->partial('content/footer'); ?>
        </div>
		<?php $res->partial('content/foot'); ?>
    </body>
</html>

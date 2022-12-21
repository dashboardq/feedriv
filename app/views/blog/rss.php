<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>                  
        <title>FeedRiv Blog</title>
        <link><?php uri('blog'); ?></link>
        <description></description>
        <language>en-us</language>
        <atom:link href="<?php uri('rss'); ?>" rel="self" type="application/rss+xml" />
    
        <?php foreach($items as $item): ?>
        <item>                 
            <title><?php esc($item['title']); ?></title>
            <link><?php esc($item['permalink']); ?></link>
            <guid><?php esc($item['permalink']); ?></guid>
            <pubDate><?php esc($item['published_at']->format('r')); ?></pubDate> 
            <description><![CDATA[<?php echo $item['content']; ?>]]></description>
        </item>                
        <?php endforeach; ?>   
    
    </channel>                 
</rss>

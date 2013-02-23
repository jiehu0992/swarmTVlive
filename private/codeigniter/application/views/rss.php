<?php  echo '<?xml version="1.0" encoding="' . $encoding . '"?>' . "\n"; ?>  
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:content="http://purl.org/rss/1.0/modules/content/">  
    <channel>  
        <link><?php echo $feed_url; ?>  
        <description><?php echo $page_description; ?></description>  
        <dc:language><?php echo $page_language; ?></dc:language>  
        <dc:creator><?php echo $creator_email; ?></dc:creator>  
        <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>  
        <admin:generatoragent rdf:resource="http://www.codeigniter.com/">  
            <?php foreach($updates->result() as $update): ?>
            <item>  
                <title><?php echo $update->page ?></title>   
                <pubdate><?php echo $update->pubDate; ?></pubdate> 
                <summary><?php echo $update->summary ?></summary>
                <elementInHtml><?php echo $update->elementInHtml ?></elementInHtml>
                <description><[CDATA[ <?php echo character_limiter($update->jsonArray, 200); ?> ]]></description>  
            </item>  
            <?php endforeach; ?>  
        </admin:generatoragent>
    </channel>  
</rss>  
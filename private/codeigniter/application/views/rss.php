<?php  echo '<?xml version="1.0" encoding="' . $encoding . '"?>' . "\n"; ?>  
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom">  
     <channel>  
	  <title>Swarm TV Recent Changes</title> 
	  <link><?php echo $feed_url; ?></link>
	  <description><?php echo $page_description; ?></description>  
	  <dc:language><?php echo $page_language; ?></dc:language>  
	  <dc:creator><?php echo $creator_email; ?></dc:creator>
	  <atom:link href="http://ucfmediacentre.co.uk/swarmtv/index.php/feed" rel="self" type="application/rss+xml" />  
	  <?php foreach($updates->result() as $update): ?>  
	  <item>  
	       <title><?php echo ucfirst($update->summary) . ' on page: '. $update->page ?></title>
	       <guid><?php echo 'Update_' . $update->id ?></guid>
	       <link><?php echo 'http://ucfmediacentre.co.uk/swarmtv/index.php/pages/view/' . $update->page ?></link>   
	       <pubDate><?php echo date('r', strtotime($update->pubDate)); ?></pubDate>
	       <description><![CDATA[ <?php echo $update->jsonArray; ?> ]]></description> 
	  </item>  
	  <?php endforeach; ?>
     </channel>  
</rss>  
<?php
 
// Make sure SimplePie is included. You may need to change this to match the location of autoloader.php
// For 1.0-1.2:
#require_once('../simplepie.inc');
// For 1.3+:
require_once('php/autoloader.php');
 
// We'll process this feed with all of the default options.
$feed = new SimplePie();
 
// Set which feed to process.
$feed->set_feed_url('http://ucfmediacentre.co.uk/swarmtv/index.php/feed/');

// Remove these tags from the list
$strip_htmltags = $feed->strip_htmltags;
array_splice($strip_htmltags, array_search('object', $strip_htmltags), 1);
array_splice($strip_htmltags, array_search('param', $strip_htmltags), 1);
array_splice($strip_htmltags, array_search('embed', $strip_htmltags), 1);
array_splice($strip_htmltags, array_search('video', $strip_htmltags), 1);
array_splice($strip_htmltags, array_search('audio', $strip_htmltags), 1);
 
$feed->strip_htmltags($strip_htmltags);
 
// Run SimplePie.
$feed->init();
 
// This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
$feed->handle_content_type();
 
// Let's begin our XHTML webpage code.  The DOCTYPE is supposed to be the very first thing, so we'll keep it on the same line as the closing-PHP tag.
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
        "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Sample SimplePie Page</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
</head>
<body>
 
	<div class="header">
		<h1><a href="<?php echo $feed->get_permalink(); ?>"><?php echo $feed->get_title(); ?></a></h1>
		<p><?php echo $feed->get_description(); ?></p>
	</div>
 
	<?php
	//echo '<pre>'.var_dump($feed).'</pre><br /><br /><br />';
	//echo var_dump(get_object_vars($feed["http://purl.org/rss/1.0/modules/content/"]["encoded"][0]["data"]));
	//print_r(array_values(get_content($feed)));
	
	//Here, we'll loop through all of the items in the feed, and $item represents the current item in the loop.
	
	foreach ($feed->get_items() as $item):
	?>
 
		<div class="item">
			<h2><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h2>
			<p><?php echo $item->get_description(); ?></p>
			<p><?php echo $item->get_content(); ?></p>
			<p><small>Posted on <?php echo $item->get_date('j F Y | g:i a'); ?></small></p>
		</div>
 
	<?php endforeach; ?>
 
</body>
</html>
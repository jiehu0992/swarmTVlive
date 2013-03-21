<?php
	$changesList = '';
	foreach($res_feed as $item):
		//iterate through all the items found in the rss feed
		$changesList = $changesList . "<a href=" . $item->get_link() . "><H2>" . $item->get_title() . "</H2></a><br />";
		$changesList = $changesList . "Changed: " . $item->get_date() . "<br /><br />";
		$changesList = $changesList . $item->get_content() . "<br /><br />";
		$changesList = $changesList . "Attributes: " . $item->get_description() . "<br />";
		$changesList = $changesList . "<hr />";	
	endforeach; 
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>swarm tv: <?php if (isset($page_info->title)) echo $page_info->title; ?> </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="<?php echo base_url(); ?>css/normalize.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>libraries/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>libraries/fineuploader.jquery-3.0/fineuploader.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>libraries/jquery-ui-1.9.2.custom/css/eggplant/jquery-ui-1.9.2.custom.min.css" type="text/css" media="screen" />
        
        <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->
        <script src="<?php echo base_url(); ?>js/vendor/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>libraries/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>
		<script src="<?php echo base_url(); ?>js/vendor/jquery.ui.touch-punch.min.js"></script>
        <script src="<?php echo base_url(); ?>js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
    <div>
        <h1 id="page_title">Recent Changes</h1>
		<?php echo $changesList; ?>
	</div>
    <a id="main_home_button" href="<?php echo base_url(); ?>index.php/swarmtv">&nbsp;</a>
</div>

        <script src="<?php echo base_url(); ?>js/plugins.js"></script>
        <script src="<?php echo base_url(); ?>js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>

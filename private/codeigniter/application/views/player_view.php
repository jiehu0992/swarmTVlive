<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Video Player</title>
<style type="text/css">
body,td,th {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #666;
}</style>
<script type="application/javascript" src="js/vendor/jquery-1.8.3.min.js"></script>
<script type="application/javascript" src="js/popcorn.js"></script>
<script type="application/javascript" src="js/videoExtension.js"></script>
<script type="text/javascript">

$(document).ready(function() {
	/*sDuration = <?php echo $out ?>;
	//alert(sDuration);
	sHours = Math.floor(sDuration/3600);
	if (sHours<10) {sDurationString="0"+sHours;}
	sMins = Math.floor((sDuration-(sHours*3600))/60);
	sDurationString=sDurationString+":";
	if (sMins<10) {sDurationString=sDurationString+"0"+sMins;} else {sDurationString=sDurationString+sMins;}
	sSecs = sDuration - ((sHours*3600)+sMins*60);
	sDurationString=sDurationString+":";
	if (sSecs<10) {sDurationString=sDurationString+"0"+sSecs;} else {sDurationString=sDurationString+sSecs;}*/
	//alert(sDurationString);
	$("#clipLabel").html('(<?php echo $filename ?>'+videoExtension+')');/*+sDurationString);*/
});

function setIn(videoID, videoIn){
	var In = myClip.currentTime()
	$('#videoInField').val(In);
	$.ajax({
		url: "updateClip.php?id="+videoID+"&trim=in&currentTime="+In,
		success:function(data){
		}
	});
}

function setOut(videoID, videoOut){
	var Out = myClip.currentTime()
	$('#videoOutField').val(Out);
	$.ajax({
		url: "updateClip.php?id="+videoID+"&trim=out&currentTime="+Out,
		success:function(data){
		}
	});
}

function updateIn(videoID, videoIn){
	//var In = myClip.currentTime()
	$('#videoInField').val(videoIn);
	$.ajax({
		url: "updateClip.php?id="+videoID+"&trim=in&currentTime="+videoIn,
		success:function(data){
		}
	});
}

function updateOut(videoID, videoOut){
	//var Out = myClip.currentTime()
	$('#videoOutField').val(videoOut);
	$.ajax({
		url: "updateClip.php?id="+videoID+"&trim=out&currentTime="+videoOut,
		success:function(data){
		}
	});
}


function setText(videoID, altText){
	//alert("setText:"+"updateClip.php?id="+videoID+"&altText="+altText);
	$.ajax({
		url: "updateClip.php?id="+videoID+"&altText="+altText,
		success: function(data) {
			//alert(data);
			//alert('Load was performed.');
		}
	});
}

</script>
</head>

<body>

<table cellpadding="2">
	<tr>
		<td colspan="3">
			<video height="<?php echo $height ?>" width="<?php echo $width ?>" id="clipToShow" controls>
				<source src="movies/<?php echo $filename ?>.mp4">
				<source src="movies/<?php echo $filename ?>.ogv">
			</video>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			Clip Description: <input type="text" name="videoCaption" id="videoCaption" value='<?php echo $altText ?>' />
			<button type="button" onClick="setText('<?php echo $id ?>', document.getElementsByName('videoCaption')[0].value);">Update</button>
		</td>
	</tr>
	<tr>
		<td align="left" valign="bottom">
			<input type="text" name="videoInField" id="videoInField" value='<?php echo $in ?>' size="10"  onChange="updateIn('<?php echo $id ?>', this.value);" />
			<button type="button" onClick="setIn('<?php echo $id ?>', '<?php echo $in ?>');">Set In</button>
		</td>
		<td><span id="clipLabel"></span></td>
		<td align="right"  valign="bottom">
			<input type="text" name="videoOutField"  id="videoOutField" value='<?php echo $out ?>' size="10"   onChange="updateOut('<?php echo $id ?>', this.value);"/>
			<button type="button" onClick="setOut('<?php echo $id ?>', '<?php echo $out ?>');">Set Out</button>
		</td>
	</tr>
</table>
    
<script type="text/javascript">

//create popcorn object
var myClip = Popcorn("#clipToShow");

myClip.mute();

if (<?php echo round($in) ?>!=0){
	myClip.exec( 0.001, function() {
		this.play( <?php echo round($in) ?> );
		myClip.unmute();
	});
} else {
	myClip.unmute();
}

myClip.exec( <?php echo round($out) ?>, function() {
    this.pause();
});

// play the video
myClip.play();

</script>
</body>
</html>
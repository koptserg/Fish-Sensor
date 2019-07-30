<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache">
<meta name="viewport" content="width=device-width">
<meta http-equiv="Refresh" content="0.5" />
  <style>
   .colortext {
    background-color: #FF0000; /* Цвет фона */
    color: #930; /* Цвет текста */
   }
	input[type=submit] { 
	-webkit-appearance: none; 
	margin: 0px; 
	width: 150px;
	height: 50px; 
	}
  </style>
</head>
<body style="background-color: white">
<INPUT type="text" ID='cl3' size=15 value="" name="vd">
	<SCRIPT>
	function clock3(){
	var d=new Date()
	return d.toLocaleDateString()+" "+d.toLocaleTimeString()
	}
	setInterval("document.all.cl3.value=clock3()",500) // ?????????? ????????? ?????
	</SCRIPT>
	<form action="setting.php" method="post">
		<p><input type="submit" value="Setting" /></p>
	</form>
<?php
$CONF_FILE = '/www/mpu6050';
$d = date("H:i:s");
$s = shell_exec('cat '.$CONF_FILE.' | grep s= | sed -e "s/s=//"');
$ntail = intval(shell_exec('cat '.$CONF_FILE.' | grep ntail= | sed -e "s/ntail=//"'));
$ndelta = intval(shell_exec('cat '.$CONF_FILE.' | grep ndelta= | sed -e "s/ndelta=//"'));
$cdelta = intval(shell_exec('cat '.$CONF_FILE.' | grep cdelta= | sed -e "s/cdelta=//"'));
$si = intval(shell_exec('cat '.$CONF_FILE.' | grep sound= | sed -e "s/sound=//"'));
$soundf[0] = 'tada.ogg';
$soundf[1] = 'kasp.ogg';
$sound = $soundf[$si];
$volume = intval(shell_exec('cat '.$CONF_FILE.' | grep volume= | sed -e "s/volume=//"'));
$volumen = $volume/10;

$file = "/www/mess.log";
$mtime = filemtime($file);
while (true) {
  clearstatcache();
  if ($mtime < filemtime($file)) {
    $audio = "<audio id='myaudio' autoplay='autoplay'><source src='".$sound."' type='audio/ogg' /></audio><script>var audio = document.getElementById('myaudio'); audio.volume = ".$volumen.";</script>";
    echo $audio;
    echo "<p type='text' class='colortext'>sens:".$cdelta." range:".$ntail." ndelta:".$ndelta." ".$sound.":".$volume."</p>";
    break;
  } else {
    $nn=$nn+1;
    if ($nn > 10) {
    echo "<p type='text'>sens:".$cdelta." range:".$ntail." ndelta:".$ndelta." ".$sound.":".$volume."</p>";
    break;
    }
  }

  usleep(200000); // 0.2 сек
}
$output = shell_exec('tail -10 /www/mess.log');
    $outp = explode("
", $output);

$i = 9;
do {
    echo ' | '.$outp[$i].'<br />';
    $i = $i-1;
} while ($i >= 0);

?>
</body>
</html>
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Sensor</title>
  <meta name="viewport" content="width=device-width">
  <meta http-equiv="Cache-Control" content="no-cache">
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="icon" sizes="192x192" href="/icon.png">
  <link rel="stylesheet" media="only screen and (max-device-width: 854px)" href="/luci-static/bootstrap/mobile.css" type="text/css" />
  <script src="/luci-static/resources/xhr.js"></script>
  <title>mpu6050-setting</title>
  <style> 
	/* Контейнер */ 
	input[type=range] { 
	-webkit-appearance: none; 
	margin: 0px; 
	width: 60%; }
	input[type=submit] { 
	-webkit-appearance: none; 
	margin: 0px; 
	width: 100px;
	height: 50px; }
	/* Полоса в Хроме */ 
	input[type=range]::-webkit-slider-runnable-track {
	height: 8px; cursor: pointer; 
	animate: 0.2s; border: 1px solid #575656; 
	background: #AD020D; }
	/* Полоса в Мозиле */ 
	input[type=range]::-moz-range-track { 
	height: 3px; 
	cursor: pointer; 
	animate: 0.2s; 
	box-shadow: 1px 1px 1px #000; 
	background: #AD020D; 
	border: 1px solid #575656; 
	}
	/* Бегунок в Хроме */ 
	input[type=range]::-webkit-slider-thumb { 
	box-shadow: 1px 1px 1px #000; 
	border: 1px solid #000; 
	height: 35px; width: 15px; 
	border-radius: 40%/60%; 
	background: #ffffff; cursor: pointer; 
	-webkit-appearance: none; 
	margin-top: -14px; } 
	/* Бегунок в Мозиле */ 
	input[type=range]::-moz-range-thumb { 
	box-shadow: 1px 1px 1px #000; 
	border: 1px solid #000000; 
	height: 36px; width: 16px; 
	border-radius: 40%/60%; 
	background: #ffffff; 
	cursor: pointer; 
	} 
  </style> 
 </head>
 <body>
<?php

$CONF_FILE = '/www/mpu6050';
$dt = date("Y-m-d H:i:s");
$soundf[0] = 'tada.ogg';
$soundf[1] = 'kasp.ogg';

if (isset($_POST['vd']))
{
$dt = date("Y-m-d H:i:s", strtotime(htmlspecialchars($_POST['vd'])));
shell_exec('date -s "'.$dt.'"');
}
if (isset($_POST['sens']))
{
$cdelta = htmlspecialchars($_POST['sens']);
$t = trim(shell_exec('cat '.$CONF_FILE.' | grep cdelta='));
$e = shell_exec('sed -e "s/'.$t.'/cdelta='.$cdelta.'/" '.$CONF_FILE.' > /www/1; cp /www/1 '.$CONF_FILE.' ');
} 
if (isset($_POST['tail']))
{
$ntail = htmlspecialchars($_POST['tail']);
$ndelta = $ntail-1;
$t = trim(shell_exec('cat '.$CONF_FILE.' | grep ntail='));
$d = trim(shell_exec('cat '.$CONF_FILE.' | grep ndelta='));
$e = shell_exec('sed -e "s/'.$t.'/ntail='.$ntail.'/; s/'.$d.'/ndelta='.$ndelta.'/" '.$CONF_FILE.' > /www/1; cp /www/1 '.$CONF_FILE.' ');
}
if (isset($_POST['sound']))
{
$si = htmlspecialchars($_POST['sound']);
$t = trim(shell_exec('cat '.$CONF_FILE.' | grep sound='));
$e = shell_exec('sed -e "s/'.$t.'/sound='.$si.'/" '.$CONF_FILE.' > /www/1; cp /www/1 '.$CONF_FILE.' ');
$sound = $soundf[$si];
$volume = intval(shell_exec('cat '.$CONF_FILE.' | grep volume= | sed -e "s/volume=//"'));
$volumen = $volume/10;
$audio = "<audio id='myaudio' autoplay='autoplay'><source src='".$sound."' type='audio/ogg' /></audio><script>var audio = document.getElementById('myaudio'); audio.volume = ".$volumen.";</script>";
echo $audio;
}
if (isset($_POST['volume']))
{
$volume = htmlspecialchars($_POST['volume']);
$volumen = $volume/10;
$t = trim(shell_exec('cat '.$CONF_FILE.' | grep volume='));
$si = intval(shell_exec('cat '.$CONF_FILE.' | grep sound= | sed -e "s/sound=//"'));
$sound = $soundf[$si];
$e = shell_exec('sed -e "s/'.$t.'/volume='.$volume.'/" '.$CONF_FILE.' > /www/1; cp /www/1 '.$CONF_FILE.' ');
$audio = "<audio id='myaudio' autoplay='autoplay'><source src='".$sound."' type='audio/ogg' /></audio><script>var audio = document.getElementById('myaudio'); audio.volume = ".$volumen.";</script>";
echo $audio;
}

$FILE = shell_exec('cat /www/mpu6050 | grep FILE=');
$TMP_FILE = shell_exec('cat /www/mpu6050 | grep TMP_FILE=');
$s = shell_exec('cat '.$CONF_FILE.' | grep s= | sed -e "s/s=//"');
$ntail = intval(shell_exec('cat '.$CONF_FILE.' | grep ntail= | sed -e "s/ntail=//"'));
$ndelta = intval(shell_exec('cat '.$CONF_FILE.' | grep ndelta= | sed -e "s/ndelta=//"'));
$cdelta = intval(shell_exec('cat '.$CONF_FILE.' | grep cdelta= | sed -e "s/cdelta=//"'));
$si = intval(shell_exec('cat '.$CONF_FILE.' | grep sound= | sed -e "s/sound=//"'));
$sound = $soundf[$si];
$volume = intval(shell_exec('cat '.$CONF_FILE.' | grep volume= | sed -e "s/volume=//"'));
?>
	<form action="index2.php" method="post">
		<?php
		$input = "sens:".$cdelta." range:".$ntail." ndelta:".$ndelta;
		echo "$input"; 
		?>
		<input type="submit" value="Sensor" />
	</form>
	<form onsubmit="return true" oninput="level.value = sens.valueAsNumber" method="post">
		<p>Sensitivity: <output for="flying" name="level"><?php echo "$cdelta";  ?></output></p>
		<?php
		$input = " <input name='sens' id='flying' type='range' min='1' max='30' value='".$cdelta."' step='1'>";
		echo "$input"; 
		?>
		<input type="submit" value="Save" />
	</form>
	<form onsubmit="return true" oninput="levelt.value = tail.valueAsNumber" method="post">
		<p>Range: <output for="flying" name="levelt"><?php echo "$ntail";  ?></output></p>
		<?php
		$input = " <input name='tail' id='flying' type='range' min='1' max='30' value='".$ntail."' step='1'>";
		echo "$input"; 
		?>
		<input type="submit" value="Save" />
	</form>
	<form name="formsound" method="post">
		<p>Sound: <output for="formsound" name="fsound"><?php echo "$sound";  ?></output></p>
		<input type="radio" name="sound" value="0" onclick="formsound.fsound.value=this.value" />tada.ogg
		<input type="radio" name="sound" value="1" onclick="formsound.fsound.value=this.value" />kasp.ogg
		<?php
		$script = " <script> function RAZ911() {var c = document.getElementsByName('sound'); if (!c[".$si."].checked) c[".$si."].checked = true;} RAZ911(); </script>";
		echo $script;
		?>
		<input type="submit" value="Save" /> 
	</form>
	<form onsubmit="return true" oninput="levelv.value = volume.valueAsNumber" method="post">
		<p>Volume: <output for="flying" name="levelv"><?php echo "$volume";  ?></output></p>
		<?php
		$input = " <input name='volume' id='flying' type='range' min='0' max='10' value='".$volume."' step='1'>";
		echo "$input"; 
		?>
		<input type="submit" value="Save" />
	</form>
	<form method="post">
		<input type="text" ID='cl3' size=15 value="" name="vd">
		<script>
			function clock3(){
			var d=new Date()
			return d.toLocaleDateString()+" "+d.toLocaleTimeString()
			}
			setInterval("document.all.cl3.value=clock3()",500)
		</script>
		<?php
		$input = "<br>".$dt."	";
		echo "$input"; 
		?>
		<input type="submit" value="Set Date" />
	</form>
  </body>
</html>
<?php
include_once ("db.php");
if (isset($_SERVER['QUERY_STRING'])) {
	if ($_SERVER['QUERY_STRING']=='pel') $rs=sq_query("delete from pelna;vacuum;");
	if ($_SERVER['QUERY_STRING']=='tym') $rs=sq_query("delete from tymczasowa;vacuum;	");
	if ($_SERVER['QUERY_STRING']=='vac') $rs=sq_query("vacuum;");
}

if (isset($_POST) && isset ($_POST['field'])) {
	$f=$_POST['field'];
	sq_query("INSERT INTO tymczasowa SELECT * FROM pelna WHERE $f>=".$_POST['od_km']." AND $f<=".$_POST['do_km'].";");
}

if (1)  
{

?>
<html><head>
  <meta http-equiv="Refresh" content="0; url=index.php" />
</head><body>
  <p>Przejdü pod ten <a href="index.php">link</a>!</p>
</body></html>
<?php
}
?>
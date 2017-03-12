<?php
header( 'Content-type: text/html; charset=utf-8' );
include_once ("db.php");
include_once ("jr_sqlite.php");
$dir="uploads\\";


print "<PRE>";
//print_r($_POST);print_r($_SERVER);
$ins="";$txt="";
if (isset($_POST['delete'])) 
	if (isset($_POST['files']))
	foreach ($_POST['files'] as $fn) {
		print (" Delete file: $fn \n");
		@flush();@ob_end_flush();
		unlink($dir.$fn);
	}




if (isset($_POST['parse'])) {
	if (isset($_POST['files']))
	foreach ($_POST['files'] as $fn) {
		print ("<br /> Start parsing $fn \n");
		@flush();@ob_end_flush();

		$f=$dir.$fn;
		$a=pathinfo ($f);$cab=$a['filename'];
//		print_r($cab);
//		print ("\n\n");
		$fp=fopen($f,"r");
		$i=0;
		while(!feof($fp)) {
			$l=trim(fgets ($fp));
			if ($l && !strpos($l,"space")) {
//				print ("$l\n");
				$l=explode(";",$cab.";".$l);
				if (isset($l[12])) unset ($l[12]);
				if (isset($l[11])) $l[11]=trim(strtr($l[11],'"'," "));
//				print_r($l);die();
				$txt.=implode(";",$l)."\n";
				$ins.=sq_insert("pelna",$l,0,1,1)."\n";
				$i++;
				if ($i%1000  ==0) {print str_pad($i,10," ",STR_PAD_LEFT); @flush();@ob_end_flush();}
				if ($i%10000 ==0) {print ("\n"); @flush();@ob_end_flush();}
				if ($i%30000==0) {
					//print($ins);
					//$db->query($ins);$ins="";
								
				}

			}
	
		}
		fclose($fp);
		file_put_contents('db\ins.txt',$ins);
		file_put_contents('db\csv.txt',$txt);
		
		print ("<br /> EXEX <br />");@flush();@ob_end_flush();
		$eee="";
		exec('db\sqlite3.exe db\db.db3 <db\insert.sql',$eee);
		print ("<br /> Finish parsing $fn - $i lines parsed.\nExecuting INSERT to Database");@flush();@ob_end_flush();
//		if ($ins) $db->query($ins);
		print ("<br /> INSERT to Database finished<hr />\n");
		print_r ($eee);
//		unlink('db\csv.txt');
	}
}
print "</PRE>";


print "<hr /><a href='index.php'>Back</a>";

?>
<script>
window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);
</script>
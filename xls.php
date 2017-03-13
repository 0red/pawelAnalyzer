<?php
// http://stackoverflow.com/questions/25628205/is-it-possible-to-generate-or-clone-pivot-tables-using-phpexcel-library


error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
function jr_p($text) {
//ob_end_clean();
print $text."<br />\n";@flush();@ob_end_flush();
//ob_start();
}


header( 'Content-type: text/html; charset=utf-8' );
include_once ("db.php");
require_once 'PHPExcel.php';
require('pivot/Pivot.php');
date_default_timezone_set('UTC');

$phpXls = new PHPExcel();

$phpXls ->getProperties()->setCreator("Jacek Rusin")
							 ->setLastModifiedBy("Jacek Rusin")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");
	
/*	
// result = 'B'
$columnLetter = PHPExcel_Cell::stringFromColumnIndex(1); 
and backwards:

// result = 1
$colIndex = PHPExcel_Cell::columnIndexFromString('B');
	*/
	
function num2alpha($n)
// 0=A 1=B etc
{
    for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
        $r = chr($n%26 + 0x41) . $r;
    return $r;
}						 
	
function xls_cell ($alpha,$num) {
	return num2alpha($alpha)."".$num;
}
							 
function jr_xls_save($_xls=null,$file_name='',$act_sheet=0) {
  global $phpxls;
  if (!$_xls) $_xls=$phpxls;
  if (!$file_name) $file_name=__FILE__;

  $_xls->setActiveSheetIndex($act_sheet);
  $objWriter = PHPExcel_IOFactory::createWriter($_xls, 'Excel2007');
  $objWriter->save(str_replace('.php', '.xlsx', $file_name));
}

function jr_xls_read($file_name,$worksheets=array()) {
  $t = PHPExcel_IOFactory::createReaderForFile($file_name);
  $t->setReadDataOnly(true);
  if ($worksheets) {
      if (!is_array($worksheets)) $worksheets=array($worksheets);
      $t->setLoadSheetsOnly($worksheets);
  } else {
      $t->setReadDataOnly(true);
  }
  $tt=$t->load($file_name);
  #echo date('H:i:s') , " Iterate worksheets" , EOL;
  $w=array();
  foreach ($tt->getWorksheetIterator() as $worksheet) {
  #	echo 'Worksheet - ' , $worksheet->getTitle() , EOL;
    $w[$w1=$worksheet->getTitle()]=array();

    foreach ($worksheet->getRowIterator() as $row) {
#		echo '    Row number - ' , $row->getRowIndex() , EOL;
      $w3=array();
			
      $cellIterator = $row->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
      foreach ($cellIterator as $cell) {
        if (!is_null($cell)) {
#			  	echo '        Cell - ' , $cell->getCoordinate() , ' - ' , $cell->getCalculatedValue() , EOL;
          $w3[]=$cell->getCalculatedValue();
        }
      }
      $w[$w1][$row->getRowIndex()]=$w3;
    }
  }
  return ($w);
}

function sheet($name,$arrayData,$csv=0) {
	global $phpXls;
	
//	$csv=";";
	
	if (!is_array($arrayData) || !$arrayData ) {
		$arrayData=array( 0=>array('blad'=>'danych'));
	}

	if ($csv) {
		$l="\"";
		$a="";
		$a.=$l.join ($l.$csv.$l,array_keys($arrayData[0])).$l;
		foreach($arrayData as $b) $a.="\n".$l.join ($l.$csv.$l,$b).$l;
		return $a;
	}
	
	$wk=new PHPExcel_Worksheet($phpXls, $name);
	$phpXls->addSheet($wk, 0);
	
	$phpXls->setActiveSheetIndex(0);
	$phpXls->getActiveSheet()
		->fromArray(
        array_keys($arrayData[0]),  // The data to set
        NULL,        // Array values with this value will not be set
        'A1'         // Top left coordinate of the worksheet range where
                     //    we want to set these values (default is A1)
               )
    ->fromArray(
        $arrayData,  // The data to set
        NULL,        // Array values with this value will not be set
        'A2'         // Top left coordinate of the worksheet range where
                     //    we want to set these values (default is A1)
    );
    
    $range='A1:'.xls_cell(count($arrayData[0])-1,count($arrayData)+1);
    
    $phpXls->getActiveSheet()->setAutoFilter($range);
    $styleArray = array(
//	'font' => array(
//		'bold' => true,
	//),
//	'alignment' => array(
//		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
//	),
	'borders' => array(
		'top' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			),
		'bottom' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			),
		'left' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,	
			),	
		'right' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,	
			),
		),
/*	'fill' => array(
		'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
		'rotation' => 90,
		'startcolor' => array(
			'argb' => 'FFA0A0A0',
		),
		'endcolor' => array(
			'argb' => 'FFFFFFFF',
		),
	),
	*/
);

		for ($i=0;$i<=count($arrayData[0])-1;$i++)
			for ($j=1;$j<=count($arrayData)+1;$j++)
				$phpXls->getActiveSheet()->getStyle(xls_cell($i,$j))->applyFromArray($styleArray);

//    print "<pre>".count($arrayData)." ".count($arrayData[0])."\n\n";
//    print "<pre>".'A1:'.cell(count($arrayData[0]),count($$arrayData));print_r($arrayData);
}

if (isset($_SERVER["QUERY_STRING"])) {
	if ($_SERVER["QUERY_STRING"]=="avg") {
		print "<h2>aaAverage and other data generation from Analyzed data</h2>";		@flush();@ob_end_flush();

//		ob_start();
		$recordset=sq_query("SELECT * FROM avg_tch");
		sheet("avg_tch",$recordset);
		jr_p("avg_tch");
		
		$recordset=sq_query("SELECT * FROM avg_bcch");
		sheet("avg_bcch",$recordset);
		jr_p("avg_bcch");
		
		$recordset=sq_query("SELECT * FROM avg_session");
		sheet("avg_session",$recordset);
		jr_p("avg_session");
		
		$recordset=sq_query("SELECT * FROM avg_session_bcch");
		sheet("avg_session_bcch",$recordset);
		jr_p("avg_session_bcch");
		
		$recordset=sq_query("SELECT * FROM avg_session_tch");
		sheet("avg_session_tch",$recordset);
		jr_p("avg_session_tch");

		
		$recordset=sq_query("SELECT * FROM per_rxqual_tch");
		$data = Pivot::factory($recordset)
			->pivotOn(array('cab','tch'))
			->addColumn(array('rxqual'), array('ile'))
			->fullTotal()
			->lineTotal()

			->fetch();
		sheet("per_rxqual_tch",$data);
		jr_p("per_rxqual_tch");

		$recordset=sq_query("SELECT * FROM per_rxqual_bcch");
		$data = Pivot::factory($recordset)
			->pivotOn(array('cab','bcch'))
			->addColumn(array('rxqual'), array('ile'))
			->fullTotal()
			->lineTotal()
			->fetch();
		sheet("per_rxqual_bcch",$data);
		jr_p("per_rxqual_bcch");


		$recordset=sq_query("SELECT * FROM session_rxqual_bcch");
		$data = Pivot::factory($recordset)
			->pivotOn(array('cab','session','bcch'))
			->addColumn(array('rxqual'), array('ile'))
			->fullTotal()
			->lineTotal()
			->fetch();
		sheet("session_rxqual_bcch",$data);
		jr_p("session_rxqual_bcch");
			
		$recordset=sq_query("SELECT * FROM session_rxqual_tch");
		$data = Pivot::factory($recordset)
			->pivotOn(array('cab','session','tch'))
			->addColumn(array('rxqual'), array('ile'))
			->fullTotal()
			->lineTotal()

			->fetch();  
		sheet("session_rxqual_tch",$data);
		jr_p("session_rxqual_tch");
			
//		ob_end_clean();

		print "<a href='db\dupa.xlsx'> open AVG excel file</a>";
		jr_xls_save($phpXls,'db\dupa.xlsx');


	}
	
	if ($_SERVER["QUERY_STRING"]=="tym") {
		print "<h2> Analyzed data dump</h2>";@flush();@ob_end_flush();
		$recordset=sq_query("SELECT * FROM tymczasowa;");
		sheet("analyzed",$recordset);
		print "<a href='db\dupa.xlsx'> open analyzed records excel file</a>";
		jr_xls_save($phpXls,'db\dupa.xlsx');

	}

	if ($_SERVER["QUERY_STRING"]=="all") {
		print "<h2> Full parsed data dump </h2>";@flush();@ob_end_flush();
		$recordset=sq_query("SELECT * FROM pelna;");
		sheet("parsed",$recordset);
		print "<a href='db\dupa.xlsx'> open parsed DUMP data	 excel file</a>";
		jr_xls_save($phpXls,'db\dupa.xlsx');
	}

	if ($_SERVER["QUERY_STRING"]=="tymc") {
		print "<h2> Analyzed data dump</h2>";@flush();@ob_end_flush();
		$recordset=sq_query("SELECT * FROM tymczasowa;");
		file_put_contents ('db\dupa.csv', sheet("avg_tch",$recordset,";"));
		print "<a href='db\dupa.csv'> open analyzed records excel file</a>";

	}

	if ($_SERVER["QUERY_STRING"]=="allc") {
		print "<h2> Full parsed data dump </h2>";@flush();@ob_end_flush();
		$recordset=sq_query("SELECT * FROM pelna;");
		file_put_contents ('db\dupa.csv', sheet("avg_tch",$recordset,";"));
		print "<a href='db\dupa.csv'> open parsed DUMP data	 excel file</a>";
	}


	
}
    
	print "<hr /><a href='index.php'>Back</a>";

?>
<script>
window.scrollTo(0, document.body.scrollHeight || document.documentElement.scrollHeight);
</script>	
<?php
//localhost:8082/tests/pivot/test.php
//https://gonzalo123.com/2010/01/24/pivot-tables-in-php/
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

include_once ("db.php");
require('pivot/Pivot.php');


function simpleHtmlTable($data)
{
    // do you like spaghetti? 
    echo "<table border='1'>";
    echo "<thead>";
    foreach (array_keys($data[0]) as $item) {
        echo "<td><b>{$item}<b></td>";
    }
    echo "</thead>";
    foreach ($data as $row) {
        echo "<tr>";
        foreach ($row as $item) {
            echo "<td>{$item}</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}




function per_rxqual_tch() {
	echo "<h2>per_rxqual_tch</h2>";
	$recordset=sq_query("SELECT * FROM per_rxqual_tch");
	$data = Pivot::factory($recordset)
    ->pivotOn(array('cab','tch'))
    ->addColumn(array('rxqual'), array('ile'))
		->fullTotal()
    ->lineTotal()

    ->fetch();
	simpleHtmlTable($data);
}

function per_rxqual_bcch() {
	echo "<h2>per_rxqual_bcch</h2>";
	$recordset=sq_query("SELECT * FROM per_rxqual_bcch");
	$data = Pivot::factory($recordset)
    ->pivotOn(array('cab','bcch'))
    ->addColumn(array('rxqual'), array('ile'))
		->fullTotal()
    ->lineTotal()
    ->fetch();
	simpleHtmlTable($data);
}


function session_rxqual_bcch() {
	echo "<h2>per_rxqual_bcch</h2>";
	$recordset=sq_query("SELECT * FROM session_rxqual_bcch");
	$data = Pivot::factory($recordset)
    ->pivotOn(array('cab','session','bcch'))
    ->addColumn(array('rxqual'), array('ile'))
		->fullTotal()
    ->lineTotal()
    ->fetch();
	simpleHtmlTable($data);
}

function session_rxqual_tch() {
	echo "<h2>per_rxqual_tch</h2>";
	$recordset=sq_query("SELECT * FROM session_rxqual_tch");
	$data = Pivot::factory($recordset)
    ->pivotOn(array('cab','session','tch'))
    ->addColumn(array('rxqual'), array('ile'))
		->fullTotal()
    ->lineTotal()

    ->fetch();
	simpleHtmlTable($data);
}

function avg_tch() {
	echo "<h2>avg_tch</h2>";
	$recordset=sq_query("SELECT * FROM avg_tch");

	simpleHtmlTable($recordset);
}

function avg_bcch() {
	echo "<h2>avg_bcch</h2>";
	$recordset=sq_query("SELECT * FROM avg_bcch");

	simpleHtmlTable($recordset);
}

function avg_session_tch() {
	echo "<h2>avg_session_tch</h2>";
	$recordset=sq_query("SELECT * FROM avg_session_tch");

	simpleHtmlTable($recordset);
}

function avg_session_bcch() {
	echo "<h2>avg_session_bcch</h2>";
	$recordset=sq_query("SELECT * FROM avg_session_bcch");

	simpleHtmlTable($recordset);
}

function avg_session() {
	echo "<h2>avg_session</h2>";
	$recordset=sq_query("SELECT * FROM avg_session");

	simpleHtmlTable($recordset);
}


if (isset($_SERVER["QUERY_STRING"])) {
	if ($_SERVER["QUERY_STRING"]=="tch") {
		avg_tch();
		avg_session_tch();
		per_rxqual_tch();
		session_rxqual_tch();
	} 

	if ($_SERVER["QUERY_STRING"]=="bcch") {
		avg_bcch();
		avg_session_bcch();
		per_rxqual_bcch();
		session_rxqual_bcch();
	} 
	
	if ($_SERVER["QUERY_STRING"]=="avg") {
		avg_bcch();
		avg_tch();
		avg_session();

	} 

	if ($_SERVER["QUERY_STRING"]=="xls") {
		avg_bcch();
		avg_tch();
		avg_session();

	} 

	
}



?>
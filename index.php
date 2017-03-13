<?php
include_once ("db.php");
include_once "jr_file.php";
?>
<html>
<head>
<!-- http://www.dropzonejs.com/#installation -->
<!-- https://www.startutorial.com/articles/view/how-to-build-a-file-upload-form-using-dropzonejs-and-php -->
<!-- https://www.startutorial.com/articles/view/move_uploaded_file-faq -->
<!-- 1 -->
<link href="dropzone/dropzone.css" type="text/css" rel="stylesheet" />
<!-- 2 -->
<script src="dropzone/dropzone.js"></script>

</head><body>
<h2> Download Area</h2>
Drag and drop the files below:
<!-- 3 -->
<form action="upload.php" id="my-dropzone" class="dropzone"></form>
<script>
//myDropzone.on("complete", function(file) {
//});

Dropzone.options.myDropzone = {
  init: function() {
		/*
    this.on("success", function(file, responseText) {
      // Handle the responseText here. For example, add the text to the preview element:
      //file.previewTemplate.appendChild(document.createTextNode(responseText));
      //myDropzone.removeFile(file);
			//location.reload(true);

    });
    */
    this.on("queuecomplete", function() {
 			location.reload(true);
    });
    /*
    this.on("addedfile", function(file) {

        // Create the remove button
        var removeButton = Dropzone.createElement("<button>Remove file</button>");


        // Capture the Dropzone instance as closure.
        var _this = this;

        // Listen to the click event
        removeButton.addEventListener("click", function(e) {
          // Make sure the button click doesn't submit the form:
          e.preventDefault();
          e.stopPropagation();

          // Remove the file preview.
          _this.removeFile(file);
          // If you want to the delete the file on the server as well,
          // you can do the AJAX request here.
        });

        // Add the button to the file preview element.
        file.previewElement.appendChild(removeButton);
      });
    */
    
  }
};
</script>
<!-- 
<h2><a href="delete.php" onclick="if (!confirm('Are you sure to erase ALL uploaded files?')) return false;">erase files</a></h2>
3 -->
<table><tr><td>
<h2>DB info</h2>
<?php
 $a=sq_query("select count() as count from tymczasowa");
 $c=sq_query("select cab,count() as count from tymczasowa group by 1 order by 1");
 $b=sq_query("select count() as count from pelna");
 $d=sq_query("select cab,count() as count from pelna group by 1 order by 1");
 $a=jr_table($c,1,1,'For Anayze',"Total ".$a[0]['count']);
 $b=jr_table($d,1,1,'Parsed',"Total ".$b[0]['count']);
 print "<table><tr><td>$a</td></tr><tr><td>$b</td></tr></table>";
 
?>
</td><td>
<h2>Select files to import</h2>
<form action="parse.php" method="post"><div>
<input type="reset" value="Clear selection" /><br />
<?php
$a=jr_dir_file("uploads/");
foreach ($a as $a1) {
	print "<input type='checkbox' name='files[]' value='".basename($a1)."' />".basename($a1)."<br />\n";
}

//print_r($a);
?>
<input type="submit" name="parse" value="Parse selected files" /><br />
<input type="submit" name="delete" value="Delete selected files" />
</div></form>
<a href="index.php">Refresh uploaded</a>
</td><td>
<h2>DB management</h2>
<a href="erase.php?tym" onclick="if (!confirm('Are you sure to erase ALL analyzed data from DB?')) return false;">Clear For Anayze</a><br />
<a href="erase.php?pel" onclick="if (!confirm('Are you sure to erase ALL parsed data from DB?')) return false;">Clear Parsed</a><br />
<a href="erase.php?vac">Force Vacuum</a>
</td><td>

<h2>Data Selection</h2>
<form action="erase.php" method="post"><div>
from: <input type='text' name='od_km' /> <br />
do: <input type='text' name='do_km' /> <br />
<input type="submit" name="field" value="progressive" />(km)<br />
<input type="submit" name="field" value="rxqual" /><br />
<input type="submit" name="field" value="rxqlev" /><br />
<input type="submit" name="field" value="tch" /><br />
<input type="submit" name="field" value="bcch" />
</form>
</td><td>
<h2>Data Output</h2>
<a href="xls.php?avg">AVG XLS</a><hr />
<a href="pivot.php?avg">Average</a><br />
<a href="pivot.php?tch">TCH</a><br />
<a href="pivot.php?bcch">BCCH</a><hr />
<a href="xls.php?tymc">ALL analyzes CSV (warning can be BIG)</a><br />
<a href="xls.php?allc">ALL parsed CSV (warning can be very BIG)</a><br />
<!--
!!! XLS generatino can take some time...
<a href="xls.php?tym">ALL analyzes XLS (warning can be BIG)</a><br />
<a href="xls.php?all">ALL parsed XLS (warning can be very BIG)</a><br />
-->



</td></tr></table>
<pre>
<?php
//print_r($_SERVER);
//print_r($_POST);
?>
</pre>
</body>
</html>
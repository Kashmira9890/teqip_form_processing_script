<?php
$path = $_SERVER["DOCUMENT_ROOT"]."/php_scripting/";

$student = 'studentuploadtemp.csv';
$college = 'collegecode.csv';
$dept 	= 'deptcodes.csv';

$tempfile = 'temporary.csv';
$exportfiltered = 'export-filtered.csv';

if (file_exists($student)) {
    $inputstudent 	= fopen($student, 'r'); //open for reading
    $inputcollege 	= fopen($college, 'r'); 
    $inputdept 	= fopen($dept, 'r');
    $output 		= fopen($tempfile, 'w'); //open for writing

    while(! feof($inputcollege)) {
    	$col = 0;
    	$collegedata = fgetcsv($inputcollege);
	$c[$collegedata[$col+1]] = $collegedata[$col];
    }
    while(! feof($inputdept)) {
    	$col = 0;
	$deptdata = fgetcsv($inputdept);
	$d[$deptdata[$col+1]] = $deptdata[$col];
    }

    $header = array("email", "username", "firstname", "middlename", "lastname", "institution", "department", "profile_field_yoe", "idnumber", "cohort1");
    $count = 0;
    while( false !== ( $data = fgetcsv($inputstudent) ) ) { //read each line as an array
	$size = sizeof($data);

	if($count == 0) {
		$a = $header;
	} else {
		$a = $data;
		$a[0] = $data[1];
		array_splice($a, 5, 1);
	}
	
	// College data
	foreach($c as $x => $x_value) {
    		if($data[6] == $x_value) {
    			$a[$size-1] = $x . " " . $a[7] . " ";
    			break;
    		}
	}
	
	// Dept data
	foreach($d as $x => $x_value) {
    		if($data[7] == $x_value) {
    			$a[$size-1] = $a[$size-1] . $x . " Student";
    			break;
    		}
	}

        //write modified data to temporary file
        fputcsv( $output, $a);
        $count++;
    }

    //close both files
    fclose( $inputstudent );
    fclose( $output );
} else {
    die('Import file not found!');  
}
?>

<?php
$path = $_SERVER["DOCUMENT_ROOT"]."/php_scripting/";

$student = 'studentuploadtemp.csv';
$college = 'collegecode.csv';
$dept = 'deptcodes.csv';

$tempfile = 'temporary.csv';
$exportfiltered = 'export-filtered.csv';


if (file_exists($student)) {

   /* if ( unlink($exportfiltered) ) { // Delete previous filtered export
        print "Deleted previous $exportfiltered before import<br>";     
    } else {
        print "Delete of previous $exportfiltered before import failed!<br>";
    }*/

    $inputstudent = fopen($student, 'r'); //open for reading
    $inputcollege = fopen($college, 'r'); //open for reading
    $inputdept = fopen($dept, 'r'); //open for reading
    
    $output = fopen($tempfile, 'w'); //open for writing

    echo "\nCollege data:\n";
    
    //for ($row = 0; $row < 20; $row++) {
  		$row = 0;
  		while(! feof($inputcollege))
    		{
    			$col = 0;
    			echo "\n Col row: \n";
    			$collegedata = fgetcsv($inputcollege);
    			//print_r($collegedata);
    			echo "\n B row: \n";
    			/*
    			for ($col = 0; $col < 2; $col++) {  
				$b[$row][$col] = $collegedata[$col];	
			}*/
			
			$c[$collegedata[$col+1]] = $collegedata[$col];
			
			//$row++;
	        }
	 	//print_r($c);
	 	while(! feof($inputdept))
    		{
    			$col = 0;
    			echo "\n Col row: \n";
    			$deptdata = fgetcsv($inputdept);
    			//print_r($deptdata);
    			echo "\n D row: \n";
    			/*
    			for ($col = 0; $col < 2; $col++) {  
				$b[$row][$col] = $collegedata[$col];	
			}*/
			
			$d[$deptdata[$col+1]] = $deptdata[$col];
			
			//$row++;
	        }
	        //print_r($d);
  	//}
    
    
    //fclose($file);
    
    //print_r($b);
$header = array("email", "username", "firstname", "middlename", "lastname", "institution", "department", "profile_field_yoe", "idnumber", "cohort1");
	$count = 0;
    while( false !== ( $data = fgetcsv($inputstudent) ) ) { //read each line as an array
	echo "writing data . . . \n";
	
	//print_r($data);
	echo 'writing count . . . ' . $count . "\n";
	
	
	$size = sizeof($data);
	/*
	for($i=0; $i<$size; $i++) {
		if($count == 0) {
			$a[$i] = $header[$i];
			
		} else {
			if($i == 0){
				$a[$i] = $data[$i+1];
			} elseif($i == 5) {
				continue;
			} else {
				$a[$i] = $data[$i];
			}
		}
		echo "writing a . . . \n";
		print_r($a);
	}*/
	if($count == 0) {
		$a = $header;
	} else {
		$a = $data;
		$a[0] = $data[1];
		array_splice($a,5,1);
	}
	
	echo "\nColleges\n";
	foreach($c as $x => $x_value) {
    		//echo "Key=" . $x . ", Value=" . $x_value;
    		//echo "<br>";
    		if($data[6] == $x_value) {
    			$a[$size-1] = $x . " " . $a[7] . " ";
    			break;
    		}
	}
	
	foreach($d as $x => $x_value) {
    		//echo "Key=" . $x . ", Value=" . $x_value;
    		//echo "<br>";
    		if($data[7] == $x_value) {
    			$a[$size-1] = $a[$size-1] . $x . " Student";
    			break;
    		}
	}
	
	//$a[$size] = "cohort1";
	echo 'writing a . . . here . . ';
	print_r($a);
	

        //modify data here      
        /*if ( $data[14] == 'Rate Name' ) {
            $data[14] = 'RATE_NAME';
        }*/

        //write modified data to temporary file
        fputcsv( $output, $a);
        $count++;

    }

    //close both files
    fclose( $inputstudent );
    fclose( $output );

    /*if ( unlink($student) ) { // Delete raw export file
        print "Deleted $student<br>";     
    } else {
        print "Delete of $student failed!<br>";
    }

    if ( copy($path.$tempfile, $path.$exportfiltered) ) { // Rename temporary to new
        print "Export renamed<br>";
    } else {
        print "Export was not renamed!<br>";
    }

    echo '<pre>';print_r(error_get_last());echo '</pre>';*/



} else {
    die('Import file not found!');  
}
?>

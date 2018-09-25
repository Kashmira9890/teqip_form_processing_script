<?php
$path = $_SERVER["DOCUMENT_ROOT"]."/php_scripting/";

$student = 'studentuploadtemp.csv';
$college = 'collegecode.csv';
$dept      = 'deptcodes.csv';

$tempfile = 'temporary.csv';
$exportfiltered = 'export-filtered.csv';

if (file_exists($student)) {
    $inputstudent 	= fopen($student, 'r'); // Open for reading
    $inputcollege 	= fopen($college, 'r'); 
    $inputdept 	= fopen($dept, 'r');
    $output 		= fopen($tempfile, 'w'); // Open for writing

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
    
    // Get csv data
    $rows = array_map('str_getcsv', file($student));
    $my_header = array_shift($rows);
    $header = $my_header;
   
    // Formatting header     
    array_unshift($header,"username");
  
    if (in_array("Timestamp", $header)) {
    	$key = array_search ('Timestamp', $header);
    	array_splice($header, $key, 1);
    }
    
    if (in_array("Email Address", $header)) {
    	$key = array_search ('Email Address', $header);
    	array_splice($header, $key, 1, 'email');
    }
  
    if (in_array("Mobile number", $header)) {
    	$key = array_search ('Mobile number', $header);
    	array_splice($header, $key, 1);
    }
    
    if (in_array("Year of Admission to College", $header)) { 
    	$key = array_search ('Year of Admission to College', $header);
    	array_splice($header, $key, 1, 'profile_field_yoe');
    }
    
    if (in_array("College ID or Unique Roll Number", $header)) {
    	$key = array_search ('College ID or Unique Roll Number', $header);
    	array_splice($header, $key, 1, 'idnumber');
    }
    
    array_push($header,"cohort1");
    
    $header = array_map('strtolower', $header);
    $header = str_replace( ' ', '', $header);  
    fputcsv($output, $header);
    
    // Formatting csv data
    $csv = array();
    $i = 0;
    $c_code = null;
    $d_code = null;

    foreach($rows as $i=>$row) {
        $csv[$i] = array_combine($my_header, $row);
        $csv[$i] = array_merge(array('username' => $csv[$i]['Email Address']), $csv[$i]);
        
        if(array_key_exists('Timestamp', $csv[$i])) {
        	unset($csv[$i]['Timestamp']);
        }
        
        if(array_key_exists('Mobile number', $csv[$i])) {
        	unset($csv[$i]['Mobile number']);
        }
        
        if(array_key_exists("Institute", $csv[$i])) {
        	$value = $csv[$i]["Institute"];
        	$c_code = array_search($value, $c);
        }
        
         if(array_key_exists("Department", $csv[$i])) {
        	$value = $csv[$i]["Department"];
        	$d_code = array_search($value, $d);
        }
        
        if($c_code && $d_code) {
        	$csv[$i]['cohort1'] = $c_code . " " . $csv[$i]['Year of Admission to College'] . " " . $d_code . " Student";
        } else {
        	$csv[$i]['cohort1'] = "";
        }   
        
         fputcsv( $output, $csv[$i]);     
    }  
    
    // Printing on terminal
    print_r($header);
    print_r($csv);

    // Close both files
    fclose( $inputstudent );
    fclose( $output );
} else {
    die('Import file not found!');  
}
?>

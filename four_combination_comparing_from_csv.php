<?php 
  if($_POST['post'] !="") {
		$file_name = $_FILES['csv_file']['name'];
		$file_type = $_FILES['csv_file']['type'];
		$file_temp_name = $_FILES['csv_file']['tmp_name'];
		$file_extension = end(explode('.', $file_name));
		if($file_extension == "csv" && $file_type == "text/csv") {
			$file_open = fopen($file_temp_name, 'r');
			if(!$file_open) {
				die('Can not open the uploaded file');
			}
			$row = 0;
			while($data = fgetcsv($file_open, 1024)) {
				if($row != 0) {
					$data_values[] = $data; 
				}
			$row++;
			}
			$output_array = array();
			$exist_array = array();
			$check_val = "";
			foreach ($data_values as $key=>$inner_array) {
				$check_val = $inner_array[1].$inner_array[2].$inner_array[3].$inner_array[4];
			    if (!in_array($check_val, $exist_array)) {
				$exist_array[] = $inner_array[1].$inner_array[2].$inner_array[3].$inner_array[4];
				$output_array[] = $inner_array; 
			    }
			}
			$file_name = "double_current_seesion_".date('y-m-d_H:i:s').".CSV";
			function outputCSV($data) {
				$outputBuffer = fopen("php://output", 'w');
				foreach($data as $val) {
				    fputcsv($outputBuffer, $val);
				}
				fclose($outputBuffer);
			}
			header("Content-type: text/csv");
			header("Content-Disposition: attachement; filename=".$file_name);
			header("Pragma: no-cache");
			header("Expires: 0");
			outputCSV($output_array);
			fclose($file_open);
		}
		else {
			header("Location:csv.php?error=1");		
			exit();
		}
	}
	if($_GET['error']== 1) {
		echo "Please Upload CSV File";
	}
?>
<form name="csv_form" action="" method="post" enctype="multipart/form-data">
<input type="file" name="csv_file" id="csv_file" value="">
<br/><br/><input type="submit" name="post" value="Upload">
</form>

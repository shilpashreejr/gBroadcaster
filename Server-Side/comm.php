<?
	require_once('Communication.class.php');
	
	$operation = empty($_REQUEST['operation']) ? false : $_REQUEST['operation'];
	$name = empty($_REQUEST['name']) ? false : $_REQUEST['name'];
	$data = empty($_REQUEST['data']) ? false : $_REQUEST['data'];
	$latitude = empty($_REQUEST['latitude']) ? false : $_REQUEST['latitude'];
	$longitude = empty($_REQUEST['longitude']) ? false : $_REQUEST['longitude'];
	/*
	error_log('Name: ' .$name );
	error_log('Operation: ' . $operation);
	error_log('Data: ' . $data);
	error_log('Latitude: ' . $latitude);
	error_log('Longitude: ' . $longitude);
	*/
	$ret = array(
		'name' => $name,
	);
	
	switch($operation) {
		case 'getData':
			$retData = Communication::getData($data, $latitude, $longitude, $name);
			$ret['data'] = $retData;
			if(empty($ret['data'])) {
				$ret['lastTimestamp'] = $data;
			} else {
				$ret['lastTimestamp'] = Communication::getLastTimestamp($retData);
			}
			break;
		
		case 'postData':
			$savedata = array(
								'name' => $name,
								'data' => $data,
								'timestamp' => time(),
								'latitude' => $latitude,
								'longitude' => $longitude
							); 
			$saveTimestamp = Communication::saveData($savedata);
			$ret['data'] = $saveTimestamp;
			break;
		
		case 'registerName':
			$ret['data'] = time();
			break;
	}
	
	echo json_encode($ret);


	
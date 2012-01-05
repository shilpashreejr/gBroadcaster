<?
	require_once('/home/shil/repo/common/lib/DataStore.class.php');
	
	class Communication {

		public static $DATA_KEY = 'shilChat';
		public static $DATA_SIZE = 100;
		public static $MAX_DISTANCE_IN_KM = 5000;
	
		public static function saveData($data){
			$currentTimeStamp = time();
			$oldData = Communication::getData();
			$oldData[$currentTimeStamp] = $data;
			
			//error_log(count($oldData));
			if (count($oldData) > self::$DATA_SIZE) {
				array_shift($oldData);
			}
			
			//error_log('New Data: ' . print_r($oldData, true));
			DataStore::set(self::$DATA_KEY, $oldData);
			return $currentTimeStamp;
		}
		
		public static function getData($timeStamp=0, $latitude=false, $longitude=false, $name=''){
			$storedData = DataStore::get(self::$DATA_KEY);
			//error_log('stored data:' . print_r($storedData, true));
			//error_log($timeStamp);
			if (empty($timeStamp)) {
				//error_log('return stored data');
				return($storedData);
			} else {
				//error_log('other stored data');
				$calcData = array_reverse($storedData);
				$retData = array();
				foreach($calcData as $k => $v) {
					$distance = self::calculateDistance($latitude, $longitude, $v['latitude'], $v['longitude']);
					//error_log('Distance: ' . $distance);
					if ($v['timestamp'] > $timeStamp) {
						if ($distance < self::$MAX_DISTANCE_IN_KM && $v['name'] != $name) {
							//error_log('Im adding' . print_r($v, true));
							$retData[] = $v;
						}
					} else {
						$eeee = 0;
						//break;
					}
				}
				//error_log('RET DATA********' . print_r($retData, true));
				return $retData;
			}
		}
		
		public static function getLastTimestamp($data) {
			
			$calcData = array_reverse($data);
			$retKey = 0;
			//error_log(print_r($calcData, true));
			foreach ($calcData as $k => $v) {
				//error_log('Key: ' . $k);
				$retKey = $v['timestamp'];
				break;
			}
			return $retKey;
		}
		
		public static function deleteStorage() {
			DataStore::set(self::$DATA_KEY, array());
		}
		
		//distance in km
		public static function calculateDistance($lat1, $lon1, $lat2, $lon2) {
		  $R = 6371; // km
		  $dLat = self::toRad($lat2 - $lat1);
		  $dLon = self::toRad(($lon2 - $lon1)); 
		  $a = sin($dLat / 2) * sin($dLat / 2) +
		  	   cos(self::toRad($lat1)) * cos(self::toRad($lat2)) * 
		  	   sin($dLon / 2) * sin($dLon / 2); 
		  $c = 2 * atan2(sqrt($a), sqrt(1 - $a)); 
		  $d = $R * $c;
		  return $d;
		}
		
		public static function toRad($degree) {
		  return $degree * pi() / 180;
		}
		
	}
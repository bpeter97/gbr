<?php

class Functions {

	/**
	 * For debuging output
	 * @method dump
	 * @param  string $value Data needed to be displayed
	 * @return string        Raw data, may be in an array
	 */
	public function dump($value)
	{
		echo "<pre>";
		var_dump($value);
		echo "</pre>";
	}

	/**
	 * Can be used before storing in database.
	 * May not be needed but added security
	 * @method escape
	 * @param  string $value data needing to striped and encoded
	 * @return string        Encoded string
	 */
	public function escape($value)
	{
		return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	}

	/**
	 * This function will allow us to send text to the console.
	 * @param  string $data The message you want in the console.
	 * @return string       This returns a message to the console.
	 * @example debug('This is the error message');
	 */
	//TODO: Would it not be better to throw an exception to kill the page?
	public function debug($data)
	{
		if ( is_array($data)){
			$data = implode( ',', $data);
		}

		echo "<script>console.log( 'Debug Objects: " . $data . "' );</script>";
	}

	function fixObject (&$object)
	{
		if (!is_object ($object) && gettype ($object) == 'object'){
			return ($object = unserialize (serialize ($object)));
		}
		return $object;
	}

	// This will take the database time and adjust it to the user's timezone!
	function adjustTime($date, $format = null)
	{
		$timezone_array = array(
			'Eastern Standard Time (US)'=>-5,
			'Central Standard Time (US)'=>-6,
			'Mountain Standard Time (US)'=>-7,
			'Pacific Standard Time (US)'=>-8
			);

		if(isset($_SESSION['user']))
		{
			$user = new User();
						$user = unserialize($_SESSION[Config::get('session/user_session')]);
						if($user->getUserTimezone() != null){
							$user_timezone = $user->getUserTimezone();
						} else {
							$user_timezone = Config::get('site/timezone');
						}
						
						$time_diff = $timezone_array[$user_timezone];

			$new_date = new DateTime($date);
			$new_date->modify("{$time_diff} hours");
			if($format == null)
			{
				return $new_date->format('Y-m-d H:i:s');
			} elseif($format == 'date') {
				return $new_date->format('Y-m-d');
			}
			
		} else {

						$time_diff = Config::get('site/timezone');

			$new_date = new DateTime($date);
			$new_date->modify("{$time_diff} hours");

			if($format == null)
			{
				return $new_date->format('Y-m-d H:i:s');
			} elseif($format == 'date') {
				return $new_date->format('Y-m-d');
			}
		}
	}

}

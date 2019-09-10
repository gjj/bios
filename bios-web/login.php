<?php
	// Always start this first
	session_start();
	
	if (isset($_POST['userId']) and isset($_POST['password'])) {
		$data = array(
			'userId' => $_POST['userId'],
			'password' => $_POST['password']
		);
		$result = callAPI("POST", "http://localhost/bios/bios-api/public/app/json/authenticate", $data);

		$result = json_decode($result);

		if (isset($result->status)) {
			if ($result->status == "success") {
				$_SESSION['token'] == $result->token;
			}
			else {
				echo $result->message;
			}
		}
	}

	function callAPI($method, $url, $data = false) {
		$curl = curl_init();
		switch ($method) {
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);

				if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_PUT, 1);
				break;
			default:
				if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);

		curl_close($curl);

		return $result;
	}
?>
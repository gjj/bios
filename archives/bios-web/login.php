<?php
	require_once("config/db.php");

	if (isset($_POST['userId']) and isset($_POST['password'])) {
		$data = array(
			'userId' => $_POST['userId'],
			'password' => $_POST['password']
		);

		$result = callAPI("POST", "http://localhost/bios/app/json/authenticate", $data);
		
		$result = json_decode($result);

		if (isset($result->status)) {
			if ($result->status == "success") {
				if (isset($result->token)) {
					$_SESSION['token'] = $result->token;
					header("Location: home");
				}
				else {
					$_SESSION['token'] = $result->session;
					header("Location: home");
				}
			}
			else {
				echo $result;
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
<?php 
	$con = mysqli_connect("localhost", "root", "fucksociety", "webshop");
	if (mysqli_connect_errno($con)) {
		echo "Faild to connect to Mysqli: " . mysqli_connect_error();
	}
	if (!$con->set_charset("utf8")) {
		printf("Ошибка при загрузке символов utf8: %s\n", $mysqli->error);
		exit();
	}

	if (!isset($_GET["func"])){
		echo json_encode("plz set args");
	}

	$func = $_GET["func"];

	switch ($func){
		case "addIpBlackList":
			addIpBlackList();
			break;
	}

	mysqli_close($conn);
	exit();

	// FUNCTIONS

	function addIpBlackList(){
		if (!isset($_GET["ip"])) {
			echo "plz sent arg";
		 	exit();
		}

		$ip = $_POST["ip"];

		$query = "INSERT INTO blackList (ip) VALUES ('$ip')";

		if (mysqli_query($con, $query)) {
			echo json_encode("success");
		}
		else {
			echo json_encode("error");
		}
	}
?>
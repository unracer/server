<?php 
	$conn = mysqli_connect("localhost", "shop", "fucksociety", "webshop");
	if (mysqli_connect_errno($conn)) {
		echo "Faild to connect to Mysqli: " . mysqli_connect_error();
	}
	if (!$conn->set_charset("utf8")) {
		printf("Ошибка при загрузке символов utf8: %s\n", $mysqli->error);
		exit();
	}

	if (!isset($_GET["func"])){
		echo json_encode("plz set args");
		exit();
	}

	$func = $_GET["func"];

	switch ($func){
		case "addIpBlackList":
			addIpBlackList();
			break;
		case "searchGood":
			searchGood();
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

		if (mysqli_query($conn, $query)) {
			echo json_encode("success");
		}
		else {
			echo json_encode("error");
		}
	}
	function searchGoods(){
		if (!isset($_GET["goods"])) {
			echo "plz sent arg";
		 	exit();
		}

		$good = $_POST["goods"];

		$query = "SELECT id, title, price, about FROM goods WHERE title = '$goods'";

		if (mysqli_query($conn, $query)) {
			echo json_encode($query);
		}
		else {
			echo json_encode("error");
		}
	}
?>
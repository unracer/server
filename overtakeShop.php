<?php

// принимает только GET запросы
// выводиться данные должны только один раз !!!

	$conn=mysqli_connect("localhost","fuck","society", "overtakeshop");
	// why people is stupid.. because fuck th society

	if (mysqli_connect_errno($conn)) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		exit();
	}
	if (!$conn->set_charset("utf8")) {
		printf("Error load char set utf8: %s\n", $mysqli->error);
		exit();
	}

	if (!isset($_GET["func"])){
		echo json_encode("plz set args");
		exit();
	}

	$func = $_GET["func"];

	switch ($func){
		case "goodsCreate":
			goodsCreate();
			break;
		case "goodsGet":
			goodsGet();
			break;
		case "msgCreate":
			msgCreate();
			break;
		case "msgGet":
			msgGet();
			break;
		case "orderCreate":
			orderCreate();
			break;
		case "orderGet":
			orderGet();
			break;
		case "userCreate":
			userCreate();
			break;
		case "userGet":
			userGet();
			break;
		case "userUpdate":
			userUpdate();
			break;
		case "serviceGet":
			serviceGet();
			break;	
		case "blockIp":
			blockIp();
			break;
	}

	mysqli_close($conn);

	// FUNCTIONS

	function goodsCreate(){
		global $conn;
		if (!isset($_GET["id_shop"]) && !isset($_GET["title"])) {
			if (!isset($_GET["price"]) && !isset($_GET["about"])) {
				echo "plz sent arg";
		 		exit(); 
			}
		}

		$id_shop = $_GET["id_shop"]; 
		$title = $_GET["title"]; 
		$price = $_GET["price"]; 
		$about = $_GET["about"]; 

		$query = "INSERT INTO chat (id_shop, title, price, about) VALUES ('$id_shop', '$title', '$price', '$about')";

		if (mysql_query($conn, $query)) {
			echo json_encode("success");
		} else {
			echo json_encode("error");
		}
	}

	function goodsGet(){
		global $conn;
		$query;
		if (isset($_GET["goods"])){
			$goods = $_GET["goods"];
			$query = mysqli_query($conn,"SELECT * FROM goods WHERE title='$goods'");
		} else {
			$query = mysqli_query($conn,"SELECT * FROM goods");
		}
		echo json_encode($query->fetch_all(MYSQLI_ASSOC));
	}

	function msgCreate(){
		global $conn;
		if (!isset($_GET["nickname"]) && !isset($_GET["msg"])) {
			echo "plz sent arg";
		 	exit(); 
		}

		$time = $_GET["time"];
		$nickname = $_GET["nickname"];
		$msg = $_GET["msg"];

		$query = "INSERT INTO chat (time, nickname, msg) VALUES ('$time', '$nickname', '$msg')";

		if (mysqli_query($conn, $query)) {
			echo json_encode("New record create successfully.");
		}
		else {
			echo json_encode("error");
		}
	}

	function msgGet(){
		global $conn;
		if (!isset($_GET["id"])) { echo " plz sent arg"; exit(); }

		$empty_DB = mysqli_query($conn,"SELECT * FROM chat WHERE id = 2"); 
		// если в таблице нет сообщения -- выйти
		if ($empty_DB == null) {
			$query = mysqli_query($conn,"SELECT id, nickname, text FROM chat WHERE id=1");
					echo json_encode($query->fetch_all(MYSQLI_ASSOC));
					exit();
		} 

		$needidmsg = 0;
		$needidmsg = $_GET["id"]; 
		// сразу берем нужное сообщение (может его и нет вовсе)
		$needmsg = mysqli_query($conn,"SELECT * FROM chat WHERE id = '$needidmsg'"); 

		$needmsg = mysqli_num_rows( $needmsg ); //считает количеств найденых записей
		if ($needmsg > 0) { // if row exist
			$needmsgboolean = true;
		} else $needmsgboolean = false;

		if ($needidmsg == 0) {	
		// output all
				$query = mysqli_query($conn,"SELECT id, nickname, text FROM chat");
				echo json_encode($query->fetch_all(MYSQLI_ASSOC));
		} else if ($needidmsg != 0 && $needidmsg != null && $needmsgboolean == true ) {	
		// last msg
				$query = mysqli_query($conn,"SELECT id, nickname, text FROM chat WHERE id = '$needidmsg'");
				echo json_encode($query->fetch_all(MYSQLI_ASSOC));
		} else  {	
		// if havent new msg
				$query = array('id' => "1", 'time' => "12:14:35", 'nickname' => "unracer", 'msg' => "No_raw_messages"); 
				// $query = "[{"id":"1","time":"12:14:35","nickname":"unracer","msg":"No_raw_messages"}]"
				echo json_encode($query);
		};
	}

	function orderCreate(){
		global $conn;
		if (!isset($_GET["nickname"]) && !isset($_GET["progress"])) {
			if (!isset($_GET["id_goods"]) && !isset($_GET["name_goods"])){
				if (!isset($_GET["point_meeting"]) && !isset($_GET["time_meeting"])){
					if (!isset($_GET["date_reg"]) && !isset($_GET["date_arrival"])){
						echo "plz send args";
		 				exit(); 
					}
				}
			}
		}

		$nickname = $_GET['nickname'];
		$id_goods = $_GET['id_goods'];
		$name_goods = $_GET['name_goods']; // for check
		$point_meeting = $_GET['point_meeting'];
		$time_meeting = $_GET['time_meeting'];
		$date_reg = $_GET['date_reg'];
		$date_arrival = $_GET['date_arrival'];
		$progress = $_GET['progress'];

		$query = "INSERT INTO orders (nickname, id_goods, name_goods, point_meeting, time_meeting, date_reg, date_arrival, progress) VALUES('$nickname', '$family', '$id_goods', '$name_goods', '$point_meeting', '$time_meeting', '$date_reg', '$date_arrival', '$progress') ";

		if (mysqli_query($conn, $query)) {
			echo json_encode("success");
		} else {
			echo json_encode("error");
		}	
	}

	function orderGet(){
		global $conn;
		$query = mysqli_query($conn,"SELECT * FROM orders"); // сразу берем нужное сообщение (может его и нет вовсе)
		echo json_encode($query->fetch_all(MYSQLI_ASSOC));
	}

	function userCreate(){
		global $conn;
		if (!isset($_GET["nickname"]) && !isset($_GET["rating"])){
			if (!isset($_GET["quantityGoods"])){
				echo "plz send args";
				exit(); 
			}
		}

		$nickname = $_GET['nickname'];
		$rating = $_GET['rating'];
		$quantityGoods = $_GET['quantityGoods'];

		$query = "INSERT INTO users (nickname, rating, quantityGoods) VALUES ('$nickname', '$rating', '$quantityGoods')";
		if (mysqli_query($conn, $query)) {
			echo json_encode("success");
		} else {
			echo json_encode("error");
		}
	}

	function userGet(){
		global $conn;
		$query = mysqli_query($conn,"SELECT * FROM users"); // сразу берем нужное сообщение (может его и нет вовсе)
		echo json_encode($query->fetch_all(MYSQLI_ASSOC));
	}

	function userUpdate(){
		global $conn;
		if (!isset($_GET["nickname"]) && !isset($_GET["rating"])){
			if (!isset($_GET["quantityGoods"])){
				echo "plz send args";
				exit(); 
			}
		}

		$nickname = $_GET['nickname'];
		$rating = $_GET['rating'];
		$quantityGoods = $_GET['quantityGoods'];

		$query = "UPDATE users (nickname, rating, quantityGoods) VALUES ('$nickname', '$rating', '$quantityGoods')";
		if (mysqli_query($conn, $query)) {
			echo json_encode("success");
		} else {
			echo json_encode("error");
		}
	}

	function serviceGet(){
		global $conn;
		$query = mysqli_query($conn,"SELECT * FROM service"); // сразу берем нужное сообщение (может его и нет вовсе)
		echo json_encode($query->fetch_all(MYSQLI_ASSOC));
	}

	function addIpBlackList(){
		global $conn;
		if (!isset($_GET["ip"])) {
			echo "plz sent arg";
		 	exit();
		}

		$ip = $_GET["ip"];

		$query = "INSERT INTO blackList (ip) VALUES ('$ip')";

		if (mysqli_query($conn, $query)) {
			echo json_encode("success");
		}
		else {
			echo json_encode("error");
		}
	}
?>
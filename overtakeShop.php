<?php
error_reporting(0); 

// улыбаемся и принимаем только GET запросы, пацы
// p.s.я никогда так еще не ошибался, чесн слово

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

		if (!mysql_query($conn, $query)) {
			echo json_encode("error");
		}
	}

	function goodsGet(){
		global $conn;
		$query; 

		if (isset($_GET["goods"])){
			$goods = $_GET["goods"];

			// dangerouse 1
			// $query = "SELECT * FROM goods WHERE title='" . $goods . "'";

			// should be
			// $query = "SELECT * FROM goods WHERE title='-1'UNION(SELECT*FROM(goods)WHERE(title='vodka'))#'";

			// exploit
			// -1'UNION(SELECT*FROM(goods)WHERE(title='vodka'))%23

			// dangerouse 2
			// $query = "SELECT*FROM(goods)WHERE(title='" . $goods . "')";

			// should be
			// $query = "SELECT*FROM(goods)WHERE(title='-1')UNION(SELECT*FROM(goods)WHERE(title='vodka'))#')";

			// exploit
			// -1')UNION(SELECT*FROM(goods)WHERE(title='vodka'))%23

			// normal?
			$query = "SELECT * FROM goods WHERE title='$goods'";

			// should be
			// $query = "SELECT * FROM goods WHERE title='-1'UNION(SELECT*FROM(goods)WHERE(title='vodka'))#'";

			// exploit
			// -1'UNION(SELECT*FROM(goods)WHERE(title='vodka'))%23

			// карочь суть в том что бы сначала создать запрос который будет корректно работать, а потом из него вытащить частичку,которую вставишь

			$query = mysqli_query($conn, $query);
		} else {
			$query = mysqli_query($conn,"SELECT * FROM goods");
		}

		if (!$query){
			echo mysqli_error($conn);
		}
		echo json_encode($query->fetch_all(MYSQLI_ASSOC));
	}

	function msgCreate(){
		global $conn;

		if (!isset($_GET["nickname"]) && !isset($_GET["msg"])) {
			if (!isset($_GET["timeMsg"])) {
				echo "plz sent arg";
		 		exit();
			} 
		}

		$timeMsg = $_GET["timeMsg"];
		$nickname = $_GET["nickname"];
		$msg = $_GET["msg"];

		$query = "INSERT INTO chat (timeMsg, nickname, msg) VALUES ('$timeMsg', '$nickname', '$msg')";

		if (!mysqli_query($conn, $query)) { echo json_encode("error"); }
	}

	function msgGet(){
		global $conn;
		$query;
		$msgExist;

		if (!isset($_GET["id"])) { echo " plz sent arg"; exit(); }

		// get msg
		$needMsg = $_GET["id"]; 

		if ($needMsg == 0) {	
		// output all
			$query = mysqli_query($conn,"SELECT * FROM chat");
			echo json_encode($query->fetch_all(MYSQLI_ASSOC));
		} else if ($needMsg != 0 && $needMsg != null) {	
		// last msg
			$query = mysqli_query($conn,"SELECT * FROM chat WHERE id = '$needMsg'");

			if (!$query) {
				// havent new msg
				$query = array('id' => "0", 'time' => "11:22:33", 'nickname' => "unracer", 'msg' => "no_raw_messages"); 
				echo json_encode($query);
			}

			echo json_encode($query->fetch_all(MYSQLI_ASSOC));
		}
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
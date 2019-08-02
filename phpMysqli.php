// Sanitize functions
// Make sanitizing easy and you will do it often

// Sanitize for HTML output 
function h($string) {
	return htmlspecialchars($string);
}

// Sanitize for JavaScript output
function j($string) {
	return json_encode($string);
}

// Sanitize for use in a URL
function u($string) {
	return urlencode($string);
}

// Getting the user IP address
function getIp() {
    $ip = $_SERVER['REMOTE_ADDR'];
 
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
 
    return $ip;
}
// creating the shopping cart
function cart(){
	global $connect;
	if (isset($_GET['add_cart'])) {
		$ip = getIp();

		$pro_id = $_GET['add_cart'];
		$check_pro = "select * from cart where ip_add='$ip' AND p_id='$pro_id'";
		$run_check = mysqli_query($connect, $check_pro);
		if (mysqli_num_rows($run_check)>0) {
			echo " Sorry!!! the Pet you selected already exist in your Cart." . "</br>" . "<a href='index.php' style='background-color:#9000ff; line-height:100px; color:#fff;'>Please go back and add another Pet. Thanks </a>";
		} else{
			$insert_pro = "insert into cart (p_id,ip_add) values ('$pro_id', '$ip')";
			$run_pro = mysqli_query($connect, $insert_pro);
			echo "<script> window.open('index.php','_self')</script>";
		}
	}
}
// Total item in the cart
function total_items() {
	if (isset($_GET['add_cart'])) {
		global $connect;
		$ip = getIp();
		$get_items = "select * from cart where ip_add='$ip'";
		$run_items = mysqli_query($connect, $get_items);
		$count_items = mysqli_num_rows($run_items);
	} else {
		global $connect;
		$ip = getIp();
		$get_items = "select * from cart where ip_add='$ip'";
		$run_items = mysqli_query($connect, $get_items);
		$count_items = mysqli_num_rows($run_items);
	}
	echo $count_items;
}

// Getting the total price
function total_price(){
	$total = 0;
	global $connect;
	$ip = getIp();
	$sel_price = "select * from cart where ip_add='$ip'";
	$run_price = mysqli_query($connect, $sel_price);
	while ($p_price = mysqli_fetch_array($run_price)) {
		$pro_id = $p_price['p_id'];
		$pro_price = "select * from pets_entry2 where id='$pro_id'";
		$run_pro_price = mysqli_query($connect, $pro_price);
		while ($pp_price = mysqli_fetch_array($run_pro_price)) {
			$pets_price = array($pp_price['price']);
			$values = array_sum($pets_price);
			$total += $values;
		}
	}
	echo "$ ". $total;
}
//  This file contains basic functions.

<!-- This function is used to encrypt passwords for security purposes -->
function password_encrypt($password){
		$hash_format = "$2y$10$"; // Tells PHP to use Blowfish with a "cost" of 10.
		$salt_length = 22; // Blowfish salts should be 22-characters or more.
		$salt = generate_salt($salt_length);
		$format_and_salt = $hash_format . $salt;
		$hash = crypt($password, $format_and_salt);
		return $hash;
	}
<!-- This function is used to salt passwords after hashing them. When salt is added to a password it makes decrypting it very difficult -->
	function generate_salt($length){
		// Not 100% unique, not 100% random, but good enough for a salt
		// MD5 returns 32 characters
		$unique_random_string = md5(uniqid(mt_rand(), true));

		// Valid characters for a salt are [a-zA-Z0-9./]
		$base64_string = base64_encode($unique_random_string);

		// But not '+' which is valid in base64 encoding
		$modified_base4_string = str_replace('+', '.' , $base64_string);

		// Truncate string to the correct length
		$salt = substr($modified_base4_string, 0, $length);

		return $salt;
	}

<!-- It compares the password the user submits with the one in the database like during logging in -->
	function password_check($c_pass, $existing_hash){
		// existing hash contains format and salt at start
		$hash = crypt($c_pass, $existing_hash);
		if ($hash === $existing_hash) {
			return true;
		} else{
			return false;
		}
	}
<!-- Trys to log a user using email and password by checking password using the password_check function above	 -->
	function attempt_login($s_email, $s_pass){
		$customer = find_customer_by_email($s_email);
		if ($customer) {
			// Found customer, now check password
			if (password_check($s_pass, $customer["c_pwd"])) {
				return $customer;
			} else {
				// Password does not match
				return false;
			}
		} else {
			// customer not found
			return false;
		}
	}

function find_staff_by_name($s_name){
	global $connect;

	$safe_s_name = mysqli_real_escape_string($connect, $s_name);

	$query  = "SELECT * FROM ";
	$query .= "adminlog ";
	$query .= "WHERE adm_name = '{$safe_s_name}' ";
	$query .= " LIMIT 1";
	$staff_set = mysqli_query($connect, $query); 
	confirm_query($staff_set);
	if($staff = mysqli_fetch_assoc($staff_set)){
		return $staff;
	}else{
		return null;
	}
}
function attempt_login_staff($s_name, $s_pass){
		$staff = find_staff_by_name($s_name);
		if ($staff) {
			// Found customer, now check password
			if (password_check($s_pass, $staff["adm_pwd"])) {
				return $staff;
			} else {
				// Password does not match
				return false;
			}
		} else {
			// customer not found
			return false;
		}
	}
<!-- when you log in it sets your session to your name so that anything you which to do in the site you wont have to log in and it will know its you	 -->
	function logged_in(){
		return isset($_SESSION["username"]);
	}

<!--  IF you want to access a particular page that needs you to be logged in, this function first checks if you have logged in and if not it sends you to login page-->
	function confirm_logged_in(){
		if (!logged_in()) {
			echo "<script>alert('Sorry! You have to Log In to gain access.')</script>";
			$_SESSION['message'] = "Sorry! You have to Log In to gain access.";
			echo "<script>window.open('login.php', '_self')</script>";
			//redirect_to("customer_login.php");
		}
	}

// Set timezone
  date_default_timezone_set("UTC");
 
  // Time format is UNIX timestamp or
  // PHP strtotime compatible strings
  function dateDiff($time1, $time2, $precision = 6) {
    // If not numeric then convert texts to unix timestamps
    if (!is_int($time1)) {
      $time1 = strtotime($time1);
    }
    if (!is_int($time2)) {
      $time2 = strtotime($time2);
    }
 
    // If time1 is bigger than time2
    // Then swap time1 and time2
    if ($time1 > $time2) {
      $ttime = $time1;
      $time1 = $time2;
      $time2 = $ttime;
    }
 
    // Set up intervals and diffs arrays
    $intervals = array('year','month','day','hour','minute','second');
    $diffs = array();

<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/header.php"); 
// -------------------------------------------------------------------------------------

if(isset($_SESSION["email"])) {
	header("Location: /");
}

if(isset($_POST["Submit"])) {
	if(empty($_POST["email"])) { die("you did not input an email"); }
	if(empty($_POST["password"])) { die("you did not input a password"); }
	
	$email = $_POST["email"];
	$password = sha1($_POST["password"].$website["sha1_salt"]);
	
	$sql = "SELECT * FROM users WHERE email='$email'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) { 
			$id = $row["id"]; 
			$password_db = $row["password"]; 
			$isBanned = $row["isBanned"];
			$screen_name = $row["screen_name"];
		}
		
		if($password == $password_db and $isBanned == 1) {
			die("This account has been suspended.");
		} else {
			$_SESSION["id"] = $id;
			$_SESSION["email"] = $email;
			$_SESSION["screen_name"] = $screen_name;
			// Update last login date
			$lastLogin = date('Y-m-d H:i:s');
			$stmt = $conn->prepare("UPDATE users SET last_login=? WHERE email=?");
			$stmt->bind_param("ss", $lastLogin, $email);
			$stmt->execute();
			header("Location: /");
		}
		} else {
			die("user does not exist");
		}
		$conn->close();
	}
	
?>

	<h1>Log In</h1>

	<table>
		<tr>
			<td id="Hint">
				<p>Have you <a href="forgot.gne">forgotten your password</a>?</p>
			<img src="/images/spaceball.gif" alt="spacer image" width="160" height="1">			</td>			<td id="GoodStuff">
  
 
				<form action="login.php" method="post">
				<table>
					<tr>
						<td>Email:</td>
						<td><input type="text" class="input" name="email"  size="40" value="" id="first_field" /></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="password" class="input" name="password" /></td>
					</tr>
					<!--
					<tr>
						<td>&nbsp;</td>
						<td><input type="checkbox" name="remember_me" value="1" /> Remember me on this computer.</td>
					</tr>
					-->
					<tr>
						<td>&nbsp;</td>
						<td><input name="Submit" type="submit" class="Butt" value="GET IN THERE"></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>Or, <a href="./">return to the home page</a>.</td>
					</tr>
				</table>
				</form>
			</td>
		</tr>
	</table>

<script language="Javascript">
<!--
document.getElementById('first_field').focus();
//-->
</script>


	<br clear="all" />

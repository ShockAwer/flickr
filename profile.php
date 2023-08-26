<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/header.php"); 

if(!isset($_GET["id"])) { die("No user ID set."); }
$_GET["id"] = (int) $_GET["id"]; // better cast to int

$stmt = $conn->prepare("SELECT * FROM users WHERE id=:t0");
$stmt->bindParam(':t0', $_GET["id"]);
$stmt->execute();
foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $user);

if($stmt->rowCount() == 0) {
	die("user does not exist");
}
	
$Now = new DateTime($user->last_login);

if(substr($user->screen_name, -1) == "s") { 
	$sn_display = htmlspecialchars($user->screen_name) . "'"; 
} else {
	$sn_display = htmlspecialchars($user->screen_name) . "'s";
}

$Now = new DateTime($user->last_login);
?>

	<h1><img src="<?php echo $user->display_picture; ?>" alt="buddy icon" width="48" height="48" border="0" align="absmiddle" class="xBuddyIconH"><?php echo htmlspecialchars($user->screen_name); ?></h1>
	<?php if($user->isBanned == 1) {
			echo "<p class=\"Problem\" style=\"margin-top: 30px; margin-left: 60px;\">This person is no longer active on " . $website["instance_name"] . "</p>";
			die(require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/footer.php"));
	}
	?>
	<table>
		<tr>
			<td id="Hint">			
			<h2 class="PhotosTease" style="font-weight: bold;"><a href="/profile_photos.php?id=<?php echo $_GET["id"]; ?>"> <?php echo $sn_display; ?> photos</a></h2>
			<?php
			if(!isset($_SESSION["id"])) {
				echo "<table class=\"Tease\" cellspacing=\"0\" cellpadding=\"0\">
					<tr>
						<td><span style=\"font-weight: bold; color:#FF6699;\">PSST!</span> To make your own profile, just choose a screen name...</td>
					</tr>
					<tr>
						<td valign=\"bottom\"><input name=\"username\" type=\"text\" size=\"12\">&nbsp;<a href==\"/register.php\"><img src=\"/images/button_go_up_small.gif\" style=\"border:none;\" alt=\"Get your screen name!\"></a></td>
					</tr>
					<tr>
						<td class=\"Member\">(Already a member? <a href=\"/login.php\" title=\"Log in to " . $website["instance_name"] . "\">Log in</a>.)
					</tr>
				</td>
			</tr>
		</table>
	</form>";
			}
			
			$stmt = $conn->prepare("SELECT * FROM photos WHERE uploaded_by=:t0 ORDER by id DESC LIMIT 1");
			$stmt->bindParam(':t0', $_GET['id']);
			$stmt->execute();
			if($stmt->rowCount() > 0) {
				foreach($stmt->fetchAll(PDO::FETCH_OBJ) as $photo) {
					echo "<div class=\"ToDoProfile\" style=\"text-align: center;\"><a href=\"/gallery_view.php?id=" . $_GET["id"] . "\"><img src=\"/photos/". $photo->id . ".t.jpg\" alt=\"Click to view gallery\" style=\"margin-bottom:10px; border: solid 1px #000000\"><br /><strong>View Gallery</strong></a></div>";
				}
			}
			?>
			<img src="/images/spaceball.gif" alt="spacer image" width="160" height="1">
			</td>
			<td id="GoodStuff">
			<?php if(isset($user->about)) {
				echo "<p>" . nl2br(htmlspecialchars($user->about)) . "</p>";
			}
			?>
			<!-- <p>I'm <strong>Other</strong>.</p> -->
			
			<p>
					<span class="NotLoggedIn"><?php echo htmlspecialchars($user->screen_name); ?> is not in <?php echo $website["instance_name"]; ?>Live right now.</span><br />
					<span class="DateTime">(Last recorded sighting: <?php echo ordinal($Now->format("d")) . " " . $Now->format("F Y"); ?>)</span> 
			</p>
			<p>
				<?php
				if(isset($user->site_addr)) {
					echo "<a href=\"" . htmlspecialchars($user->site_addr) . "\">";
					if(isset($user->site_name)) {
						echo "<strong>". htmlspecialchars($user->site_name) . "</strong></a><br>";
					} else {
						echo "<strong>". htmlspecialchars($user->site_addr) . "</strong></a><br>";
					}
				}
				
				echo htmlspecialchars($user->off_city . ", " . $user->off_country);
				?>
			</p>
				
			<?php if(!empty($user->off_hometown || $user->off_occ || $user->things_interests || $user->things_books || $user->things_movies || $user->things_music)) {
				echo "<h3>A bit more about ". $user->screen_name . "...</h3>";
				echo "<table>";
				
					if(isset($user->off_hometown)) {
						echo "<tr>
							<td>Hometown:</td>
							<td>" . htmlspecialchars($user->off_hometown) . "</td>
						</tr>";
						}
						
					if(isset($user->off_occ)) {
						echo "<tr>
							<td>Occupation:</td>
							<td>" . htmlspecialchars($user->off_occ) . "</td>
						</tr>";
						}

					if(isset($user->things_interests)) {
						echo "<tr>
							<td>Interests:</td>
							<td>" . htmlspecialchars($user->things_interests) . "</td>
						</tr>";
						}
						
					if(isset($user->things_books)) {
						echo "<tr>
							<td>Favorite Books & Authors:</td>
							<td>" . htmlspecialchars($user->things_books) . "</td>
						</tr>";
						}

					if(isset($user->things_movies)) {
						echo "<tr>
							<td>Favorite Movies, Stars & Directors:</td>
							<td>" . htmlspecialchars($user->things_movies) . "</td>
						</tr>";
						}

					if(isset($user->things_music)) {
						echo "<tr>
							<td>Favorite Music & Artists:</td>
							<td>" . htmlspecialchars($user->things_music) . "</td>
						</tr>";
						}

				echo "</table>
				";
			}
			?>
	</table>
	
	<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/incl/footer.php"); ?>
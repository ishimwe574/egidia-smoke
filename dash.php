<?php
session_start();

/* ================= LOGIN ================= */
if(isset($_POST['login'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if($user == "ishimwe" && $pass == "1234"){
        $_SESSION['user'] = $user;
    } else {
        $error = "Invalid username or password!";
    }
}

/* ================= LOGOUT ================= */
if(isset($_GET['logout'])){
    session_destroy();
    header("Location: dash.php");
    exit();
}

/* ================= DATABASE ================= */
$conn = new mysqli("localhost","root","","sensor_db");

if($conn->connect_error){
    die("Connection Failed");
}

$latest = $conn->query("SELECT * FROM sensor_data ORDER BY id DESC LIMIT 1");
$row = $latest->fetch_assoc();

$history = $conn->query("SELECT * FROM sensor_data ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title> IoT Dashboard</title>

<style>
body{
    background:#081330;
    font-family:Arial;
    color:white;
    margin:0;
    padding:20px;
}

.login-box{
    width:300px;
    margin:100px auto;
    background:black;
    padding:20px;
    border-radius:10px;
    text-align:center;
}

input{
    width:90%;
    padding:10px;
    margin:10px 0;
}

button{
    padding:10px 20px;
    background:green;
    color:white;
    border:none;
    cursor:pointer;
}

.container{
    width:95%;
    margin:auto;
}

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}

.card{
    background:black;
    padding:20px;
    border-radius:15px;
}

.value{
    font-size:40px;
    color:#00ff00;
}

.camera img{
    width:100%;
    max-width:700px;
    border-radius:15px;
}

.logout{
    position:absolute;
    top:20px;
    right:20px;
    background:red;
    padding:10px 20px;
    color:white;
    text-decoration:none;
    border-radius:10px;
}
</style>
</head>

<body>

<?php if(!isset($_SESSION['user'])): ?>

<!-- ================= LOGIN FORM ================= -->
<div class="login-box">
    <h2>Login</h2>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>

        <button type="submit" name="login">Login</button>
    </form>

    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
</div>

<?php else: ?>

<!-- ================= LOGOUT ================= -->
<a class="logout" href="?logout=1">Logout</a>

<div class="container">

<h1 style="text-align:center;color:red;">IOT DASHBOARD</h1>

<!-- ================= CARDS ================= -->
<div class="cards">

<div class="card">
<h3>Temperature</h3>
<div class="value">
<?php echo $row['temperature']." °C"; ?>
</div>
</div>

<div class="card">
<h3>Smoke</h3>
<div class="value">
<?php echo $row['smoke']; ?>
</div>
</div>

<div class="card">
<h3>Distance</h3>
<div class="value">
<?php echo $row['distance']." cm"; ?>
</div>
</div>

<div class="card">
<h3>Status</h3>
<div class="value">
<?php
if($row['distance'] < 50){
    echo "<span style='color:red'>DANGER</span>";
} else {
    echo "<span style='color:lime'>SAFE</span>";
}
?>
</div>
</div>

</div>

<!-- ================= CAMERA ================= -->
<div class="camera" style="text-align:center;margin-top:30px;">
<h2>Camera Live</h2>

<img src="http://192.168.137.122:81/stream">
</div>

<!-- ================= TABLE ================= -->
<table border="1" width="100%" style="margin-top:30px;text-align:center;">
<tr>
<th>ID</th>
<th>Temp</th>
<th>Smoke</th>
<th>Distance</th>
<th>Time</th>
</tr>

<?php while($data = $history->fetch_assoc()): ?>
<tr>
<td><?= $data['id'] ?></td>
<td><?= $data['temperature'] ?> °C</td>
<td><?= $data['smoke'] ?></td>
<td><?= $data['distance'] ?> cm</td>
<td><?= $data['created_at'] ?></td>
</tr>
<?php endwhile; ?>

</table>

</div>

<?php endif; ?>

</body>
</html>
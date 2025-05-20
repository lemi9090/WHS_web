<?php
include "./db_conn.php";
if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}

$ppstm = $conn->prepare("INSERT INTO users(user_id, user_pw, email_adr) VALUES (?, ?, ?)");

$user_id = $_POST['user_id'];
$user_pw = $_POST['password'];  // 🔄 해시 없이 평문 저장
$email_adr = $_POST['email'];


if (preg_match('/[\'";#-]/', $user_id) || preg_match('/[\'";#-]/', $user_pw) || preg_match('/[\'";#-]/', $email_adr)) {
    echo "<script>alert('Please don\\'t try that.');</script>";
    exit;
}

$ppstm->bind_param("sss", $user_id, $user_pw, $email_adr);

if ($ppstm->execute()) {
    echo '<script>
            alert("Welcome to our site.");
            location.href = "index.php";
          </script>';
} else {
    echo "Registration failed: " . mysqli_error($conn);
}

$ppstm->close();
$conn->close();
?>

<?php
include '/var/www/html/db_conn.php';

session_start();

if (!isset($_SESSION['name'])) {
    echo "<script>alert('Abnormal access detected. Please log in again.'); window.location.href='../../index.php';</script>";
    exit();
}

$current_user = $_SESSION['name'];

$post_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_SESSION['post_id']) ? $_SESSION['post_id'] : 0);


if (isset($_SESSION['current_post_id'])) {
  $post_id = $_SESSION['current_post_id']; // 세션값에서 게시물 선택 
}

$board_id = isset($_SESSION['board_id']) ? $_SESSION['board_id'] : 0;

  if (isset($_SESSION['id'])) {
    $post_id = $_SESSION['post_id']; // 세션값에서 댓글 선택 
  }

  if (isset($_SESSION['post_id'])) {
    $post_id = $_SESSION['post_id']; // 세션값에서 댓글 선택 
  }



$sql = "DELETE FROM free_bulletin WHERE id = ? AND board_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $post_id, $board_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo '<script>alert("success");</script>';
    echo '<script>history.back();</script>';
} else {
    echo '<script>alert("fail.");</script>';
    echo '<script>history.back();</script>';
}

$stmt->close();
$conn->close();
?>

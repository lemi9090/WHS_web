<?php
include '/var/www/html/db_conn.php';

session_start();

if (!isset($_SESSION['name'])) {
    echo "<script>alert('Abnormal access detected. Please log in again.'); window.location.href='../../index.php';</script>";
    exit();
}


$username = $_SESSION['name'];
$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

if (isset($_SESSION['board_id'])) {
    $boardid = $_SESSION['board_id'];  // 세션에서 board_id 가져오기
  }


if ($post_id == 0) {
    echo "<script>alert('Invalid access.'); history.back();</script>";
    exit();
}

// 게시물 작성자 확인
$check_sql = "SELECT writer, file_path FROM free_bulletin WHERE id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("i", $post_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    echo "<script>alert('Post does not exist.'); history.back();</script>";
    exit();
}

$check_row = $check_result->fetch_assoc();
if (strcasecmp(trim($check_row['writer']), trim($username)) !== 0) {
    echo "<script>alert('You do not have permission to modify this post.'); history.back();</script>";
    exit();
}

$title = htmlspecialchars($_POST['post_title'], ENT_QUOTES, 'UTF-8'); //xss 공격 방지
$content = htmlspecialchars($_POST['post_content'], ENT_QUOTES, 'UTF-8');

$upload_file = $check_row['file_path']; // 기본 파일 경로는 기존 파일로 설정

if (!empty($_FILES['SelectFile']['name'])) {
    $tmpfile = $_FILES['SelectFile']['tmp_name'];
    $o_name = $_FILES['SelectFile']['name'];

    $baseDir = '/var/www/html/bulletin/contents/';
    $uploadDir = $baseDir . 'upload/';

    $userFolderName = preg_replace("/[^a-zA-Z0-9]+/", "_", $username); //파일 트래버셜 공격 방지
    $titleFolderName = preg_replace("/[^a-zA-Z0-9]+/", "_", $title);
    $userDir = $uploadDir . $userFolderName;
    $titleDir = $userDir . '/' . $titleFolderName;

    // 각 디렉터리가 있는지 확인
    if (!is_dir($userDir) && !mkdir($userDir, 0777, true)) {
        error_log("Failed to create directory with username." . error_get_last()['message']);
        echo '<script>alert("Failed to create directory with username."); history.back();</script>';
        exit;
    }

    if (!is_dir($titleDir) && !mkdir($titleDir, 0777, true)) {
        error_log("Failed to create directory with post title." . error_get_last()['message']);
        echo '<script>alert("Failed to create directory with post title."); history.back();</script>';
        exit;
    }

    //최종 디렉터리 (유저 이름 + 게시물 제목)
    $upload_file = $titleDir . '/' . basename($_FILES['SelectFile']['name']); //파일 디렉토리 공격 방지
    
    $image_type = $_FILES['SelectFile']['type'];
    // if ($image_type != "image/png" && $image_type != "image/jpeg") {
    //     echo '<script>alert("Only PNG and JPG files are allowed."); history.back();</script>';
    //     exit;
    // }

    if (!move_uploaded_file($_FILES['SelectFile']['tmp_name'], $upload_file)) {
        echo '<script>alert("File upload failed."); history.back();</script>';
        exit;
    }

    
}

$update_sql = "UPDATE free_bulletin SET subject = ?, contents = ?, file_path = ? WHERE id = ?";
if ($title && $content) {
    $ppstm = $conn->prepare($update_sql);
    $ppstm->bind_param("sssi", $title, $content, $upload_file, $post_id); //sql 인젝션 방지
    if ($ppstm->execute()) {
        //$board_id = $_SESSION['board_id'];  // 세션에서 board_id 값을 가져옴
        switch ($board_id) {
            case 1:
                header('Location: ../free_bulletin.php'); 
                break;
            case 2:
                header('Location: ../new_bulletin.php'); 
                break;
            default:
                header('Location: ../dictionary.php'); 
                break;
        }
        exit();
    } else {
        echo "<script>
      alert('Failed to modify the post.');
      history.back();</script>";
    }
}
$ppstm->close();
$conn->close();
?>

<?php
include '/var/www/html/db_conn.php';

session_start();

if (!isset($_SESSION['name'])) {
    echo "<script>alert('Abnormal access detected. Please log in again.'); window.location.href='../../index.php';</script>";
    exit();
}

if (isset($_SESSION['board_id'])) {
    $boardid = $_SESSION['board_id'];
} elseif (isset($_POST['board_id'])) {
    $boardid = htmlspecialchars($_POST['board_id'], ENT_QUOTES, 'UTF-8'); // 세션에서 board_id 가져오기
}else {
    // board_id가 없을 경우의 처리
    echo "<script>alert('board_id not found'); history.back();</script>";
    exit();
}


$ppstm = $conn->prepare("INSERT INTO free_bulletin(subject, contents, writer, file_path, board_id) VALUES (?, ?, ?, ?, ?)");
$username = $_SESSION['name'];
$title = htmlspecialchars($_POST['post_title'], ENT_QUOTES, 'UTF-8'); // XSS 공격 방지
$content = htmlspecialchars($_POST['post_content'], ENT_QUOTES, 'UTF-8');
$date = date('Y-m-d');

$file_path = ''; // 파일 경로 초기화


// 파일이 업로드된 경우 처리
if (isset($_FILES['SelectFile']) && $_FILES['SelectFile']['error'] != UPLOAD_ERR_NO_FILE) {
    $tmpfile = $_FILES['SelectFile']['tmp_name'];
    $o_name = $_FILES['SelectFile']['name'];
    $image_type = $_FILES['SelectFile']['type'];

    $baseDir = '/var/www/html/bulletin/contents/';
    $uploadDir = $baseDir . 'upload/';

    $userFolderName = preg_replace("/[^a-zA-Z0-9]+/", "_", $username) ?: "default_user";
    $titleFolderName = preg_replace("/[^a-zA-Z0-9]+/", "_", $title) ?: "default_title";
    $userDir = $uploadDir . $userFolderName;
    $titleDir = $userDir . '/' . $titleFolderName;

    if (!is_dir($userDir) && !mkdir($userDir, 0777, true)) {
        error_log("Failed to create user directory!" . error_get_last()['message']);
        echo '<script>alert("Failed to create user directory!"); history.back();</script>';
        exit();
    }

    if (!is_dir($titleDir) && !mkdir($titleDir, 0777, true)) {
        error_log("Failed to create title directory!" . error_get_last()['message']);
        echo '<script>alert("Failed to create title directory!"); history.back();</script>';
        exit();
    }

    // 최종 디렉터리 (유저 이름 + 게시물 제목)
    $upload_file = $titleDir . '/' . basename($_FILES['SelectFile']['name']); // 파일 디렉토리 공격 방지

    if (move_uploaded_file($_FILES['SelectFile']['tmp_name'], $upload_file)) {
        $image_type = $_FILES['SelectFile']['type'];
        if ($image_type == "image/png" || $image_type == "image/jpeg") {
          $file_path = $upload_file; 
          } else {
                echo '<script>
                    alert("Only PNG and JPG files are allowed.");
                    history.back();</script>'; 
                exit();
            }

    } else {
        echo '<script>alert("File upload failed: ';
        switch ($_FILES['SelectFile']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                echo 'The uploaded file exceeds the upload_max_filesize (' . ini_get("upload_max_filesize") . ') directive in php.ini.';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                echo 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
                break;
            case UPLOAD_ERR_PARTIAL:
                echo 'The uploaded file was only partially uploaded.';
                break;
            case UPLOAD_ERR_NO_FILE:
                echo 'No file was uploaded.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                echo 'Missing a temporary folder.';
                break;
            default:
                echo 'An unknown error occurred.';
                break;
        }
        echo '"); history.back();</script>';
        exit();
    }
}

// 게시물 작성
if ($username && $title && $content) {
  $ppstm->bind_param("ssssi", $title, $content, $username, $file_path, $boardid); // SQL 인젝션 방지
  if ($ppstm->execute()) {
        
        switch ($boardid) {
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
  }else {
      echo "<script>
      alert('Failed to create the post. Error: " . $ppstm->error . "');
      history.back();</script>";
  }
} else {
  echo "<script>
  alert('Please fill in all fields.');
  history.back();</script>";
}

$ppstm->close();
$conn->close();
?>



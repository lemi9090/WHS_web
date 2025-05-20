<?php
    session_start();
    include "./db_conn.php";

    $id = trim($_POST['input_id']); // 공백 제거 후 입력 받음
    $pw = $_POST['input_pw'];
    if (strlen($id) < 8 || strlen($pw) < 8) { 
        echo "<script>
            alert(\"check your id or pwd\");
            history.back();
          </script>";
        exit;
    }
    
    if (preg_match('/[\'";#-]/', $id) || preg_match('/[\'";#-]/', $pw)) {
        echo "<script>alert('pleas don't try');</script>";
        exit;
    }

    $ppstm = $conn->prepare("SELECT * FROM users WHERE user_id=?");  //sql 방지를 위한 바인딩 작업
    $ppstm->bind_param("s", $id);
    $ppstm->execute();
    $result = $ppstm->get_result();

    if (!$result) {
        error_log("SQL Error: " . mysqli_error($conn)); // SQL 에러 로깅
    }

    if($result->num_rows==0){
        echo "<script> 
        alert(\"User not found.\");
        history.back();
        </script>";
        exit;
    }else{
        $row = $result->fetch_assoc(); // fetch_assoc함수는 행의 이름을 키값으로 데이터를 value로 할당
        if ($pw != $row['user_pw']) {
            echo "<script>
                    alert(\"Incorrect password.\");
                    history.back();
                </script>";
            exit;
        } else {
            session_regenerate_id(true);
            $_SESSION['name'] = $row['user_id'];
            mysqli_close($conn);
            header("Location: main.php");
            exit();
        }
    }
?>

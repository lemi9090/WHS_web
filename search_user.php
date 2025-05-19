<?php
session_start();
include "./db_conn.php";

$searchUser = trim($_POST["search_user"]);

if (strlen($searchUser) > 30) {
    alertAndBack(" Input value is too long."); // alertBack 에 참조대로 아래 메시지 변수에서 출력
}

// if (preg_match('/[\'";#-]/', $searchUser) ) {
//     echo "<script>alert('pleas don't try');</script>";
//     exit;
// }

$sql = "SELECT * FROM users WHERE user_id = '$searchUser'";
$result = $conn->query($sql);
// $ppstm = $conn->prepare("SELECT * FROM users WHERE user_id=?");
// $ppstm->bind_param("s", $searchUser);

// if (!$ppstm->execute()) {
//     error_log("SQL Error: " . mysqli_error($conn));
//     alertAndBack("시스템 오류가 발생했습니다.");
// }

// $result = $ppstm->get_result();

// 결과가 있을 경우 전체 행을 출력

if ($result->num_rows == 0) {
    alertAndBack("No matching user ID found.");
} elseif ($result->num_rows == 1) {
    // 정확한 user_id 검색만 해당됨
    $row = $result->fetch_assoc();
    if ($row['user_id'] === $searchUser) {
        alertAndBack("User exists.");
    } else {
        // 이건 SQLi로 한 줄만 나오는데 user_id가 다를 경우 → SQLi 탐지로 전체 출력
        displayRawDump($result);
    }
} else {
    // 여러 줄이면 SQLi 성공으로 판단 → 정보 노출
    displayRawDump($result);
}

function displayRawDump($result) {
    echo "<h2>Search Results</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>User ID</th><th>Password</th><th>Email</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['user_pw']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email_adr']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<br><a href='javascript:history.back()'>Go Back</a>";
    exit;
}

function alertAndBack($message) {
    echo "<script>
            alert(\"$message\");
            history.back();
          </script>";
    exit;
}
?>
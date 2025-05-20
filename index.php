<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #fdfaf5; /* 밝은 배경 */
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
            padding-top: 50px;
        }

        img#logo {
            width: 180px;
            margin-bottom: 20px;
        }

        .form-container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            margin: 0 auto;
            width: 300px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 8px;
            border: none;
            background-color: #ffbb55;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #ffaa33;
        }
    </style>
</head>

<body>
    <!-- 삽입된 이미지 -->
    <img id="logo" src="img/soar.png" alt="TungTung Soar Logo">

    <form action="login_sql.php" method="POST" id="input_info" class="form-container">
        <div>
            <label for="input_id">ID</label>
            <input type="text" name="input_id" id="input_id" placeholder="Input your ID">
        </div>

        <div>
            <label for="input_pw">Password</label>
            <input type="password" name="input_pw" id="input_pw" placeholder="Input your password">
        </div>

        <button type="submit" id="Login_button">Login</button>
        <button type="button" id="Signup_button">Register</button>
    </form>

    <script>
        const LoginButton = document.querySelector('#Login_button');
        const SignupButton = document.querySelector('#Signup_button');
        const Inputid = document.querySelector('#input_id');
        const Inputpw = document.querySelector('#input_pw');

        LoginButton.addEventListener("click", function (e) {
            if (!Inputid.value || !Inputpw.value) {
                alert("Please enter both ID and password.");
                e.preventDefault();
            }
        });

        SignupButton.addEventListener("click", function () {
            window.location.href = 'rgt_user.html';
        });
    </script>
</body>
</html>

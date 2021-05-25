<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTf-8">
        <link rel="shortcut icon" type="image/icon" href="<?php echo base_url(); ?>/icons/logo.png">
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        
    </head>
    <body>
        <div class="wrapper">
            <img src="<?php echo base_url(); ?>/icons/logo.png" alt="web logo">
            <form method="POST" class="cred" action="<?php echo base_url(); ?>index.php/home/auth">
                <input type="text" name="username" placeholder="Username">
                <input type="password" name="password" placeholder="Password">
                <input type="submit" value="Login">
			</form>
			<a href="">Register Me</a>
        </div>
    </body>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            background-size: cover;
            font-family: Poppins;
        }

        .wrapper {
            width: 400px;
            height: 420px;
            color: #000;
            top: 50%;
            left: 50%;
            padding: 60px 30px;
            position: absolute;
            transform: translate(-50%,-50%);
            box-sizing: border-box;
            box-shadow: 8px 8px 50px rgb(94, 92, 92);
            border-radius: 10px;
        }

        h1 {
            margin: 0;
            padding: 0;
            font-weight: bold;
            font-size: 22px;
            color: #000;
            text-align: center;
            margin-bottom: 8%;
            font-family: Courgette;
        }

        img {
            position: absolute;
            width: 200px;
            height: 150px;
            left: 30%;
        }

        .wrapper input {
            width: 100%;
            margin-bottom: 20px;
        }

        .wrapper input[type=text], .wrapper input[type=password] {
            border: none;
            border-bottom: 1px solid rgb(148, 143, 143);
            background: transparent;
            outline: none;
            height: 30px;
            font-size: 16px;
            opacity: 1;
            color: #000;
        }

        .wrapper input[type=submit] {
            border: none;
            outline: none;
            height: 40px;
            background: #f6648b;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            border-radius: 10px;
        }

        .wrapper input[type=submit]:hover {
            cursor: pointer;
        }

        .wrapper a {
            font-size: 18px;
            text-decoration: none;
            color: #2d589c;
            opacity: 0.8;
        }

        .wrapper a:hover {
            color: #254373;
            opacity: 1;
        }

        .cred {
            margin-top: 160px;
        }
    </style>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sorry, it's 404</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
            font-size: 14px;
            text-align: center;
        }
        .wrapper{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        h1 {
            font-size: 50px;
            margin: 0;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <a href="/">
        <?php include(DOCROOT . "public/app/svg/codex-logo.svg") ?>
    </a>
    <h1>404</h1>
</div>
</body>
</html>

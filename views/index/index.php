<html>
<head>
    <meta charset="utf-8" /> 
    <link rel="stylesheet" type="text/css" href="/e-betyg/resources/css/main.css" />
    <title>
        E-Betyg
    </title>
<head>
<body>
    <div id="page">
        <div id="header"><img src="/e-betyg/resources/img/header.png" /></div>
        <div id="nav"></div>
        
        <div id="text_left_content">
            <h1>
                <?php echo $this->_M->h1;?>
            </h1>

                <?php echo $this->_M->p; ?>
            <p><a href="Account">Logga in</a></p>
        </div>
        <div id="text_right_content">
            <h2>Logga in:</h2>
            <form method="post" action="Account/SignIn" >
                <label>E-post</label>
                <input type="text" name="user" />
                <label>Lösenord</label>
                <input type="password" name="pass" />
                <input type="submit" name="submit" value="Logga in" />
            </form>
        </div>
    </div>
</body>
</html>

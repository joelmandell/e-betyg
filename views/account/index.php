<!DOCTYPE html>
<html dir="ltr" lang="sv">
<head>
    <meta charset="utf-8" /> 
    <link rel="stylesheet" type="text/css" href="/e-betyg/resources/css/main.css" />
    <title>
        E-Betyg
    </title>
</head>
<body>
    <div id="page">
        <div id="header"><img src="/e-betyg/resources/img/header.png" /></div>
        <div id="nav">
            <ul>
                
                <li><a href="/e-betyg/">Start</a></li>
                <li><a href="#">Registrera</a></li>
                
                <?php
                    if($user->InvokedPriviligies && $user->GroupName=="ADMIN")
                    {
                        echo $_M->edit;
                    }

                    if($auth->IsAuth())
                    {
                        echo $_M->logout;
                    }
                ?>
                
            </ul>
        </div>

        <div id="text_left_content">
            <?php 
                echo $_M->h1;
            ?>
            <?php 
                if(!$auth->isAuth())
                {
                    echo $_M->not_logged_in;
                }
            ?>
            <p>
                <?php                      
                echo $_M->register_success;            
                ?>
            </p>
        </div>
        
    </div>
</body>
</html>

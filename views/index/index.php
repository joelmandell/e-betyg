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
        <div id="header"><img src="/e-betyg/resources/img/header.jpg" /></div>
        <div id="nav">
            <ul>
                
                <li><a href="#">Start</a></li>
                <?php
                echo $auth->IsAuth() ? "" : $_M->register;
                ?>
                <?php
                echo $auth->IsAuth() ? $_M->upload : "";
                ?>
                <?php
                echo $auth->IsAuth() ? $_M->account : "";
                ?>
                <?php
                echo $auth->IsAuth() ? $_M->doc : "";
                ?>
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
            <div id="login_status">
                <?php
                    echo $auth->IsAuth() ? $_M->user : "";
                ?>
            </div>
        </div>
        
        <div id="text_left_content">
            <?php echo $_M->h1; ?>
            <?php echo $_M->p; ?>
        </div>
        
        <div id="text_right_content">          
            <?php
            if(!$auth->IsAuth())
            {
               echo $_M->login_form; 
            }
            ?>
            <div id="form_msg">
            <?php
               echo $auth->IsAuth() ? "" : $_M->msg;            
            ?>
            </div>
        </div>  
    </div>
    <div id="foot">
        <div id="foot_content">
            <p>Copyright Joel Mandell 2013-2014</p>
        </div>
    </div>       
</body>
</html>

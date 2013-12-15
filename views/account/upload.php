<!DOCTYPE html>
<html dir="ltr" lang="sv">
<head>
    <meta charset="utf-8" /> 
    <link rel="stylesheet" type="text/css" href="/e-betyg/resources/css/main.css" />
    <title>
        E-Betyg - Ladda upp dokument.
    </title>
</head>
<body>
    <div id="page">
        
        <div id="header">
            <img src="/e-betyg/resources/img/header.png" />
        </div>
        
        <div id="nav">
            <ul>
                
                <li><a href="/e-betyg/">Start</a></li>
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
                    if($user->InvokedPriviligies && $user->GroupName=="ADMIN")
                    {
                        echo $_M->edit;
                        
                    } else if($user->InvokedPriviligies) {
                        
                    }

                    if($auth->IsAuth())
                    {
                        echo $_M->logout;
                    }
                ?>
                
            </ul>
        </div>
        
        <div id="text_left_content">
            <?php echo $_M->h1; ?>
            <?php echo $_M->p; ?>
            <?php
                if($user->InvokedPriviligies)
                {
                    echo $_M->upload_view;
                }
            ?>
            <?php echo $_M->file_input; ?>
            <?php echo $_M->file_choice; ?>
            <?php echo $_M->progress; ?>
            <?php echo $_M->usermessage; ?>
            <?php echo $_M->groupPublic; ?>
            <?php echo $_M->send; ?>

        </div>
        
        <div id="text_right_content">          
            <?php
            if(!$auth->IsAuth())
            {
               echo $_M->login_form; 
            } else {
                if($user->InvokedPriviligies)
                {
                    echo $_M->upload_options;
                }
            }
            ?>
            <div id="form_msg">
            <?php
               echo $auth->IsAuth() ? $_M->msg : "";            
            ?>
            </div>
        </div>
       
    </div>   
    <div id="foot">
        <div id="foot_content">
        <a href="http://github.com/joelmandell"><img id="social" src="/e-betyg/resources/img/Octocat.png" /></a><a href="http://plus.google.com/+joelmandell"><img id="social" src="/e-betyg/resources/img/gplus-64.png" /></a><a href="http://twitter.com/dikatlon"><img id="social" src="/e-betyg/resources/img/Twitter_logo_white.png" /></a>
        <p>Copyright Joel Mandell 2013-2014</p>
        </div>
    </div>
    <?php
    echo $js;
    ?>
</body>
</html>
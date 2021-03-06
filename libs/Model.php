<?php

//Need to have this $is_called, because
//our framework always has an empty Model skeleton
//in case of user not having instantiated a custom Model.
//And in the Model construct code we look for data that
//has been passed to Model in previuos site
//so that data is alive for one redirection.
$is_called=0;

class Model {

    private $data = array();
    public $auth, $group, $db, $user, $jslibs;
    
    function __construct(&$db, &$auth, &$group) {
   
        global $is_called;
        $this->jslibs=new ArrayObject();

        if($is_called==1)
        {
            //Iterate through $_SESSION variable.
            foreach($_SESSION as $name => $s)
            {
                //Find data passed to our model from previuos redirect.
                if(preg_match('/Model\:(.*)/i', $name, $result)===1)
                {
                    //Dynamically set a variable in the standard Model
                    //wich later will pass the data to our View.
                    $this->$result[1]=$s;
                    
                    //We don't need this data anymore, so remove this
                    //session variable.
                    $_SESSION[$name]=NULL;
                    unset($_SESSION[$name]);
                }
            }
            $is_called=0; //Reset this counter.
        }
        
        $is_called++;
        isset($_SESSION["user"]) ? $this->user=$_SESSION["user"] : $this->user=new User();
        $this->auth=$auth;
        $this->db=$db;
    }
    
    public function addJsLibrary($file)
    {
        global $base_uri;
        $this->jslibs[]=$base_uri."/resources/js/".$file;
    }
    
    public function createLink($controller,$linkName)
    {
        global $base_uri;

        return "<a href=".$base_uri."/".$controller.">".$linkName."</a>";
    }
    
    public function __get($key) {
        return isset($this->data[$key]) ? $this->data[$key] : "";
    }
    
    function __set($key,$value)
    {
        $this->data[$key] = $value;
    }
}
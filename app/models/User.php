<?php

/**
* 
*/
class User extends Model
{
    
    public $username;
    public $password;
    private $rows;

    public function __construct()
    {

    }

    public function login()
    {
        $this->checkSession();

        $this->db = new Database();
        $this->db->connect();

        $escaped_username = $this->db->escapestring($this->username);
        $this->username = $this->db->stripSlashes($escaped_username);

        $escaped_password = $this->db->escapestring($this->password);
        $this->password = $this->db->stripSlashes($escaped_password);
        
        $this->checkLogin();

        if(isset($_SESSION['loggedin'])){
            return true;
        } else {
            return false;
        }

    }

    private function checkLogin()
    {
        $sql = 'SELECT * FROM users WHERE username = "'. $this->username .'"';
        $this->db->sql($sql);
        $this->res = $this->db->getResult();
        $this->rows = $this->db->numRows();

        if($this->rows == 1){  
            $this->logUserIn();
        } else {
            echo "<strong>Incorrect username <br>";
        }
    }

    private function logUserIn()
    {
        $sql = 'SELECT * FROM users WHERE username = "'. $this->username .'" AND password = "' . $this->password . '"';
        $this->db->sql($sql);
        $this->res = $this->db->getResult();
        $this->rows = $this->db->numRows();

        if ($this->rows == 1) {
            $_SESSION['username'] = $this->username;
            $_SESSION['userfname'] = $this->res[0]['firstname'];
            $_SESSION['userlname'] = $this->res[0]['lastname'];
            $_SESSION['usertitle'] = $this->res[0]['title'];
            $_SESSION['usertype'] = $this->res[0]['user_type'];
            $_SESSION['loggedin'] = true;
        }
        else {
            echo "<strong>Incorrect password.<br/>";
        }
        $this->db->disconnect();
        $this->resetResDb();
    }

    private function checkSession()
    {
        if(session_id() == '' || !isset($_SESSION)) {
            session_start();
        }
    }

}

?>
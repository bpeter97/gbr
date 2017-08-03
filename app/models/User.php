<?php

/**
* 
*/
class User extends Model
{
    
    private $username,
                $password,
                $firstname,
                $lastname,
                $title,
                $type;
    
    public function getUsername() { return $this->username; }
    public function getPassword() { return $this->password; }
    public function getFirstname() { return $this->firstname; }
    public function getLastname() { return $this->lastname; }
    public function getTitle() { return $this->title; }
    public function getType() { return $this->type; }
    
    public function setUsername($username) { $this->username = $username; }
    public function setPassword($password) { $this->password = $password; }
    public function setFirstname($firstname) { $this->firstname = $firstname; }
    public function setLastname($lastname) { $this->lastname = $lastname; }
    public function setTitle($title) { $this->title = $title; }
    public function setType($type) { $this->type = $type; }
    
    public function __construct()
    {
        $this->db = Database::getDBI();
    }
    
    //@TODO Code the get user info function.
    public function getUserInfo()
    {
        
    }

    public function login()
    {

        $this->checkLogin();

        if(isset($_SESSION['loggedin'])){
            return true;
        } else {
            return false;
        }

    }

    private function checkLogin()
    {
        
        $sql = 'SELECT * FROM users WHERE username = ?';
        $this->db->query($sql, array($this->getUsername()));
        $results = $this->db->results('arr');
        $rows = $this->db->count();

        if($rows == 1){  
            $this->logUserIn();
        } else {
            echo "<strong>Incorrect username <br>";
        }
    }

    private function logUserIn()
    {
        $sql = 'SELECT * FROM users WHERE username = "'. $this->getUsername() .'" AND password = "' . $this->getPassword() . '"';
        $this->db->query($sql);
        $results = $this->db->single();
        $rows = $this->db->count();

        if ($rows == 1) {
            $_SESSION['username'] = $this->getUsername();
            $_SESSION['userfname'] = $results->firstname;
            $_SESSION['userlname'] = $results->lastname;
            $_SESSION['usertitle'] = $results->title;
            $_SESSION['usertype'] = $results->user_type;
            $_SESSION['loggedin'] = true;
        }
        else {
            echo "<strong>Incorrect password.<br/>";
        }
    }
}

?>
<?php

/**
* 
*/
class User extends Model
{
    
    private $username,
                $id,
                $password,
                $firstname,
                $lastname,
                $phone,
                $title,
                $type;
    
    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getPassword() { return $this->password; }
    public function getFirstname() { return $this->firstname; }
    public function getLastname() { return $this->lastname; }
    public function getPhone() { return $this->phone; }
    public function getTitle() { return $this->title; }
    public function getType() { return $this->type; }
    
    public function setId($id) { $this->id = $id; }
    public function setUsername($username) { $this->username = $username; }
    public function setPassword($password) { $this->password = $password; }
    public function setFirstname($firstname) { $this->firstname = $firstname; }
    public function setLastname($lastname) { $this->lastname = $lastname; }
    public function setPhone($phone) { $this->phone = $phone; }
    public function setTitle($title) { $this->title = $title; }
    public function setType($type) { $this->type = $type; }
    
    public function __construct($id = null)
    {
        $this->db = Database::getDBI();
        
        if($id != null){
            $this->setId($id);
            $this->getUserInfo($this->getId());
        } else {
            $this->setId(null);
        }
    }
    
    //@TODO Code the get user info function.
    public function getUserInfo($id = null)
    {
        $sql = 'SELECT * FROM users WHERE userid = ?';
        
        if($id != null) {
            $this->db->query($sql,array($id));
        } else {
            $this->db->query($sql,array($this->id));
        }
            
            $res = $this->db->single();
            
            $this->setId($res->userid);
            $this->setUsername($res->username);
            $this->setPassword($res->password);
            $this->setFirstname($res->firstname);
            $this->setLastname($res->lastname);
            $this->setPhone($res->phone);
            $this->setTitle($res->title);
            $this->setType($res->type);
        
    }
    
        // Function to count the containers for pagination.
    public function countUsers($where = '')
    {
        $row = '';
        $new_where = '';

        if($where != ''){
            $new_where = 'WHERE '. $where .' ';
        }
        $this->db->query('SELECT COUNT(userid) FROM users '. $new_where);
        $res = $this->db->results('arr');

        foreach($res as $count){
            $row = $count['COUNT(userid)'];
        }

        return $row;
    }

    // Function to grab containers depending on params.
    public function fetchUsers($where = '',$limit = '')
    {
        $list = array();

        $new_where = '';
        if($where != ''){
            $new_where = 'WHERE '. $where .' ';
        }

        $sql = 'SELECT * FROM users ' . $new_where . $limit;
        $this->db->query($sql);
        $res = $this->db->results('arr');
        
        foreach ($res as $user) {
            array_push($list, new User($user['userid']));
        }

        return $list;
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
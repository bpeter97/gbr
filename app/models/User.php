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
    private $employees = array();
    
    public function getId() { return $this->id; }
    public function getUsername() { return $this->username; }
    public function getPassword() { return $this->password; }
    public function getFirstname() { return $this->firstname; }
    public function getLastname() { return $this->lastname; }
    public function getPhone() { return $this->phone; }
    public function getTitle() { return $this->title; }
    public function getType() { return $this->type; }
    public function getEmployees() { return $this->employees; }
    
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
            $this->getDB()->query($sql,array($id));
        } else {
            $this->getDB()->query($sql,array($this->id));
        }
            
            $res = $this->getDB()->single();
            
            $this->setId($res->userid);
            $this->setUsername($res->username);
            $this->setPassword($res->password);
            $this->setFirstname($res->firstname);
            $this->setLastname($res->lastname);
            $this->setPhone($res->phone);
            $this->setTitle($res->title);
            $this->setType($res->user_type);
        
    }
    
        // Function to count the containers for pagination.
    public function countUsers($where = '')
    {

        $row = '';
        $new_where = '';

        if($where != ''){
            $new_where = 'WHERE '. $where .' ';
        }
        $this->getDB()->query('SELECT COUNT(userid) FROM users '. $new_where);
        $res = $this->getDB()->results('arr');

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
        $this->getDB()->query($sql);
        $res = $this->getDB()->results('arr');
        
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
        $this->getDB()->query($sql, array($this->getUsername()));
        $results = $this->getDB()->results('arr');
        $rows = $this->getDB()->count();

        if($rows == 1){  
            $this->logUserIn();
        } else {
            echo "<strong>Incorrect username <br>";
        }
    }

    private function logUserIn()
    {

        $sql = 'SELECT * FROM users WHERE username = "'. $this->getUsername() .'" AND password = "' . $this->getPassword() . '"';
        $this->getDB()->query($sql);
        $results = $this->getDB()->single();
        $rows = $this->getDB()->count();

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

    public function update()
    {
        $this->setUsername($_POST['frmuname']);
        $this->setPassword($_POST['frmupassword']);
        $this->setFirstname($_POST['frmufirstname']);
        $this->setLastname($_POST['frmulastname']);
        $this->setPhone($_POST['frmuphone']);
        $this->setTitle($_POST['frmutitle']);
        $this->setType($_POST['frmutype']);


        $res = $this->getDB()->update('users',['userid'=>$this->getId()],[
                                    'username'      =>  $this->getUsername(),
                                    'password'      =>  $this->getPassword(),
                                    'firstname'     =>  $this->getFirstname(),
                                    'lastname'      =>  $this->getLastname(),
                                    'phone'         =>  $this->getPhone(),
                                    'title'         =>  $this->getTitle(),
                                    'user_type'     =>  $this->getType()
                                    ]);
        
        if(!$res)
        {
            throw new Exception("The user was not updated.");
        }
    }

    public function delete()
    {

        // Delete the user from the database.
		$res = $this->getDB()->delete('users',['userid'=>$this->getId()]);
        
        // Check to see if the query ran properly.
        if(!$res)
        {
            throw new Exception('The product was not deleted from the user.');
        }
    }

    public function create()
    {

        $this->setUsername($_POST['frmuname']);
        $this->setPassword($_POST['frmupassword']);
        $this->setFirstname($_POST['frmufirstname']);
        $this->setLastname($_POST['frmulastname']);
        $this->setPhone($_POST['frmuphone']);
        $this->setTitle($_POST['frmutitle']);
        $this->setType($_POST['frmutype']);


        $res = $this->getDB()->insert('users',[
                        'username'      =>  $this->getUsername(),
                        'password'      =>  $this->getPassword(),
                        'firstname'     =>  $this->getFirstname(),
                        'lastname'      =>  $this->getLastname(),
                        'phone'         =>  $this->getPhone(),
                        'title'         =>  $this->getTitle(),
                        'user_type'     =>  $this->getType()
			            ]);

		if(!$res)
		{
			throw new Exception('There was an error inserting the product into the database!');
		}
    }

    public function fetchEmployees($type)
    {

        // Get the list of employee's based on the type.
        $res = $this->getDB()->select('users',['title'=>$type]);

        if($res)
        {
            // Grab the statement results.
            $results = $this->getDB()->results('arr');

            // For each employee found, add them to the employees list.
            foreach ($results as $emp) {
                $employee = new User($emp['userid']);
                array_push($this->employees, $employee);
            }
        }
    }

}

?>
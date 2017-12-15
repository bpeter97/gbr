<?php

if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class User extends CI_Model
{
    private $_id,
            $_username,
            $_password,
            $_first_name,
            $_last_name,
            $_phone,
            $_title,
            $_type;
    private $_employees = array();

    public function get_id() { return $this->_id; }
    public function get_username() { return $this->_username; }
    public function get_password() { return $this->_password; }
    public function get_first_name() { return $this->_first_name; }
    public function get_last_name() { return $this->_last_name; }
    public function get_phone() { return $this->_phone; }
    public function get_title() { return $this->_title; }
    public function get_type() { return $this->_type; }

    public function set_id($id) { $this->_id = $id; return $this; }
    public function set_username($name) { $this->_username = $name; return $this; }
    public function set_password($pass) { $this->_password = $pass; return $this; }
    public function set_first_name($name) { $this->_first_name = $name; return $this; }
    public function set_last_name($name) { $this->_last_name = $name; return $this; }
    public function set_phone($phone) { $this->_phone = $phone; return $this; }
    public function set_title($title) { $this->_title = $title; return $this; }
    public function set_type($type) { $this->_type = $type; return $this; }

    public function __construct($id = NULL)
    {
        parent::__construct();
        
        if( ! $id === NULL )
        {
            $this->set_user_data($this->get_user_info($id));
        }
    }

    public function get_user_info($id = NULL)
    {
        if( ! $id === NULL )
        {
            // Return user info based on supplied ID.
            return $this->db->get_where('users', ['id' => $id])->result();
        }
        else
        {
            // Return user info based on user model ID.
            return $this->db->get_where('users', ['id' => $this->get_id()])->result();
        }
    }

    public function set_user_data($user)
    {
        // Use setters to setup this objects properties using the object passed in.
        if(is_array($user))
        {
            $this->set_id($user['id'])
                 ->set_username($user['username'])
                 ->set_password($user['password'])
                 ->set_first_name($user['firstname'])
                 ->set_last_name($user['lastname'])
                 ->set_phone($user['phone'])
                 ->set_title($user['title'])
                 ->set_type($user['type']);
        } 
        elseif(is_object($user))
        {
            // Use object parameters.
            $this->set_id($user->id)
                 ->set_username($user->username)
                 ->set_password($user->password)
                 ->set_first_name($user->firstname)
                 ->set_last_name($user->lastname)
                 ->set_phone($user->phone)
                 ->set_title($user->title)
                 ->set_type($user->type);
        }
        else
        {
            // Throw an error
            throw new Exception('The variable passed in is not an object or an array.');
        }
    }

    public function count_users()
    {
        return $this->db->get('users')->num_rows();
    }

    // if( ! $id = $this->user->check_login() )
    public function check_login($username, $password)
    {
        // Select the user based off of the username input.
        $result = $this->db->get_where('users', array('username' => $username));
        
        // If there was a result->
        if($result)
        {
            // Check to see if user's password matches input password.
            if(password_verify($password, $result->row(2)->password))
            {
                // Return user ID if it does.
                return $result->row(0)->id;
            } 
            else 
            {
                // Return FALSE if it does not match!
                return FALSE;
            }
        } 
        else 
        {
            // Return FALSE if there was no result! (meaning username did not match!)
            return FALSE;
        }
    }

    public function create_user_session()
    {
        
    }
}

?>
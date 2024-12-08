<?php

require_once 'config/database.php';

class User {
    private $id, $username, $password, $role_id;

    // public function __construct(
    //     $username,
    //     $password,
    //     $role_id
    // )
    // {
    //     $this->username=$username;
    //     $this->password=$password;
    //     $this->role_id=$role_id;
    // }

    public static function auth($username, $password)
    {
        try{
            global $pdo;
            $sql = "SELECT * FROM users WHERE username='" . $username . "'";
            $query = $pdo->query($sql);
            $user = $query->fetchAll(PDO::FETCH_CLASS, 'User');

            if(count($user) == 0){
                $_SESSION["username"] = $username;
                $_SESSION["error"] = "$username has not registered!";

                header("location: /login");
                die;
            }


            if (password_verify($password, $user[0]->password)){
                $_SESSION['is_login'] = true;
                $_SESSION['username'] = $username;

                header("location: /membership");
                die;
            }

            $_SESSION["error"] = "WRONG PASSWORD!";
            header('location: /login');
            die;

        } catch (PDOException $e) {
            echo $user . "<br/>" . $e->getMessage();
        }
    } 
    
    public function registerUser()
    {
        global $pdo;
        $sql = "INSERT INTO users (username, password, role_id) VALUES ('$this->username', '$this->password', '$this->role_id')";

        try{
            $pdo->exec($sql);
            echo "Register Success !";
        }catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
}
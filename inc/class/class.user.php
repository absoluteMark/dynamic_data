<?php
/**
 * Created by PhpStorm.
 * User: markslater
 * Date: 12/28/17
 * Time: 10:56 AM
 */

namespace App\Scoreboard;

use \PDO;  // <--- need by PhpStorm to find Methods of PDO

class USER
{
    /**
     * @var PDO   <--- need by PhpStorm to find Methods of PDO
     */

    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function register($fname,$lname,$umail,$upass)
    {
        try
        {
            $new_password = password_hash($upass, PASSWORD_DEFAULT);

            $stmt = $this->db->prepare("INSERT INTO users(user_fname, user_lname,user_email,user_pass) 
                                                       VALUES(:fname, :lname, :umail, :upass)");

            $stmt->bindparam(":fname", $fname);
            $stmt->bindparam(":lname", $lname);
            $stmt->bindparam(":umail", $umail);
            $stmt->bindparam(":upass", $new_password);
            $stmt->execute();

            return $stmt;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    public function login($uname,$umail,$upass)
    {
        try
        {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE user_name=:uname OR user_email=:umail LIMIT 1");
            $stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0)
            {
                if(password_verify($upass, $userRow['user_pass']))
                {
                    $_SESSION['user_session'] = $userRow['user_id'];
                    $_SESSION['user_fname'] = $userRow['user_fname'];
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    public function is_loggedin()
    {
        if(isset($_SESSION['user_session']))
        {
            return true;
        }
    }

    public function redirect($url)
    {
        header("Location: $url");
    }

    public function logout()
    {
        session_destroy();
        unset($_SESSION['user_session']);
        return true;
    }
}
?>
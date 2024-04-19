<?php session_start();
require_once "User.php";
require_once "mappers/UserMapper.php";

class UserHandler
{
    public static function login($redirect) : bool
    {
        if (isset($_POST['email']) &&  filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        {
            require_once 'src/models/User.php';
            require_once 'src/models/mappers/UserMapper.php';
            $mapper = new UserMapper();
            if ($mapper->isExistByEmail($_POST['email'] ))
            {
                echo "hello";
                $user = $mapper->getByEmail($_POST['email']);
                if ($user && hash_equals($user->getPassword(),hash('sha256',$_POST["password"])))
                {
                    unset($_SESSION["user"]);
                    $userData = [
                        "user_id" => $user->getUserId(),
                        "password" => $user->getPassword(),
                        "email" => $user->getEmail(),
                        "is_admin" => $user->isAdmin(),
                    ];
                    $_SESSION["user"] = $userData;
                    header('Location: '.$_SERVER['HTTPS'].$redirect);
                    die;
                }
            }
        }
        return false;
    }

    public static function isLoggedIn() : bool
    {
        return isset($_SESSION["user"]);
    }
    public static function logout($redirect) : void
    {
        if (isset($_SESSION["user"]))
        {
            unset($_SESSION["user"]);
        }
        header('Location: '.$_SERVER['HTTPS'].$redirect);
        die;
    }
    public static function update() : bool
    {
        $userMapper = new UserMapper();
        $user_from_db = $userMapper->get($_SESSION['user']['user_id']);
        if (hash('sha256',$_POST["current_password"]) == $user_from_db->getPassword())
        {
            $userMapper->update(new User($_SESSION['user']['user_id'],$_POST['email'],hash('sha256',$_POST["new_password"]),true));
            return true;
        }
        return false;
    }
    public static function isAdmin() : bool
    {
        if (isset($_SESSION["user"])) {
            if ($_SESSION['user']['is_admin'])
            {
                return true;
            }
        }
        return false;
    }
}
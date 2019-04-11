<?php 
    function checkLoginToken($stmt) {
        
        if (!isset($_COOKIE['login_token']) || $_COOKIE['login_token'] == "") {
           
            return false;
       
        } else {

            $stmt->prepare("SELECT `id` FROM `users` WHERE `token` = ?");
            $stmt->bind_param("s", $_COOKIE['login_token']);
            $stmt->execute();
            $result = $stmt->get_result();

            if (mysqli_num_rows($result) == 1) {
                
                $_user = mysqli_fetch_array($result);

                $_SESSION['user_id'] = $_user['id'];
                $_SESSION['token'] = $_COOKIE['login_token'];

                return true;
           
            } else {
           
                return false;
           
            }
        }
    }
?>
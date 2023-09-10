<?php


use Defuse\Crypto\KeyProtectedByPassword;

class Register
{

    private Database $db;
    private Login $login;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->login = new Login;
    }

    /**
     * Register user
     * @param string $username User's username
     * @param string $email Email of the user
     * @param string $password Password of the user
     * @param string $confirm_password Retyped password of the user
     * @return bool|void
     */
    public function userRegister(string $username, string $email, string $password, string $confirm_password){

        $username = trim($username);
        $email = trim($email);
        $password = trim($password);
        $confirm_password = trim($confirm_password);


        if($this->login->checkUsername($username)){
            echo "Username is already exists";
            return;
        }

        if($this->login->checkEmail($email)){
            echo "Email is already exists";
            return;
        }

        if ($password !== $confirm_password){
            echo "Password doesnt match";
            return;
        }
        

        try {

            $hashed_password = password_hash($confirm_password, PASSWORD_DEFAULT);

            // generate confirmation code
            $confirmation_code = Utils::generateKey();

            $status = (EMAIL_CONFIRMATION)  ? '0' : '1';

            $protected_key_encoded = Encryption::createKey($password);


            $sql = "INSERT INTO `users` (`username`, `email`, `password`, `status`, `confirmation_key`, `secret`) VALUES (:username, :email, :password, :status, :confirmation_key, :secret)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(":confirmation_key", $confirmation_code, PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->bindParam(":secret", $protected_key_encoded, PDO::PARAM_STR);
            if($stmt->execute()){
                    return true;
                }

        } catch(Exception $e){
            echo "Error: ". $e;
        }


    }


    public function confirmAccount(string $key){

        try {

            $sql = "SELECT * FROM `users` WHERE `confirmation_key` = :key";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':key', $key, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $sql = "UPDATE `users` SET `status` = '1' WHERE `confirmation_key` = :key";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':key', $key, PDO::PARAM_STR);
                if($stmt->execute()) {

                    return true;

                } else {
                    echo 'There must be an error confirming your account!';
                }
            } else {
                echo 'Oops! Sorry, the key with this user does not exist.';

            }

        } catch (Exception $e){
            echo "Error: " . $e;
        }



    }


}

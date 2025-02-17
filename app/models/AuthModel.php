<?php

class AuthModel extends Model
{
    public function checkCredential($email, $password)
    {
        $sql = "SELECT
                users.id,
                users.prefix,
                users.firstname,
                users.lastname,
                users.email,
                users.roles,
                users.position,
                divisions.division_id,
                divisions.division_name,
                divisions.division_abbr,
                users.active,
                users.last_login,
                users.created_date,
                users.updated_date 
            FROM
                users
                LEFT JOIN divisions ON users.division = divisions.division_id 
            WHERE users.email = :email AND users.password = :password";
        $data = array(
            'email' => $email,
            'password' => md5($password),
        );
        $stmt = $this->Rdb->prepare($sql);
        $this->bind($stmt, $data);
        $stmt->execute();
        $count = $stmt->rowCount();

        if ($count > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
}

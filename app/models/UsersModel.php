<?php

class UsersModel extends Model
{
    public function getUsersData()
    {
        $sql = "SELECT users.id, users.prefix, users.firstname, users.lastname, users.email, divisions.division_abbr, users.active
                FROM users
                LEFT JOIN divisions
                ON users.division = divisions.id
                WHERE users.active = 1
                ";
        try {
            $stmt = $this->Rdb->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getUserById($id)
    {
        $sql = "SELECT users.id, users.prefix, users.firstname, users.lastname, users.email, divisions.division_abbr, users.active
                FROM users
                LEFT JOIN divisions ON users.division = divisions.division_id
                WHERE users.id = :id
                ";
        $data = array(
            'id' => $id
        );

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $data);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $stmt->debugDumpParams();
            exit;
            return $e->getMessage();
        }
    }

    public function updatePassword($id, $password)
    {
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $bind = array(
            'id' => $id,
            'password' => $password,
        );
        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo $stmt->debugDumpParams();
            exit;
            return $e->getMessage();
        }
    }

    public function getUsersByDivision($division)
    {
        $sql = "SELECT users.id, users.prefix, users.firstname, users.lastname, users.email, users.division, users.roles, users.active, divisions.division_name, divisions.division_abbr
                FROM users
                LEFT JOIN divisions ON users.division = divisions.division_id ";
        
        if(!User::isAdmin()) {
            $sql .= "WHERE users.division = :division";

            $bind = array(
                'division' => $division
            );
        }
       
        try {
            $stmt = $this->Rdb->prepare($sql);
            (!User::isAdmin()) ?? $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}

<?php

class DivisionsModel extends Model
{
    public function getDivisions() {
        $sql = "SELECT * FROM divisions";
        try {
            $stmt = $this->Rdb->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getDivisionById($id) {
        $sql = "SELECT * FROM divisions WHERE division_id = :id";
        $bind = [
            'id' => $id
        ];
        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

}

?>
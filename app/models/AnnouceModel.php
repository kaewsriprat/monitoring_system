<?php

class AnnouceModel extends Model
{

    public function get_annouces() {
        $sql = "SELECT * FROM annoucement WHERE active = 1 ORDER BY id";
        try {
            $stmt = $this->Rdb->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function get_active_annouce() {
        $sql = "SELECT * FROM annoucement WHERE active = 1 AND status = 1 ORDER BY pin DESC, id ASC";
        try {
            $stmt = $this->Rdb->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function new_annouce_post() {
        $sql = "INSERT INTO annoucement(title, detail, status, start_date, end_date, active) VALUES(:title, :detail, 0, :start_date, :end_date, 1)";
        $data = array(
            ':title' => $_POST['title'],
            ':detail' => $_POST['detail'],
            ':start_date' => $_POST['start_date'],
            ':end_date' => $_POST['end_date'],
        );

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $data);
            $stmt->execute($data);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function update_annouce_state() {
        $sql = "UPDATE annoucement SET status = :status WHERE id = :id";
        
        $data = array(
            ':status' => $_POST['status'],
            ':id' => $_POST['id'],
        );

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $data);
            $stmt->execute($data);
            return 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function update_annouce_pin() {
        $sql = "UPDATE annoucement SET pin = :pin WHERE id = :id";
        
        $data = array(
            ':pin' => $_POST['pin'],
            ':id' => $_POST['id'],
        );

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $data);
            $stmt->execute($data);
            return 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function update_annouce() {
        $sql = "UPDATE annoucement SET title = :title, detail = :detail, start_date = :start_date, end_date = :end_date WHERE id = :id";
        
        $data = array(
            ':title' => $_POST['title'],
            ':detail' => $_POST['detail'],
            ':start_date' => $_POST['start_date'],
            ':end_date' => $_POST['end_date'],
            ':id' => $_POST['id'],
        );

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $data);
            $stmt->execute($data);
            return 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function archive_annouce($id) {
        $sql ="UPDATE annoucement SET active = 0 WHERE id = :id";
        $data = array(
            ':id' => $id,
        );
        try{
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $data);
            $stmt->execute($data);
            return 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}

<?php

class QuarterModel extends Model
{
    public function get_quarters()
    {
        $sql = "SELECT * FROM quarter";

        try {
            $stmt = $this->Rdb->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function get_quarters_by_budget_year($budget_year)
    {
        $sql = "SELECT * FROM quarter WHERE budget_year = :budget_year";
        $data = array(
            ':budget_year' => $budget_year
        );
        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $data);
            $stmt->execute($data);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function new_quater_post($post_data) {
 
        if($this->check_existed_quarter($post_data['BudgetyearSelect'], $post_data['quarterSelect'])) {
            $this->update_quater_post($post_data);
        } else {
            echo "<script>alert('เพิ่มไตรมาสใหม่ ')</script>";
            $sql = "INSERT INTO quarter (budget_year, quarter, start_date, end_date) VALUES (:budget_year, :quarter, :start_date, :end_date)";
            $data = array(
                ':budget_year' => $post_data['BudgetyearSelect'],
                ':quarter' => $post_data['quarterSelect'],
                ':start_date' => $post_data['start_date'],
                ':end_date' => $post_data['end_date']
            );
            try {
                $stmt = $this->Rdb->prepare($sql);
                $stmt->execute($data);
                return 0;
            } catch (PDOException $e) {
                echo $e->getMessage();
                exit;
            }
        }

    }

    public function update_quater_post($post_data) {
        $sql = "UPDATE quarter SET budget_year = :budget_year, quarter = :quarter, start_date = :start_date, end_date = :end_date WHERE budget_year = :budget_year AND quarter = :quarter";
        $data = array(
            ':budget_year' => $post_data['BudgetyearSelect'],
            ':quarter' => $post_data['quarterSelect'],
            ':start_date' => $post_data['start_date'],
            ':end_date' => $post_data['end_date'],
            ':id' => $post_data['id']
        );
        try {
            $stmt = $this->Rdb->prepare($sql);
            $stmt->execute($data);
            return 0;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
    
    public function delete_quater_post($id) {
        
    }

    public function check_existed_quarter($budget_year, $quarter) {
        $sql = "SELECT * FROM quarter WHERE budget_year = :budget_year AND quarter = :quarter";
        $data = array(
            'budget_year' => $budget_year,
            'quarter' => $quarter
        );
        try{
            $stmt = $this->Rdb->prepare($sql);
            $stmt->execute($data);
            return ($stmt->rowCount() > 0) ? true : false;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

}

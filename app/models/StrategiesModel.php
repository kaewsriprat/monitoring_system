<?php

class StrategiesModel extends Model
{

    public function getStrategies($year){
        $sql = 'SELECT * FROM strategy_ops WHERE budget_year = :budget_year AND active = 1';

        $bind = [
            'budget_year' => $year
        ];

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print_r($stmt->debugDumpParams());
            exit;
        }
    }

    public function getStrategyById($id) {
        $sql = 'SELECT * FROM strategy_ops WHERE id = :id AND active = 1';

        $bind = [
            'id' => $id
        ];

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print_r($stmt->debugDumpParams());
            exit;
        }
    }

    public function newStrategy($data)
    {
        $sql = 'INSERT INTO strategy_ops (strategy_name, budget_year) VALUES (:strategy_name, :budget_year)';

        $bind = [
            'strategy_name' => $data['strategyName'],
            'budget_year' => $data['yearSelect'],
        ];

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            print_r($stmt->debugDumpParams());
            exit;
        }
    }

    public function updateStrategy($data)
    {
        $sql = 'UPDATE strategy_ops SET strategy_name = :strategy_name, budget_year = :budget_year WHERE id = :id';

        $bind = [
            'strategy_name' => $data['strategyName'],
            'budget_year' => $data['yearSelect'],
            'id' => $data['id'],
        ];

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            print_r($stmt->debugDumpParams());
            exit;
        }
    }

    public function deleteStrategy($id)
    {
        $sql = 'UPDATE strategy_ops SET active = 0 WHERE id = :id';

        $bind = [
            'id' => $id
        ];

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}

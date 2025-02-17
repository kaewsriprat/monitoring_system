<?php

class BudgetformModel extends Model
{
    public function getBudgetformReports()
    {
        $sql = "SELECT * FROM budget_form_reports";
        try {
            $stmt = $this->Rdb->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getBudgetformReportsByDivision($division)
    {
        $sql = "SELECT * FROM budget_form_reports 
        WHERE division = :division";

        $bind = [
            'division' => $division
        ];

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute($bind);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getFilteredFormReports($year, $division) {
        $sql = "SELECT budget_form_reports.*, divisions.division_name
        FROM budget_form_reports 
        LEFT JOIN divisions 
        ON budget_form_reports.division = divisions.division_id
        WHERE budget_year = :year";

        $bind = [
            'year' => $year,
        ];

        if ($division != 0) {
            $sql .= " AND division = :division";
            $bind['division'] = $division;
        }

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute($bind);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function submitReportYear($reportYear, $division)
    {
        $isValid = $this->verifyIfReportYearExists($reportYear, $division);
        if ($isValid) {
            return false;
        }
        $sql = "INSERT INTO budget_form_reports (budget_year, division) VALUES (:budget_year, :division)";
        $bind = [
            'budget_year' => $reportYear,
            'division' => $division
        ];

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute($bind);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function verifyIfReportYearExists($reportYear, $division)
    {
        $sql = "SELECT * FROM budget_form_reports WHERE budget_year = :budget_year AND division = :division";
        $bind = [
            'budget_year' => $reportYear,
            'division' => $division
        ];

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute($bind);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($row) ? true : false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function submitReportForm($id, $quarter, $file)
    {   

        $sql = "UPDATE budget_form_reports SET q{$quarter}_file_path = :file_path, q{$quarter}_file_update_date = CURRENT_TIMESTAMP() WHERE id = :id";
        $bind = [
            'file_path' => $file,
            'id' => $id
        ];

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute($bind);
            return [
                'status' => 'success',
                'message' => ''
            ];
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function delete($id, $quarter)
    {
        $sql = "UPDATE budget_form_reports SET q{$quarter}_file_path = NULL WHERE id = :id";
        $bind = [
            'id' => $id
        ];

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute($bind);
            return [
                'status' => 'success',
                'message' => ''
            ];
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}

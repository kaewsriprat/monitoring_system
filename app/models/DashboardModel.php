<?php

class DashboardModel extends Model
{
    public function getProjects($year, $division)
    {

        $sql = "SELECT SUM(total_budget) AS budget, COUNT(project.project_ID) AS countProject, SUM(CASE WHEN project.planned = 1 THEN 1 ELSE 0 END) AS countProjectPlanned,
        SUM(CASE WHEN 
        project.q1_file_path IS NOT NULL AND
        project.q2_file_path IS NOT NULL AND
        project.q3_file_path IS NOT NULL AND
        project.q4_file_path IS NOT NULL 
        THEN 1 ELSE 0 END) AS finishReported
        FROM projects project
        WHERE project.project_year = :year 
        AND project.active = 1 ";

        if ($division != null) {
            $sql .= "AND project.division = :division ";
        }

        $sql .= "ORDER BY project.project_ID DESC";

        $bind = [
            'year' => $year
        ];

        if ($division != null) {
            $bind['division'] = User::division();
        }

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getReportedProjects($year, $division = null)
    {
        $sql = "SELECT 
            SUM(CASE WHEN project.q1_file_path IS NOT NULL THEN 1 ELSE 0 END) AS q1_reported,
            SUM(CASE WHEN project.q1_file_path IS NULL THEN 1 ELSE 0 END) AS q1_pending,
            SUM(CASE WHEN project.q2_file_path IS NOT NULL THEN 1 ELSE 0 END) AS q2_reported,
            SUM(CASE WHEN project.q2_file_path IS NULL THEN 1 ELSE 0 END) AS q2_pending,
            SUM(CASE WHEN project.q3_file_path IS NOT NULL THEN 1 ELSE 0 END) AS q3_reported,
            SUM(CASE WHEN project.q3_file_path IS NULL THEN 1 ELSE 0 END) AS q3_pending,
            SUM(CASE WHEN project.q4_file_path IS NOT NULL THEN 1 ELSE 0 END) AS q4_reported,
            SUM(CASE WHEN project.q4_file_path IS NULL THEN 1 ELSE 0 END) AS q4_pending
            FROM projects project
            WHERE project.project_year = :year 
            AND project.active = 1 ";

        if ($division) {
            $sql .= "AND project.division = :division ";
        }

        $sql .= "GROUP BY project.project_year, project.division";

        $bind = [
            'year' => $year,
        ];

        if ($division) {
            $bind['division'] = User::division();
        }

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}

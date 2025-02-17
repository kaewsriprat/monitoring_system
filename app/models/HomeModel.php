<?php

class HomeModel extends Model
{
    public function getTopReported() {
        $budgetYear = Budgetyear::getBudgetyearThai();
        $sql = "SELECT 
            project.project_year,
            divisions.division_abbr,
            COUNT(project.project_ID) AS count_project,
              SUM(CASE WHEN project.q1_file_path IS NOT NULL THEN 1 ELSE 0 END) AS q1_reported,
            SUM(CASE WHEN project.q2_file_path IS NOT NULL THEN 1 ELSE 0 END) AS q2_reported,
            SUM(CASE WHEN project.q3_file_path IS NOT NULL THEN 1 ELSE 0 END) AS q3_reported,
            SUM(CASE WHEN project.q4_file_path IS NOT NULL THEN 1 ELSE 0 END) AS q4_reported,
            (
				(SUM(CASE WHEN project.q1_file_path IS NOT NULL THEN 1 ELSE 0 END) +
				SUM(CASE WHEN project.q2_file_path IS NOT NULL THEN 1 ELSE 0 END) +
				SUM(CASE WHEN project.q3_file_path IS NOT NULL THEN 1 ELSE 0 END) +
				SUM(CASE WHEN project.q4_file_path IS NOT NULL THEN 1 ELSE 0 END)) / (COUNT(project.project_ID) * 4)
			) * 100 AS percentile
            FROM projects project 
            LEFT JOIN divisions 
            ON project.division = divisions.division_id 
            WHERE project.project_year = $budgetYear
            AND project.division IS NOT NULL
            GROUP BY project.division, divisions.division_abbr
            ORDER BY count_project DESC
            LIMIT 5";

        try{
            $stmt = $this->Rdb->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}

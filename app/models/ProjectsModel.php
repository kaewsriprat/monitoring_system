<?php

class ProjectsModel extends Model
{

    public function getProjectById($id) {
        $sql = "SELECT project.*, strategy_ops.strategy_name, division.division_name, division.division_abbr
        FROM projects project 
        LEFT JOIN divisions division ON project.division = division.division_ID
        LEFT JOIN strategy_ops ON project.strategy_ops = strategy_ops.id
        WHERE project.project_ID = :id 
        AND project.active = 1";

        $bind = [
            'id' => $id
        ];

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

    public function getProjectsByDivision($year, $division)
    {
        $sql = "SELECT project.*, division.division_name, division.division_abbr 
        FROM projects project 
        LEFT JOIN divisions division ON project.division = division.division_ID
        WHERE project.project_year = :year 
        AND project.active = 1 ";

        if($division != 0) {
            $sql .= "AND project.division = :division ";
        }
        
        $sql .= "ORDER BY project.project_ID DESC";

        $bind = [
            'year' => $year
        ];

        if($division != 0) {
            $bind['division'] = $division;
        }

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getProjectsTableData($year, $division, $strategy)
    {
        $sql = "SELECT project.*, division.division_name, division.division_abbr 
        FROM projects project 
        LEFT JOIN divisions division ON project.division = division.division_ID
        WHERE project.project_year = :year 
        AND project.active = 1 ";

        if($division != 0) {
            $sql .= "AND project.division = :division ";
        }

        if($strategy != 0) {
            $sql .= "AND project.strategy_ops = :strategy ";
        }
        
        $sql .= "ORDER BY project.project_ID DESC";

        $bind = [
            'year' => $year
        ];

        if($division != 0) {
            $bind['division'] = $division;
        }

        if($strategy != 0) {
            $bind['strategy'] = $strategy;
        }

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getProjectsByReo($year, $division)
    {
        $sql = "SELECT project.*, division.division_name, division.division_abbr 
        FROM projects project 
        LEFT JOIN divisions division ON project.division = division.division_ID
        WHERE project.project_year = :year 
        AND project.active = 1 ";

        if(in_array(4, User::roles())) {
            $sql .= "AND division.division_group = 1 ";
        }

        if($division != 0) {
            $sql .= "AND project.division = :division ";
        }
        
        $sql .= "ORDER BY project.project_ID DESC";

        $bind = [
            'year' => $year
        ];

        if($division != 0) {
            $bind['division'] = $division;
        }

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getProjectStatus($year)
    {
        $sql = "SELECT total, quarter_1, quarter_2, quarter_3, quarter_4
            FROM
            (SELECT count(project.project_ID) AS total
            FROM projects project
            WHERE project.project_year = :year 
            AND project.active = 1
            ) AS total
            JOIN
            (SELECT count(project.project_ID) AS quarter_1
            FROM projects project
            WHERE project.project_year = :year 
            AND project.active = 1
            AND project.q1_file_path IS NOT NULL) AS quarter_1
            JOIN
            (SELECT count(project.project_ID) AS quarter_2
            FROM projects project
            WHERE project.project_year = :year 
            AND project.active = 1
            AND project.q2_file_path IS NOT NULL) AS quarter_2
            JOIN
            (SELECT count(project.project_ID) AS quarter_3
            FROM projects project
            WHERE project.project_year = :year 
            AND project.active = 1
            AND project.q3_file_path IS NOT NULL) AS quarter_3
            JOIN
            (SELECT count(project.project_ID) AS quarter_4
            FROM projects project
            WHERE project.project_year = :year 
            AND project.active = 1
            AND project.q4_file_path IS NOT NULL) AS quarter_4 ";

        $bind = [
            'year' => $year
        ];
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

    public function getProjectStatusByDivision($year, $division)
    {
        $sql = "SELECT total, quarter_1, quarter_2, quarter_3, quarter_4
            FROM
            (SELECT count(project.project_ID) AS total
            FROM projects project
            WHERE project.project_year = :year
            AND project.active = 1
            AND project.division = :division
            ) AS total
            JOIN
            (SELECT count(project.project_ID) AS quarter_1
            FROM projects project
            WHERE project.project_year = :year
            AND project.active = 1
            AND project.division = :division
            AND project.q1_file_path IS NOT NULL) AS quarter_1
            JOIN
            (SELECT count(project.project_ID) AS quarter_2
            FROM projects project
            WHERE project.project_year = :year
            AND project.active = 1
            AND project.division = :division
            AND project.q2_file_path IS NOT NULL) AS quarter_2
            JOIN
            (SELECT count(project.project_ID) AS quarter_3
            FROM projects project
            WHERE project.project_year = :year
            AND project.active = 1
            AND project.division = :division
            AND project.q3_file_path IS NOT NULL) AS quarter_3
            JOIN
            (SELECT count(project.project_ID) AS quarter_4
            FROM projects project
            WHERE project.project_year = :year
            AND project.active = 1
            AND project.division = :division
            AND project.q4_file_path IS NOT NULL) AS quarter_4 ";

        $bind = [
            'division' => $division,
            'year' => $year,
        ];
        
        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            echo $stmt->debugDumpParams();
            return false;
        }
    }

    ////////////////// CREATE //////////////////

    public function createProject($data) {
        $sql = "INSERT INTO projects (project_year, project_name, division, total_budget, strategy_ops) 
            VALUES (:year, :name, :division, :budget, :strategy_ops)";

        $bind = [
            'year' => $data['yearsSelect'],
            'name' => $data['projetName'],
            'division' => $data['divisionId'],
            'budget' => $data['budget'],
            'strategy_ops' => $data['strategiesSelect']
        ];

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


    ////////////////// UPDATE //////////////////

    public function updateProject($data) {
 
        $sql = "UPDATE projects SET project_name = :project_name, total_budget = :budget, strategy_ops = :strategy WHERE project_ID = :id";

        $bind = [
            'id' => $data['projectId'],
            'project_name' => $data['projetName'],
            'budget' => $data['budget'],
            'strategy' => $data['strategiesSelect']
        ];

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function updateProjectPlannedStatus($data) {
        $sql = "UPDATE projects SET planned = :status WHERE project_ID = :id";

        $status = ($data['status'] == 'true') ? 1 : 0;
        $bind = [
            'id' => $data['id'],
            'status' => $status
        ];

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    ////////////////// FILE //////////////////

    public function updateFilePath($projectId, $quarter, $filePath) {
        $quarterStr = 'q' . $quarter . '_file_path';
        $quarterDate = 'q' . $quarter . '_file_update_date';
        $sql = "UPDATE projects SET $quarterStr = :filePath, $quarterDate = NOW() WHERE project_ID = :projectId";

        $bind = [
            'projectId' => $projectId,
            'filePath' => $filePath
        ];
        try{
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            return $stmt->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function deleteFile($id, $quarter) 
    {
        $quarterStr = 'q' . $quarter . '_file_path';
        $quarterDate = 'q' . $quarter . '_file_update_date';
        $sql = "UPDATE projects SET $quarterStr = null, $quarterDate = null WHERE project_ID = :id";

        $bind = [
            'id' => $id
        ];
        try{
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            return $stmt->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function deleteProject($id) {
        $sql = "UPDATE projects SET active = 0 WHERE project_ID = :id";

        $bind = [
            'id' => $id
        ];

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

}

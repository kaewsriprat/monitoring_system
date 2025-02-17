<?php

class DatatableModel extends Model
{

    public function getProjectsByDiv($from, $length, $search, $order, $dir, $yearSelect)
    {
        $selectLookup = [
            'project.project_ID',
            'project.project_name',
            'project.project_year',
            'project.q1_file_path',
            'project.q2_file_path',
            'project.q3_file_path',
            'project.q4_file_path',
            'project.active',
            'project.planned',
        ];
  
        // QUERY BUILDER
        $querySelect = "SELECT project.project_ID, project.project_name, project.project_year, project.division, project.q1_file_path, project.q1_file_update_date, project.q2_file_path, project.q2_file_update_date, project.q3_file_path, project.q3_file_update_date, project.q4_file_path, project.q4_file_update_date, project.active, project.planned ";
        $queryFrom = "FROM projects project ";
        $queryWhere = "WHERE project.division = '".User::division()."' AND project.project_year = '$yearSelect' AND project.active = 1 ";

        // GET TOTAL
        $total = $this->countTotalProjects($queryWhere);

        // SEARCH
        if ($search != "") {
            $queryWhere .= "AND (project.project_ID LIKE '%$search%' OR project.project_name LIKE '%$search%' ";
        }

        //GET FILTRATED TOTAL
        $totalFiltered = $this->countTotalProjects($queryWhere);

        // ORDER BY
        $queryOrder = "ORDER BY " . $selectLookup[$order] . " " . $dir . " ";

        // LIMIT
        $queryLimit = "LIMIT $from, $length";

        // FINAL SQL
        $sql = $querySelect . $queryFrom . $queryWhere . $queryOrder . $queryLimit;
       
        try {
            $stmt = $this->Rdb->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data['data'] = $result;
            $data['total'] = $total;
            $data['totalFiltered'] = $totalFiltered;
            return $data;
        } catch (PDOException $e) {
            echo $stmt->debugDumpParams();
            echo $e->getMessage();
            exit;
        }
    }

    private function countTotalProjects($queryWhere)
    {
        $sql = "SELECT COUNT(project_ID) FROM projects project " . $queryWhere;
        try{
            $stmt = $this->Rdb->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

}

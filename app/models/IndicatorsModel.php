<?php

class IndicatorsModel extends Model
{

    //////////////////////////////////// GOALS ////////////////////////////////////

    public function getGoals()
    {
        $sql = "SELECT * FROM ind_goals WHERE status = 1";

        try {
            $stmt = $this->Rdb->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getGoalById($id)
    {
        $sql = "SELECT * FROM ind_goals WHERE status = 1 AND id = :id ";

        $bind = array(
            'id' => $id
        );

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getGoalsByYear($year, $classification)
    {
        $sql = "SELECT * FROM ind_goals WHERE status = 1 AND year = :year AND classification = :classification";

        $bind = array(
            'year' => $year,
            'classification' => $classification
        );

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function newGoal($title, $year, $classification)
    {
        $sql = "INSERT INTO ind_goals (title, year, status, classification) VALUES (:title, :year, 1, :classification)";

        $bind = array(
            'title' => $title,
            'year' => $year,
            'classification' => $classification
        );

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function updateGoal($id, $title, $year, $classification)
    {
        $sql = "UPDATE ind_goals SET title = :title, year = :year, classification = :classification WHERE id = :id";

        $bind = array(
            'id' => $id,
            'title' => $title,
            'year' => $year,
            'classification' => $classification
        );

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteGoal($id)
    {
        $sql = "UPDATE ind_goals SET status = 0 WHERE id = :id";

        $bind = array(
            'id' => $id
        );

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //////////////////////////////////// GOALS ////////////////////////////////////

    //////////////////////////////////// INDICATORS ////////////////////////////////////

    public function getIndicatorById($id)
    {
        $sql = "SELECT ind.id, ind.title, goal.id AS goal_id, goal.title AS goal_title, goal.year, goal.classification, ind.title, ind.target, ind.target_detail, rep.id AS rep_id, rep.division_id AS rep_division_id, office.division_name, office.division_abbr, project.project_ID, project.project_name, rep.target AS rep_target, rep.target_detail AS rep_target_detail
                FROM ind_indicator_major_report rep
                RIGHT JOIN ind_indicator_major ind
                ON rep.indicator_id = ind.id
                LEFT JOIN ind_goals goal
                ON ind.goal_id = goal.id
                LEFT JOIN divisions office
                ON rep.division_id = office.division_id
                LEFT JOIN projects project
                ON rep.project_id = project.project_ID
                WHERE ind.id = :id
                -- AND project.active = 1
                ";

        $bind = array(
            'id' => $id
        );

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $this->indicatorsConvert($result)['data'];
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    private function indicatorsConvert($result)
    {
        /*  
        data: {
            indicator_id,
            title,
            goal_id,
            goal_title,
            year,
            classification,
            target,
            target_detail,
            reports: [
                {
                    rep_id,
                    rep_division_id,
                    division_name,
                    division_abbr,
                    project_name
                }
            ]
        }
        */

        $data = [];

        foreach ($result as $item) {
            $indicatorId = $item['id'];
            $goalId = $item['goal_id'];

            if (!isset($data["data"])) {
                $data["data"] = [
                    'indicator_id' => $indicatorId,
                    'title' => $item['title'],
                    'goal_id' => $goalId,
                    'goal_title' => $item['goal_title'],
                    'year' => $item['year'],
                    'classification' => $item['classification'],
                    'target' => $item['target'],
                    'target_detail' => $item['target_detail'],
                    'reports' => []
                ];
            }

            $data["data"]['reports'][] = [
                'rep_id' => $item['rep_id'],
                'rep_division_id' => $item['rep_division_id'],
                'division_name' => $item['division_name'],
                'division_abbr' => $item['division_abbr'],
                'project_id' => $item['project_ID'],
                'project_name' => $item['project_name'],
                'rep_target' => $item['rep_target'],
                'rep_target_detail' => $item['rep_target_detail']
            ];
        }

        return $data;
    }

    public function getIndicatorsByGoalId($goalId)
    {
        $sql = "SELECT goals.*, ind.*, rep.*
                FROM ind_indicator_major ind
                LEFT JOIN ind_indicator_major_report rep
                ON rep.indicator_id = ind.id
                LEFT JOIN ind_goals goals
                ON	ind.goal_id = goals.id
                WHERE ind.goal_id = :goalId";

        $bind = array(
            'goalId' => $goalId
        );

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getFullIndicatorsReportByIndicatorId($indicator_id)
    {

        $sql = "SELECT ind.*, goals.year, goals.id AS goal_id, goals.title AS goal, goals.classification, ind.target, ind.target_detail, rep.id AS rep_id, office.division_name, project.project_id, project.project_name, rep.target AS rep_target, rep.q1_score, rep.q2_score, rep.q3_score, rep.q4_score
                FROM ind_indicator_major ind
                LEFT JOIN ind_indicator_major_report rep
                ON rep.indicator_id = ind.id
                LEFT JOIN ind_goals goals
                ON	ind.goal_id = goals.id
                LEFT JOIN divisions office
                ON rep.division_id = office.division_id
                LEFT JOIN projects project
                ON rep.project_id = project.project_id
                WHERE ind.id = :indicator_id
                AND project.active = 1
                ORDER BY ind.id, rep.id";

        $bind = array(
            'indicator_id' => $indicator_id
        );

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = $this->indicatorsArrayConvert($result);
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getSummaryIndicatorReport($year, $classification)
    {
        ($classification == 1) ? $classification = 'major' : $classification = 'minor';
        $sql = "SELECT ind.id AS indicator_id, ind.title AS indicator_title, goal.year, goal.title AS goal_title, ind.target, sum(q1_score) AS q1_score, sum(q2_score) AS q2_score, sum(q3_score) AS q3_score, sum(q4_score) AS q4_score, ((sum(q1_score)+sum(q2_score)+sum(q3_score)+sum(q4_score))/ind.target)*100 AS percentile, ind.created_date
                FROM ind_indicator_major_report rep
                RIGHT JOIN ind_indicator_major ind
                ON rep.indicator_id = ind.id
                LEFT JOIN ind_goals goal
                ON ind.goal_id = goal.id
                WHERE goal.year = :year
                AND goal.classification = :classification
                GROUP BY ind.id";

        $bind = array(
            'year' => $year,
            'classification' => $classification
        );

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function newIndicator($data)
    {
        $sql_1 = "INSERT INTO ind_indicator_major(title, goal_id, target, target_detail) 
        VALUES ('" . $data['indicatorName'] . "', '" . $data['goalsIndSelect'] . "', '" . $data['IndTarget'] . "', '" . $data['IndTargetText'] . "'); ";

        $lastId = " SET @main_table_id = LAST_INSERT_ID();";

        $sql_2 = "INSERT INTO ind_ind_division (indicator_id, division_id) ";
        $value_2 = "";

        $keys = [];
        $divArr = explode(',', $_POST['divisionArr']);
        foreach ($divArr as $key => $value) {
            array_push($keys, $value);
        }

        $count = count($keys);

        for ($i = 0; $i < $count; $i++) {
            $value_2 .= "SELECT @main_table_id, '" . $keys[$i] . "'";
            if ($i == $count - 1) {
                break;
            } else {
                $value_2 .= " UNION ";
            }
        }

        $sql_2 .= $value_2;
        $query = $sql_1 . $lastId . $sql_2;

        try {
            $stmt = $this->Wdb->prepare($query);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function updateIndicator($data)
    {
        $sql = "UPDATE ind_indicator_major SET title = :title, target = :target, target_detail = :target_detail, goal_id = :goal_id WHERE id = :id; ";

        $bind = array(
            'id' => $data['indicatorId'],
            'title' => $data['indicatorName'],
            'target' => $data['indicatorTarget'],
            'target_detail' => $data['indicatorTargetText'],
            'goal_id' => $data['goalsIndSelect']
        );

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function deleteIndicator($id)
    {
        $sql = "DELETE FROM ind_indicator_major_report
                WHERE id IN (
                    SELECT id FROM (
                        SELECT rep.id
                        FROM ind_indicator_major ind
                        RIGHT JOIN ind_indicator_major_report rep ON ind.id = rep.indicator_id
                        WHERE ind.id = :id
                    ) AS temp_ids
                );

                DELETE FROM ind_indicator_major ind
                WHERE ind.id = :id;";

        $bind = array(
            'id' => $id
        );

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //////////////////////////////////// INDICATORS ////////////////////////////////////

    //////////////////////////////////// REPORTS ////////////////////////////////////

    public function getIndicatorsByDivision($year, $division)
    {
        $sql = "SELECT goal.id AS goal_id, goal.title AS goal_title, goal.classification, ind.id AS ind_id, ind.title AS ind_title, project.project_id, project.project_name, rep.id AS rep_id, office.division_name, rep.target, rep.q1_score, rep.q1_approve, rep.q2_score, rep.q2_approve, rep.q3_score, rep.q3_approve, rep.q4_score, rep.q4_approve, (rep.q1_score + rep.q2_score + rep.q3_score + rep.q4_score) AS total, ((rep.q1_score + rep.q2_score + rep.q3_score + rep.q4_score)/rep.target) * 100 AS percentile
                FROM ind_indicator_major_report rep
                LEFT JOIN ind_indicator_major ind
                ON rep.indicator_id = ind.id
                LEFT JOIN ind_goals goal
                ON ind.goal_id = goal.id
                LEFT JOIN divisions office
                ON rep.division_id = office.division_id
                LEFT JOIN projects project
                ON rep.project_id = project.project_ID
                WHERE goal.year = :year
                -- AND project.active = 1
                AND rep.division_id = :division";

        $bind = array(
            'year' => $year,
            'division' => $division
        );

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getIndicatorsByDivision2($year, $division)
    {
        $sql = "SELECT goal.id AS goal_id, goal.title AS goal_title, goal.classification, ind.id AS ind_id, ind.title AS ind_title, project.project_id, project.project_name, rep.id AS rep_id, office.division_name, rep.target, rep.target_detail, rep.q1_score, rep.q1_approve, rep.q2_score, rep.q2_approve, rep.q3_score, rep.q3_approve, rep.q4_score, rep.q4_approve, (rep.q1_score + rep.q2_score + rep.q3_score + rep.q4_score) AS total, ((rep.q1_score + rep.q2_score + rep.q3_score + rep.q4_score)/rep.target) * 100 AS percentile
            FROM ind_ind_division inddiv
            LEFT JOIN ind_indicator_major ind
            ON inddiv.indicator_id = ind.id
            LEFT JOIN ind_indicator_major_report rep
            ON ind.id = rep.indicator_id
            LEFT JOIN ind_goals goal
            ON ind.goal_id = goal.id
            LEFT JOIN projects project
            ON rep.project_id = project.project_ID
            LEFT JOIN divisions office
            ON rep.division_id = office.division_id
            WHERE goal.year = :year
            AND inddiv.division_id = :division";

        $bind = array(
            'year' => $year,
            'division' => $division
        );


        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getReportById($id)
    {
        $sql = "SELECT rep.*, goal.title AS goal_title, goal.classification, goal.year, ind.title, ind.target AS ind_target, ind.target_detail, office.division_name, office.division_abbr, project.project_name, reqTarget.director_approved, reqTarget.admin_approved
                FROM ind_indicator_major_report rep
                LEFT JOIN ind_indicator_major ind
                ON rep.indicator_id = ind.id
                LEFT JOIN ind_goals goal
                ON ind.goal_id = goal.id
                LEFT JOIN divisions office
                ON rep.division_id = office.division_id
                LEFT JOIN projects project
                ON rep.project_id = project.project_ID
                LEFT JOIN ind_request_target reqTarget
				ON reqTarget.rep_id = rep.id
                WHERE rep.id = :id
                AND project.active = 1";

        $bind = array(
            'id' => $id
        );

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function updateIndicatorScore($repId, $quarter, $score)
    {
        $sql = "UPDATE ind_indicator_major_report SET q" . $quarter . "_score = :score WHERE id = :repId";

        $bind = array(
            'repId' => $repId,
            'score' => $score
        );

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function updateUserReport($data) {
        $sql = "UPDATE ind_indicator_major_report SET target = :target, target_detail = :target_detail, q1_score = :q1_score, q2_score = :q2_score, q3_score = :q3_score, q4_score = :q4_score WHERE id = :id";

        $bind = array(
            'id' => $data['rep_id'],
            'target' => $data['target'],
            'target_detail' => $data['targetDetail'],
            'q1_score' => $data['q1_score'],
            'q2_score' => $data['q2_score'],
            'q3_score' => $data['q3_score'],
            'q4_score' => $data['q4_score']
        );
       
        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function updateNewReports($data)
    {
        $sql = "";

        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['projectId'] == '') {
                $data[$i]['projectId'] = 'NULL';
            }
            $sql .= "INSERT INTO ind_indicator_major_report(indicator_id, division_id, project_id, target, target_detail) VALUES (" . $data[$i]['indicatorId'] . ", " . $data[$i]['divisionId'] . ", " . $data[$i]['projectId'] . ", " . $data[$i]['target'] . ", '" . $data[$i]['targetText'] . "'); ";
        }

        try {
            $stmt = $this->Wdb->prepare($sql);
            $stmt->execute();
            return $sql;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function updateReports($data)
    {
        $sql = "";

        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['projectId'] == '') {
                $data[$i]['projectId'] = 'NULL';
            }
            $sql .= "UPDATE ind_indicator_major_report SET project_id = " . $data[$i]['projectId'] . ", target = " . $data[$i]['target'] . ", target_detail = '" . $data[$i]['targetText'] . "' WHERE id = " . $data[$i]['id'] . "; ";
        }

        try {
            $stmt = $this->Wdb->prepare($sql);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteReports($data)
    {
        $data = json_decode($data, true);

        $sql = "";
        for ($i = 0; $i < count($data); $i++) {
            $sql .= "DELETE FROM ind_indicator_major_report WHERE id = '" . $data[$i] . "'; ";
        }

        try {
            $stmt = $this->Wdb->prepare($sql);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function deleteUserReport($repId) {
        $sql = "DELETE FROM ind_indicator_major_report WHERE id = :repId";

        $bind = array(
            'repId' => $repId
        );
        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function assignProjectToReport($data)
    {
        $sql = "INSERT INTO ind_indicator_major_report(indicator_id, division_id, project_id, target, target_detail) VALUES (:indicator_id, :division_id, :project_id, :target, :target_detail)";

        $bind = array(
            'indicator_id' => $data['indId'],
            'division_id' => $data['division'],
            'project_id' => $data['projectId'],
            'target' => $data['target'],
            'target_detail' => $data['targetDetail']
        );

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //////////////////////////////////// REPORTS ////////////////////////////////////

    public function indicatorsArrayConvert($result)
    {
        /*
        $data = [
            goal_id
            goal_title
            goal_year
            indicators => [
                indicator_id
                indicator_title
                target
                target_detail
                target_percent
                divisions => [
                    division_id
                    division_name
                    project
                    target
                    target_detail
                    q1_score
                    q2_score
                    q3_score
                    q4_score
                    q1_percent
                    q2_percent
                    q3_percent
                    q4_percent
                ]
            ]
        ]
        */

        // Prepare the $data array
        $data = [];

        // Group data by goal
        foreach ($result as $item) {
            $goalId = $item['id'];
            $indicatorId = $item['rep_id'];

            // Initialize the goal if not already set
            if (!isset($data[$goalId])) {
                $data[$goalId] = [
                    'goal_id' => $goalId,
                    'goal_title' => $item['goal'],
                    'goal_year' => $item['year'],
                    'indicators' => []
                ];
            }

            // Calculate target_percent (sum q1 to q4)
            $totalScore = (int) ($item['q1_score'] ?? 0) + (int) ($item['q2_score'] ?? 0) + (int) ($item['q3_score'] ?? 0) + (int) ($item['q4_score'] ?? 0);
            $targetPercent = $this->percentile($totalScore, $item['target']);

            // Add the indicator details
            $data[$goalId]['indicators'][] = [
                'indicator_id' => $indicatorId,
                'indicator_title' => $item['title'],
                'target' => $item['target'],
                'target_detail' => $item['target_detail'],
                'target_percent' => $targetPercent,
                'divisions' => [
                    'division_id' => $indicatorId,
                    'division_name' => $item['division_name'],
                    'project' => $item['project_name'],
                    'target' => $item['rep_target'],
                    'target_detail' => $item['target_detail'],
                    'q1_score' => $item['q1_score'],
                    'q2_score' => $item['q2_score'],
                    'q3_score' => $item['q3_score'],
                    'q4_score' => $item['q4_score'],
                    'q1_percent' => $this->percentile($item['q1_score'], $totalScore),
                    'q2_percent' => $this->percentile($item['q2_score'], $totalScore),
                    'q3_percent' => $this->percentile($item['q3_score'], $totalScore),
                    'q4_percent' => $this->percentile($item['q4_score'], $totalScore)
                ]
            ];
        }

        return $data;
    }

    private function percentile($value, $total)
    {
        return ($total == 0) ? 0 : ($value / $total) * 100;
    }

    //////////////////////////////////// APPROVE ////////////////////////////////////

    public function doApprove($data)
    {
        $sql = "UPDATE ind_indicator_major_report SET q" . $data['quarter'] . "_approve = :status WHERE id = :rep_id";

        $bind = [
            'rep_id' => $data['rep_id'],
            'status' => $data['status']
        ];

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $stmt->debugDumpParams();
            echo $e->getMessage();
        }
    }

    //////////////////////////////////// TARGET UPDATE REQUEST ////////////////////////////////////

    public function requestUpdateTargetPending($id, $newTarget)
    {

        $isExisted = $this->checkExistedPendingRequest($id);
        if (!$isExisted) {
            $sql = "INSERT INTO ind_request_target (rep_id, new_target) VALUES (:rep_id, :new_target)";
        } else {
            $sql = "UPDATE ind_request_target SET new_target = :new_target, director_approved = 0, admin_approved = 0 WHERE rep_id = :rep_id";
        }

        $bind = [
            'rep_id' => $id,
            'new_target' => $newTarget
        ];

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    private function checkExistedPendingRequest($id)
    {
        $sql = "SELECT * FROM ind_request_target WHERE rep_id = :rep_id";

        $bind = [
            'rep_id' => $id
        ];

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getPendingRequestTargets($division)
    {
        $sql = "SELECT req.*, ind.title, project.project_name, rep.target
            FROM ind_request_target req
            LEFT JOIN ind_indicator_major_report rep
            ON req.rep_id = rep.id
            LEFT JOIN projects project
            ON rep.project_id = project.project_ID
            LEFT JOIN ind_indicator_major ind
            ON rep.indicator_id = ind.id ";

        $bind = [];
        if (User::isDirector()) {
            $sql .= "WHERE rep.division_id = :division AND req.director_approved = 0 AND project.active = 1";
            $bind = [
                'division' => $division
            ];
        }
        if (User::isAdmin()) {
            $sql .= "WHERE req.director_approved = 1 AND req.admin_approved = 0 AND project.active = 1";
        }

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function approveRequestTarget($id, $status)
    {
        $sql = "UPDATE ind_request_target SET ";

        if (User::isAdmin()) {
            $sql .= "admin_approved = :status ";
        }
        if (User::isDirector()) {
            $sql .= "director_approved = :status ";
        }

        $sql .= "WHERE id = :id";

        $bind = [
            'id' => $id,
            'status' => $status
        ];

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getPendingRequestTargetsCount()
    {
        $sql = "SELECT count(*) AS count 
            FROM ind_request_target req
            JOIN ind_indicator_major_report rep
            ON req.rep_id = rep.id ";

        $bind = [];

        if (User::isDirector()) {
            $sql .= "WHERE rep.division_id = :division AND req.director_approved = 0";
        }

        if (User::isDirector()) {
            $bind = [
                'division' => User::division()
            ];
        }

        if (User::isAdmin()) {
            $sql .= "WHERE req.director_approved = 1 AND req.admin_approved = 0";
        }

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function updateTarget($id, $newTarget)
    {
        $sql = "UPDATE ind_indicator_major_report SET target = :new_target WHERE id = :id";

        $bind = [
            'id' => $id,
            'new_target' => $newTarget
        ];

        try {
            $stmt = $this->Wdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function checkIfTargetIsApproved($id)
    {
        $sql = "SELECT rep_id, new_target FROM ind_request_target WHERE id = :id AND director_approved = 1 AND admin_approved = 1";

        $bind = [
            'id' => $id
        ];

        try {
            $stmt = $this->Rdb->prepare($sql);
            $this->bind($stmt, $bind);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return ['status' => true, 'data' => $stmt->fetch(PDO::FETCH_ASSOC)];
            } else {
                return ['status' => false, 'data' => null];
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

<?php

class Budgetyear
{
    // buget year start at 1st October to 30th September
    public static function getBudgetyear()
    {
        $date = new DateTime();
        $year = $date->format('Y');
        $month = $date->format('m');
        if ($month >= 10) {
            $year = $year + 1;
        }
        return $year;
    }

    // thai format
    public static function getBudgetyearThai()
    {
        $date = new DateTime();
        $year = $date->format('Y');
        $month = $date->format('m');
        if ($month >= 10) {
            $year = $year + 1;
        }

        return $year + 543;
    }

    public static function getBudgetYearList($yearCount = 2)
    {
        $startYear = self::getBudgetyearThai() - $yearCount;
        $currentYear = self::getBudgetyearThai();
        $years = array();
        for ($year = $currentYear; $year >= $startYear; $year--) {
            $years[] = $year;
        }
        return $years;
    }
}

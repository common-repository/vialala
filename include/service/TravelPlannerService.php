<?php

namespace Vialala\Service;

class TravelPlannerService
{
    /**
     * @param $travelPlanner
     * @return string
     */
    public static function getFirstName($travelPlanner) {
        if ($travelPlanner)
            return ucfirst(strtolower($travelPlanner['firstName']));
        return '';
    }

    /**
     * @param $travelPlanner
     * @return string
     */
    public static function getLastName($travelPlanner) {
        if ($travelPlanner)
            return ucfirst(strtolower($travelPlanner['lastName']));
        return '';
    }

    /**
     * @param $travelPlanner
     * @return string
     */
    public static function getCompleteName($travelPlanner) {
        $firstName = self::getFirstName($travelPlanner);
        $lastName = self::getLastName($travelPlanner);

        return $firstName . (!empty($firstName) && !empty($lastName) ? ' ' : '') . $lastName;
    }

    /**
     * @param $travelPlanner
     * @return string
     */
    public static function getSlug($travelPlanner) {
        if ($travelPlanner)
            return 'https://www.vialala.fr/travel-planner/' . $travelPlanner['slug'];
        return '';
    }
}
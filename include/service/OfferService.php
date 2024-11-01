<?php

namespace Vialala\Service;

use DateTime;
use Exception;

class OfferService
{
    /**
     * @param $slug
     * @return mixed|null
     */
    public static function getOfferData($slug) {
        return ApiService::makeGetApiCall('publicOfferBySlug/' . $slug);
    }

    /**
     * @param $offerModule
     * @return array
     * @throws Exception
     */
    public static function getServicesOfModule($offerModule) {
        $services = [];

        if($offerModule['childComponents']) {
            foreach ($offerModule['childComponents'] as $module) {
                if($module['type'] === 'module')
                    $services = array_merge($services, self::getServicesOfModule($module));
                else if($module['type'] === 'prestation') {
                    $module['prestation']['day'] = $module['day'];
                    $module['prestation']['endDay'] = $module['endDay'];
                    $module['prestation']['startDate'] = $module['startDate'];
                    $module['prestation']['endDate'] = $module['endDate'];
                    $module['prestation']['iterationCount'] = $module['iterationCount'];
                    $services[] = $module['prestation'];
                }
            }
        }

        usort($services, function($p1, $p2) {
            if(new DateTime($p1['startDate']) === new DateTime($p2['startDate']))
                return 0;

            return new DateTime($p1['startDate']) < new DateTime($p2['startDate']) ? -1 : 1;
        });

        return $services;
    }

    /**
     * @param $offer
     * @return array
     * @throws Exception
     */
    public static function organiseServicesByDay($offer) {
        $daysServices = [];
        $sortedDaysServices = [];
        if($offer['module']) {
            $allServices = self::getServicesOfModule($offer['module']);

            foreach ($allServices as $service) {
                if (!array_key_exists(intval($service['day']), $daysServices))
                    $daysServices[intval($service['day'])] = [];
                $daysServices[intval($service['day'])][] = $service;

                if(intval($service['day']) < intval($service['endDay'])) {
                    for($i = 1 ; $i <= intval($service['endDay']) - intval($service['day']) ; $i++) {
                        if (!array_key_exists(intval($service['day']) + $i, $daysServices))
                            $daysServices[intval($service['day']) + $i] = [];
                        $daysServices[intval($service['day']) + $i][] = $service;
                    }
                }
            }

            foreach ($daysServices as $key => $services) {
                usort($services, function($p1, $p2) {
                    if(intval($p1['prestationType']['id']) === 1 || intval($p2['prestationType']['id']) === 1) {
                        if(intval($p1['prestationType']['id']) === 1)
                            return 1;
                        else if(intval($p2['prestationType']['id']) === 1)
                            return -1;
                        return 0;
                    }

                    if(intval($p1['prestationType']['id']) === 5 || intval($p2['prestationType']['id']) === 5) {
                        if(intval($p1['prestationType']['id']) === 5)
                            return -1;
                        else if(intval($p2['prestationType']['id']) === 5)
                            return 1;
                        return 0;
                    }

                    if(new DateTime($p1['startDate']) === new DateTime($p2['startDate']))
                        return 0;

                    return new DateTime($p1['startDate']) < new DateTime($p2['startDate']) ? -1 : 1;
                });
                $sortedDaysServices[$key] = $services;
            }
        }

        return $sortedDaysServices;
    }

    /**
     * @param $offer
     * @return array
     * @throws Exception
     */
    public static function getIncluded($offer) {
        $includes = [];
        if($offer['module']) {
            $allServices = self::getServicesOfModule($offer['module']);

            foreach ($allServices as $service) {
                if (!$service['prestationType'])
                    continue;

                $serviceTypeId = intval($service['prestationType']['id']);
                if(!array_key_exists($serviceTypeId, $includes)) {
                    $includes[$serviceTypeId] = [];
                    $includes[$serviceTypeId]['name'] = $service['prestationType']['name'];
                    $includes[$serviceTypeId]['prestationNames'] = [];
                }

                $includes[$serviceTypeId]['prestationNames'][] = $service['name'];
            }
        }
        return $includes;
    }

    /**
     * @param $offer
     * @return mixed|string
     */
    public static function getHeaderImage($offer) {
        if (!empty($offer['images'])) {
            foreach ($offer['images'] as $image) {
                if (isset($image['type']) && $image['type'] == 'header')
                    return $image['url'];
            }
            if(empty($url))
                return $offer['images'][0]['url'];
        }
        return '';
    }

    /**
     * @param $offer
     * @return string
     */
    public static function getSlug($offer) {
        if ($offer)
            return 'https://www.vialala.fr/voyage-sur-mesure/' . $offer['slug'];
        return '';
    }

    /**
     * @param $offer
     * @param $dayIndex
     * @return mixed|string
     */
    public static function getDayName($offer, $dayIndex) {
        if ($offer && $offer['days']) {
            foreach ($offer['days'] as $day) {
                if(intval($day['position']) === $dayIndex)
                    return $day['name'];
            }
        }
        return '';
    }

}
<?php

namespace Vialala\Service;

class ServiceService
{
    public static function getPrice($service) {
        $hasOnplacePrice = false;

        $iterationCountStr = intval($service['iterationCount']) > 1 ? ' (x' . intval($service['iterationCount']) . ')' : '';
        if($service['tripPlanning'] && $service['tripPlanning']['tripPlanningType'] && $service['prestationType']['stepperMode'] === 'tripPlanning') {
            if($service['tripPlanning']['tripPlanningType']['allowCustom'] && ceil($service['tripPlanning']['customTripPlanningPrice']) > 0)
                return ceil($service['tripPlanning']['customTripPlanningPrice']) . '€';
            else if($service['tripPlanning']['tripPlanningType']['price'] > 0)
                return $service['tripPlanning']['tripPlanningType']['price'] . '€';
        } else if($service['price']) {
            $onPlacePrice = $service['prestationType']['stepperMode'] === 'additionalInformation';

            $priceStr = '';
            if($service['price']['priceMod'] === 'single' && $service['price']['price'] > 0)
                $priceStr = ceil($service['price']['price']) . '€' . ($service['price']['priceType'] === 'person' ? '/pers.' : '') . $iterationCountStr;
            else if($service['price']['priceMod'] === 'range' && isset($service['price']['priceLowerRange']) && $service['price']['priceUpperRange'] > 0)
                $priceStr = ceil(($service['price']['priceUpperRange'] + $service['price']['priceLowerRange']) / 2) . '€' . ($service['price']['priceType'] === 'person' ? '/pers.' : '') . $iterationCountStr;

            if(strlen($priceStr) > 0) {
                if($onPlacePrice) {
                    $hasOnplacePrice = true;
                    return '<i>' . $priceStr . ' **</i>';
                } else
                    return $priceStr;
            }
        }
        return '';
    }
}
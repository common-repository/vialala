<?php

namespace Vialala\Service;

/**
 * Class ApiService
 * @package Vialala\Service
 */
class ApiService {
    /**
     * @param $url
     * @return mixed|null
     */
    public static function makeGetApiCall($url) {
        if(!isset($GLOBALS['vialala_data'][$url]) || $GLOBALS['vialala_data'][$url] == null)
            $GLOBALS['vialala_data'][$url] = json_decode(wp_remote_retrieve_body(wp_remote_get(ApiService::formatApiUrl($url))), true);

        return $GLOBALS['vialala_data'][$url];
    }

    /**
     * @param $url
     * @return string
     */
    private static function formatApiUrl($url = '') {
        if (!ApiService::isHttpUrl($url))
            $url = VIALALA_DEFAULT_API_URL . $url;

        return $url;
    }

    private static function isHttpUrl($url) {
        return !empty($url) && strlen($url) > 7 && substr( $url, 0, 4 ) === "http";
    }
}
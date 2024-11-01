<?php

namespace Vialala\Shortcode;

class TravelPlannerShortcode
{
    /**
     * @var TravelPlannerShortcode
     */
    private static $_instance = null;

    /**
     * @return TravelPlannerShortcode
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}

TravelPlannerShortcode::instance();
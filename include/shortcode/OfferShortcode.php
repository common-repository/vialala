<?php

namespace Vialala\Shortcode;

use Exception;
use Vialala\Service\OfferService;
use Vialala\Service\ServiceService;
use Vialala\Service\TravelPlannerService;

class OfferShortcode
{
    /**
     * @var OfferShortcode
     */
    private static $_instance = null;

    /**
     * @return OfferShortcode
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Vialala constructor.
     */
    public function __construct() {
        add_shortcode('vialala_offer_header', [$this, 'header']);
        add_shortcode('vialala_offer_description', [$this, 'description']);
        add_shortcode('vialala_offer_include', [$this, 'included']);
        add_shortcode('vialala_offer_travel', [$this, 'travel']);
        add_shortcode('vialala_offer_button_start_customize', [$this, 'buttonStartCustomize']);
        add_shortcode('vialala_offer_data', [$this, 'data']);
    }

    /**
     * @param $atts
     * @return false|string
     * @throws Exception
     */
    public static function header($atts) {
        extract(shortcode_atts(
            array(
                'slug' => '_default_'
            ), $atts));

        if ($slug == '_default_')
            return "<p>".__('You must add a "slug" parameter to display your offer', 'vialala')."</p>";

        $data = OfferService::getOfferData($slug);
        $headerImageUrl = OfferService::getHeaderImage($data);
        $days = OfferService::organiseServicesByDay($data);

        ob_start();
        ?>
        <div class="vialala header" <?php echo (!empty($headerImageUrl) ? 'style="background-image: url(' . $headerImageUrl . ')"' : '') ?>>
            <a href="<?php echo OfferService::getSlug($data) ?>" target="_blank">
                <div class="label">
                    <h5><?php echo __('Discover', 'vialala') ?></h5>
                </div>
                <div class="destination">
                    <h2><?php echo ($data['destination'] ? $data['destination']['name'] : '') ?></h2>
                </div>
            </a>
            <div class="organisator">
                <h5><?php echo sprintf(__('Travel idea proposed by %s', 'vialala'), '<a href="'. TravelPlannerService::getSlug($data['contact']).'" target="_blank">'.TravelPlannerService::getCompleteName($data['contact']).'</a>') ?></h5>
            </div>
        </div>
        <div class="vialala header-bandeau">
            <a href="<?php echo OfferService::getSlug($data) ?>" target="_blank">
                <div class="infos">
                    <div class="prix">
                        <?php echo sprintf(__('From €%s / p.', 'vialala'), $data['priceForOne']); ?>
                    </div>
                    <div class="duree">
                        <?php echo sprintf(esc_html__('%1$s days / %2$s nights', 'vialala'), count($days), count($days) - 1); ?>
                    </div>
                    <div class="voyageur">
                        <?php echo sprintf(__('%1$s+ travelers', 'vialala'), ($data && $data['travelerType'] ? intval($data['travelerType']['travelerCount']) : '')) ?>
                    </div>
                    <div class="pays"><?php echo ($data['destinationCountry'] ? $data['destinationCountry']['name'] : '') ?></div>
                </div>
            </a>
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * @param $atts
     * @return false|string
     */
    public static function description($atts) {
        extract(shortcode_atts(
            array(
                'slug' => '_default_'
            ), $atts));

        if ($slug == '_default_')
            return "<p>".__('You must add a "slug" parameter to display your offer', 'vialala')."</p>";

        $data = OfferService::getOfferData($slug);

        ob_start();
        ?>
        <div class="vialala description">
            <a href="<?php echo OfferService::getSlug($data) ?>" target="_blank">
                <h3><?php echo $data['name'] ?></h3>
            </a>
            <h5><?php echo sprintf(__('A tailor-made travel proposal developed by the Travel Planner %s', 'vialala'), '<a href="'.TravelPlannerService::getSlug($data['contact']).'" target="_blank">'.TravelPlannerService::getCompleteName($data['contact']).'</a>') ?></h5>
            <p><?php echo $data['description'] ?></p>
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * @param $atts
     * @return false|string
     * @throws Exception
     */
    public static function included($atts) {
        extract(shortcode_atts(
            array(
                'slug' => '_default_'
            ), $atts));

        if ($slug == '_default_')
            return "<p>".__('You must add a "slug" parameter to display your offer', 'vialala')."</p>";

        $data = OfferService::getOfferData($slug);
        $includes = OfferService::getIncluded($data);

        ob_start();
        ?>
        <div class="vialala include">
            <div class="col-12">
                <h3><?php echo __('What is included in this trip', 'vialala') ?></h3>
                <p><b><?php echo __('The program presented as well as the inclusions are given as an indication. This is a travel idea from your Travel Planner that you can then adapt with it. Contact him directly to change the length of stay, activities, accommodation ... everything is possible!', 'vialala') ?></b></p>
            </div>
            <div class="types">
                <?php foreach($includes as $inclus) { ?>
                    <div class="type">
                        <div class="titre"><b><?php echo count($inclus['prestationNames']) ?> <?php echo $inclus['name'] ?><?php echo count($inclus['prestationNames']) > 1 ? 's' : '' ?></b></div>
                        <div class="desc"><?php echo implode(', ', $inclus['prestationNames']) ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * @param $atts
     * @return false|string
     * @throws Exception
     */
    public static function travel($atts) {
        extract(shortcode_atts(
            array(
                'slug' => '_default_'
            ), $atts));

        if ($slug == '_default_')
            return "<p>".__('You must add a "slug" parameter to display your offer', 'vialala')."</p>";

        $data = OfferService::getOfferData($slug);
        $days = OfferService::organiseServicesByDay($data);

        ob_start();
        ?>
        <div class="vialala travel">
            <a href="<?php echo OfferService::getSlug($data) ?>" target="_blank">
                <h3><?php echo sprintf(__('Your trip to €% s per person, from 2 travelers', 'vialala'), $data['priceForOne']) ?></h3>
            </a>
            <div class="days">
                <?php foreach($days as $key => $day) { ?>
                    <div class="day">
                        <input id="dayCollapse<?php echo $key ?>" class="toggle" type="checkbox">
                        <div class="day-toggle">
                            <h5><label for="dayCollapse<?php echo $key ?>"><?php echo sprintf(esc_html__('Day %1$s - %2$s', 'vialala'), $key, OfferService::getDayName($data, $key)); ?>
                        </div>
                        <div class="prestations">
                            <div class="prestations-container">
                                <?php foreach($day as $service) {
                                    $price = ServiceService::getPrice($service);
                                    ?>
                                    <div class="prestation">
                                        <div><b><?php echo $price . (!empty($price) ? ' - ' : '') ?><?php echo $service['name'] ?></b></div>
                                        <div><?php echo $service['description'] ?></div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <small><?php echo __('*The mention "at your expense" indicates that the service is not included in the calculation of the total price of the offer to pay online. The cost of the service remains at your expense, the provider must be paid locally.', 'vialala') ?></small>
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * @param $atts
     * @return false|string
     */
    public static function buttonStartCustomize($atts) {
        extract(shortcode_atts(
            array(
                'slug' => '_default_'
            ), $atts));

        if ($slug == '_default_')
            return "<p>".__('You must add a "slug" parameter to display your offer', 'vialala')."</p>";

        $data = OfferService::getOfferData($slug);

        ob_start();
        ?>
        <div class="vialala-button">
            <a class="button-vialala" href="<?php echo OfferService::getSlug($data) ?>" target="_blank"><?php echo __('I\'m interested !', 'vialala') ?></a>
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public static function data($atts) {
        extract(shortcode_atts(
            array(
                'slug' => '_default_',
                'data' => '_default_'
            ), $atts));

        if ($slug == '_default_')
            return "<p>".__('You must add a "slug" parameter to display your offer', 'vialala')."</p>";
        if ($data == '_default_')
            return "<p>".__('You must add a "data" parameter to set the data you would like to show', 'vialala')."</p>";

        $offer = OfferService::getOfferData($slug);

        switch ($data) {
            case 'name':
                return $offer['name'];
            case 'shortDescription':
                return $offer['shortDescription'];
            case 'description':
                return $offer['description'];
            case 'price':
                return $offer['price'];
            case 'onsitePrice':
                return $offer['onsitePrice'];
            case 'country':
                return isset($offer['destinationCountry']) ? $offer['destinationCountry']['name'] : '';
            case 'duration':
                return $offer['moduleDayCount'];
            default:
                return '';
        }
    }
}

OfferShortcode::instance();
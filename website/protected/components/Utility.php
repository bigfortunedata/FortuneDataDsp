<?php

/**
 * Utility.php
 *
 * 
 * @author Tony Zhang
 * @copyright 2013 Fortune Data Inc
 * @license released under dual license BSD License and LGP License
 * @package Utility
 * @version 1.0
 */
class Utility {

    public $version = '3.0';

    public static function GetAccountBalance() {

        $payment = ClientPayment::model()->findAll(array('condition' => ' user_id =' . Yii::app()->user->id . '  AND status = "SUCCESS"'));
        $balance = 0;
        foreach ($payment as $payments)
            $balance+=$payments->amount;
        return $balance;
    }

    /**
     * GetTaxRate
     * get Tax rate for Canadian province
     */
    public static function GetTaxRate() {

        Yii::import('application.modules.profile.models.*');
        $profile = YumProfile::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
        $country = $profile->country;
        $state = $profile->state;
        $tax_rate = 0;

        if (($country == 'CANADA') and ($state == 'BC')) {
            $tax_rate = 0.12;
        } elseif (($country == 'CANADA') and ($state == 'ON')) {
            $tax_rate = 0.13;
        } elseif (($country == 'CANADA') and ($state == 'PE')) {
            $tax_rate = 0.14;
        } elseif (($country == 'CANADA') and ($state == 'NB')) {
            $tax_rate = 0.13;
        } elseif (($country == 'CANADA') and ($state == 'NS')) {
            $tax_rate = 0.15;
        } elseif (($country == 'CANADA') and ($state == 'NL')) {
            $tax_rate = 0.13;
        } elseif (($country == 'CANADA')) {
            $tax_rate = 0.05;
        } else {
            $tax_rate = 0;
        }
        return $tax_rate;
    }

    /**
     * Get Status ID
     * get status code from fd_campaign_status
     */
    public static function GetStatusId($code) {


        $status = CampaignStatus::model()->findByAttributes(array('code' => $code));

        if (!isset($status)) {
            throw new CException(
            Yii::t('Utility', 'GetStatusId: Failed to get status id, status code:' . $code));
        }

        return $status->id;
    }

    /**
     * Get ReviewStatus ID
     * get status code from fd_campaign_review_status
     */
    public static function GetReviewStatusId($code) {


        $review_status = ReviewStatus::model()->findByAttributes(array('code' => $code));

        if (!isset($review_status)) {
            throw new CException(
            Yii::t('Utility', 'GetStatusId: Failed to get status id, status code:' . $code));
        }

        return $review_status->id;
    }

}
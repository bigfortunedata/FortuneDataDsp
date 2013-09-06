<?php

if (Yum::module()->rtepath != false)
    Yii::app()->clientScript->registerScriptFile(Yum::module()->rtepath);
if (Yum::module()->rteadapter != false)
    Yii::app()->clientScript->registerScriptFile(Yum::module()->rteadapter);

if ($profile) {



    echo CHtml::openTag('div', array());
    echo CHtml::activeLabelEx($profile, 'lastname', array('label' => 'Last Name:'));
    echo CHtml::activeTextField($profile, 'lastname');
    echo CHtml::error($profile, 'lastname');
    echo CHtml::closeTag('div');


    echo CHtml::openTag('div', array());
    echo CHtml::activeLabelEx($profile, 'firstname', array('label' => 'First Name:'));
    echo CHtml::activeTextField($profile, 'firstname');
    echo CHtml::error($profile, 'firstname');
    echo CHtml::closeTag('div');

    echo CHtml::openTag('div', array());
    echo CHtml::activeLabelEx($profile, 'email', array('label' => 'Email:'));
    echo CHtml::activeTextField($profile, 'email');
    echo CHtml::error($profile, 'email');
    echo CHtml::closeTag('div');


    echo CHtml::openTag('div', array());
    echo CHtml::activeLabelEx($profile, 'company_name', array('label' => 'Company Name:'));
    echo CHtml::activeTextField($profile, 'company_name');
    echo CHtml::error($profile, 'company_name');
    echo CHtml::closeTag('div');

    echo CHtml::openTag('div', array());
    echo CHtml::activeLabelEx($profile, 'website', array('label' => 'Company Website:'));
    echo CHtml::activeUrlField($profile, 'website');
    echo CHtml::error($profile, 'website');
    echo CHtml::closeTag('div');


    echo CHtml::openTag('div', array());
    echo CHtml::activeLabelEx($profile, 'phone_no', array('label' => 'Phone No.:'));
    echo CHtml::activeTextField($profile, 'phone_no');
    echo CHtml::error($profile, 'phone_no');
    echo CHtml::closeTag('div');

    echo CHtml::openTag('div', array());
    echo CHtml::activeLabelEx($profile, 'street', array('label' => 'Address:'));
    echo CHtml::activeTextField($profile, 'street');
    echo CHtml::error($profile, 'street');
    echo CHtml::closeTag('div');

    echo CHtml::openTag('div', array());
    echo CHtml::activeLabelEx($profile, 'city', array('label' => 'City:'));
    echo CHtml::activeTextField($profile, 'city');
    echo CHtml::error($profile, 'city');
    echo CHtml::closeTag('div');


    echo CHtml::openTag('div', array());
    echo CHtml::activeLabelEx($profile, 'state', array('label' => 'State/Province:'));
    echo CHtml::activeDropDownList($profile, 'state', array(
        "AL" => "Alabama",
        "AK" => "Alaska",
        "AZ" => "Arizona",
        "AR" => "Arkansas",
        "CA" => "California",
        "CO" => "Colorado",
        "CT" => "Connecticut",
        "DE" => "Delaware",
        "DC" => "District of Columbia",
        "FL" => "Florida",
        "GA" => "Georgia",
        "HI" => "Hawaii",
        "ID" => "Idaho",
        "IL" => "Illinois",
        "IN" => "Indiana",
        "IA" => "Iowa",
        "KS" => "Kansas",
        "KY" => "Kentucky",
        "LA" => "Louisiana",
        "ME" => "Maine",
        "MD" => "Maryland",
        "MA" => "Massachusetts",
        "MI" => "Michigan",
        "MN" => "Minnesota",
        "MS" => "Mississippi",
        "MO" => "Missouri",
        "MT" => "Montana",
        "NE" => "Nebraska",
        "NV" => "Nevada",
        "NH" => "New Hampshire",
        "NJ" => "New Jersey",
        "NM" => "New Mexico",
        "NY" => "New York",
        "NC" => "North Carolina",
        "ND" => "North Dakota",
        "OH" => "Ohio",
        "OK" => "Oklahoma",
        "OR" => "Oregon",
        "PA" => "Pennsylvania",
        "RI" => "Rhode Island",
        "SC" => "South Carolina",
        "SD" => "South Dakota",
        "TN" => "Tennessee",
        "TX" => "Texas",
        "UT" => "Utah",
        "VT" => "Vermont",
        "VA" => "Virginia",
        "WA" => "Washington",
        "WV" => "West Virginia",
        "WI" => "Wisconsin",
        "WY" => "Wyoming",
        "" => "----------------------",
        "AB" => "Alberta",
        "BC" => "British Columbia",
        "MB" => "Manitoba",
        "NB" => "New Brunswick",
        "NL" => "Newfoundland",
        "NT" => "Northwest Territory",
        "NS" => "Nova Scotia",
        "ON" => "Ontario",
        "PE" => "Prince Edward Island",
        "QC" => "Quebec",
        "SK" => "Saskatchewan",
        "YT" => "Yukon Territory"
    )); 
    echo CHtml::error($profile, 'state');
    echo CHtml::closeTag('div');
   
    echo CHtml::openTag('div', array());
    echo CHtml::activeLabelEx($profile, 'country', array('label' => 'Country:'));
    echo CHtml::activeDropDownList($profile, 'country', array('CANADA' => 'Candada', 'US' => 'United States'));
    echo CHtml::error($profile, 'country');
    echo CHtml::closeTag('div');

    echo CHtml::openTag('div', array());
    echo CHtml::activeLabelEx($profile, 'postal_code', array('label' => 'Zip/Postal Code:'));
    echo CHtml::activeTextField($profile, 'postal_code');
    echo CHtml::error($profile, 'postal_ciode');
    echo CHtml::closeTag('div');
}
/* foreach(YumProfile::getProfileFields() as $field) {
  echo CHtml::openTag('div',array());

  echo CHtml::activeLabelEx($profile, $field);
  echo CHtml::activeTextField($profile,
  $field);
  echo CHtml::error($profile,$field);

  echo CHtml::closeTag('div');
  } */
?>

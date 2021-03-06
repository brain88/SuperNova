<?php

/*
#############################################################################
#  Filename: mercenary.mo
#  Project: SuperNova.WS
#  Website: http://www.supernova.ws
#  Description: Massive Multiplayer Online Browser Space Startegy Game
#
#  Copyright © 2009 Gorlum for Project "SuperNova.WS"
#############################################################################
*/

/**
*
* @package language
* @system [English]
* @version 33a13
*
* @clean - all constants is used
*
*/

/**
* DO NOT CHANGE
*/

if (!defined('INSIDE'))
{
  exit;
}

if (empty($lang) || !is_array($lang))
{
  $lang = array();
}

// Officers/mercenaries
$lang = array_merge($lang, array(
  'mrc_period_list' => array(
    PERIOD_MINUTE    => '1 minute',
    PERIOD_MINUTE_3  => '3 minutes',
    PERIOD_MINUTE_5  => '5 minutes',
    PERIOD_MINUTE_10 => '10 minutes',
    PERIOD_DAY       => '1 day',
    PERIOD_DAY_3     => '3 days',
    PERIOD_WEEK      => '1 week',
    PERIOD_WEEK_2    => '2 weeks',
    PERIOD_MONTH     => '30 days',
    PERIOD_MONTH_2   => '60 days',
    PERIOD_MONTH_3   => '90 days',
  ),

  'mrc_up_to' => 'up to',
  'mrc_hire' => 'Hire',
  'mrc_hire_for' => 'Hire for',
  'mrc_msg_error_wrong_mercenary' => 'Wrong mercenary',
  'mrc_msg_error_wrong_level' => 'Wrong mercenary level - too big or too small',
  'mrc_msg_error_wrong_period' => 'Unacceptable hire period',
  'mrc_msg_error_already_hired' => 'Mercenary already hired. Wait until hire period ends',
  'mrc_msg_error_no_resource' => 'Not enough Dark Matter to hire mercenary',
  'mrc_msg_error_requirements' => 'Requirements not met',

  'mrc_dismiss' => 'Dismiss',
  'mrc_dismiss_confirm' => 'When you dismissing mercenary you loose all DM that you spent for hiring this merc before! Are you sure that you want do dismiss mercenary?',

));

?>

<?php
/*
 * common.php
 *
 * Common init file
 *
 * @version 1.1 Security checks by Gorlum for http://supernova.ws
 */

require_once('includes/init.php');

$user = sn_autologin(!$allow_anonymous);
$sys_user_logged_in = $user && is_array($user) && isset($user['id']) && $user['id'];

lng_switch($force_lang);

if($config->game_disable)
{
  $disable_reason = sys_bbcodeParse($config->game_disable_reason);
  if ($user['authlevel'] < 1 || !(defined('IN_ADMIN') && IN_ADMIN))
  {
    message($disable_reason, $config->game_name);
    ob_end_flush();
    die();
  }
  else
  {
    print("<div align=center style='font-size: 24; font-weight: bold; color:red;'>{$disable_reason}</div><br>");
  }
}

if(
  !($allow_anonymous || $sys_user_logged_in) ||
  (defined('IN_ADMIN') && IN_ADMIN && $user['authlevel'] < 1)
)
{
  setcookie($config->COOKIE_NAME, '', time() - PERIOD_WEEK);
  header('Location: ' . SN_ROOT_VIRTUAL .'login.php');
  ob_end_flush();
  die();
}

if($user['authlevel'] >= 2 && file_exists(SN_ROOT_PHYSICAL . 'badqrys.txt') && @filesize(SN_ROOT_PHYSICAL . 'badqrys.txt') > 0)
{
  echo "<a href=\"badqrys.txt\" target=\"_NEW\"><font color=\"red\">{$lang['ov_hack_alert']}</font</a>";
}

if (defined('IN_ADMIN') && IN_ADMIN)
{
  $UserSkin  = $user['dpath'];
  $local     = stristr ( $UserSkin, "http:");
  if ($local === false)
  {
    if (!$user['dpath'])
    {
      $dpath     = "../". DEFAULT_SKINPATH  ;
    }
    else
    {
      $dpath     = "../". $user["dpath"];
    }
  }
  else
  {
    $dpath     = $UserSkin;
  }

  lng_include('admin');
}
elseif($sys_user_logged_in)
{
  $dpath = $user["dpath"] ? $user["dpath"] : DEFAULT_SKINPATH;

  if(!$skip_fleet_update && $time_now - $config->flt_lastUpdate >= 4)
  {
    require_once("includes/includes/flt_flying_fleet_handler.php");
    flt_flying_fleet_handler($config, $skip_fleet_update);
/*
  $flt_update_mode = 0;
  // 0 - old
  // 1 - new
  switch($flt_update_mode)
  {
    case 0:
      if($time_now - $config->flt_lastUpdate <= 4)
      {
        return;
      }
    break;

    case 1:
      if($config->flt_lastUpdate)
      {
        if($time_now - $config->flt_lastUpdate <= 15)
        {
          return;
        }
        else
        {
          $GLOBALS['debug']->error('Flying fleet handler is on timeout', 'FFH Error', 504);
        }
      }
    break;
  }
*/
  }

  $planet_id = SetSelectedPlanet($user);

  doquery('START TRANSACTION;');
  eco_bld_que_tech($user);

  if($user['ally_id'])
  {
    sn_ali_fill_user_ally($user);
    if(!$user['ally']['player']['id'])
    {
      $debug->error("User ID {$user['id']} has ally ID {$user['ally_id']} but no ally info", 'User record error', 502);
    }
    eco_bld_que_tech($user['ally']['player']);
    doquery("UPDATE `{{users}}` SET `onlinetime` = {$time_now} WHERE `id` = '{$user['ally']['player']['id']}' LIMIT 1;");
  }
  doquery('COMMIT;');

  doquery('START TRANSACTION;');
  $global_data = sys_o_get_updated($user, $planet_id, $time_now);
  if(!$global_data['planet'])
  {
    doquery("UPDATE {{users}} SET `current_planet` = '{$user['id_planet']}' WHERE `id` = '{$user['id']}' LIMIT 1;");
    $global_data = sys_o_get_updated($user, $user['id_planet'], $time_now);
  }
  doquery('COMMIT;');

  if(!$global_data)
  {
    $debug->error("User ID {$user['id']} has no current planet and no homeworld", 'User record error', 502);
  }

  $planetrow = $global_data['planet'];
  if(!($planetrow && isset($planetrow['id']) && $planetrow['id']))
  {
    header('Location: login.php');
    ob_end_flush();
    die();
  }

  $que = $global_data['que'];

  if(!$allow_anonymous)
  {
    sys_user_vacation($user);
  }
}

?>

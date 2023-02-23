<?php

function ii_isMobileAgent()
{
  $bool = false;
  $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
  if (strpos($userAgent, 'android') && strpos($userAgent, 'mobile')) $bool = true;
  elseif (strpos($userAgent, 'iphone')) $bool = true;
  elseif (strpos($userAgent, 'ipod')) $bool = true;
  elseif (strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false) $bool = true;
  return $bool;
}
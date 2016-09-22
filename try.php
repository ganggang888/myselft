<?php

$b = '<presence from="zhuanjia2@xmpp.mattservice.com/d35a3735" />';
$do = simplexml_load_string($b);
$c = '<presence type="unavailable" from="18017309891@xmpp.mattservice.com">
  <status>Unavailable</status> 
  </presence>';
$co = simplexml_load_string($c);
$oo = '<presence type="error" from="1801730989111@xmpp.mattservice.com">
<error code="403" type="auth">
  <forbidden xmlns="urn:ietf:params:xml:ns:xmpp-stanzas" /> 
  </error>
  </presence>';
  var_dump($oo);exit;
$a = str_replace('-', '', $l);
var_dump($a);exit;
$d = simplexml_load_string('');
var_dump($do);
var_dump($co);
var_dump($d);
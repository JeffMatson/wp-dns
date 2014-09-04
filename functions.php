<?php
/**
* Defines DNS/whois variables.
*
* @access 
* @param 
* @param 
* @param 
* @return void
*/

add_action('admin_menu', 'dns_menu');

/**
* wp_dns_menu function.
*
* @access public
* @return void
*/
function dns_menu() { 
        add_menu_page('DNS Check', 'DNS Check', 'administrator', 'dns', 'wp_dns'); 
} 

/**
* wp_dns function.
*
* @access public
* @return void
*/ 
function wp_dns() { 

  $domain = $_SERVER['SERVER_NAME']; 
  $alldns = dns_get_record ($domain, DNS_ALL, $authns); 
 
  // Create Arrays of DNS Records 
  $ns = array(); 
  $mx = array(); 
  $a = array(); 
  $txt = array(); 
 
  foreach ( $alldns as $x ) {  
   if ($x["type"] == "A") { 
        array_push($a, $x["ip"]); 
   }
   if ($x["type"] == "NS") {
        array_push($ns, $x["target"]);
   }
   if ($x["type"] == "MX") {
        array_push($mx, $x["target"]);
   }
   if ($x["type"] == "TXT") {
        foreach ( $x["entries"] as $y) {
         array_push($txt, $y);
        }
   }
  }
    
  // Print Domain
  echo "<h1>Domain</h1>" . $domain . "<br />";
  
  // Print Name Servers
  echo "<h1>Name Servers</h1>";
  foreach ( $ns as $x ) { 
   echo nl2br($x . "\n");
  }
  
  // Print A Records
  echo "<h1>A Records</h1>";
  foreach ( $a as $x ) {
   echo nl2br($x . " with reverse lookup " . gethostbyaddr($x) . "\n");
  }
  
  // Print MX Records
  echo "<h1>MX Records</h1>";
  foreach ($mx as $x) {
   echo nl2br($x . " with IP " . gethostbyname($x) . " and reverse lookup " . gethostbyaddr(gethostbyname($x)) . "\n");
  }

  // Print TXT Records
  echo "<h1>TXT Records</h1>";
  foreach ($txt as $x) {
   echo nl2br($x . "\n");
  }

  wp_whois();
}

/**
* wp_whois function.
*
* @access public
* @return void
*/
function wp_whois() {
  $domain = $_SERVER['SERVER_NAME'];
  require("whoisClass.php");
  $whois=new Whois;
  echo "<h1>WHOIS Info</h1>";
  echo "<pre>"; echo $whois->whoislookup($domain); echo "</pre>";
}

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

add_action( 'admin_menu', 'dns_menu' );

/**
* wp_dns_menu function.
*
* @access public
* @return void
*/
function dns_menu() {
        add_submenu_page(
        'tools.php',
        __( 'DNS Check', 'dns' ),
        __( 'DNS check', 'dns' ),
        'administrator',
        'dns',
        'wp_dns',
        99
        );
}

/**
* wp_dns function.
*
* @access public
* @return void
*/
function wp_dns() {
  $domain = $_SERVER['SERVER_NAME'];
  $alldns = dns_get_record ( $domain, DNS_ALL, $authns );

  // Create Arrays of DNS Records
  $ns   = array();
  $mx   = array();
  $a    = array();
  $txt  = array();

  foreach ( $alldns as $dns_records ) {
    if ($dns_records['type'] == 'A') {
      $a[] = $dns_records['ip'];
    }
    elseif ($dns_records['type'] == 'NS') {
      $ns[] = $dns_records['target'];
    }
    elseif ($dns_records['type'] == 'MX') {
      $mx[] = $dns_records['target'];
    }
    elseif ($dns_records['type'] == 'TXT') {
      foreach ( $dns_records['entries'] as $txt_entries) {
        $txt[] = $txt_entries;
      }
    }
  }

  // Print Domain
  echo '<h1>Domain</h1>' . $domain . '<br />';

  // Print Name Servers
  echo '<h1>Name Servers</h1>';
  foreach ( $ns as $get_ns ) {
    echo $get_ns, PHP_EOL;
  }

  // Print A Records
  echo '<h1>A Records</h1>';
  foreach ( $a as $get_a ) {
    echo $get_a . ' with reverse lookup ' . gethostbyaddr( $get_a ), PHP_EOL;
  }

  // Print MX Records
  echo '<h1>MX Records</h1>';
  foreach ( $mx as $get_mx ) {
    echo $get_mx . ' with IP ' . gethostbyname( $get_mx ) . ' and reverse lookup ' . gethostbyaddr( gethostbyname( $get_mx ) ), PHP_EOL;
  }

  // Print TXT Records
  echo '<h1>TXT Records</h1>';
  foreach ( $txt as $get_txt ) {
    echo $get_txt, PHP_EOL;
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
  require_once( 'whoisClass.php' );
  $whois = new Whois;
  echo '<h1>WHOIS Info</h1>';
  echo '<pre>';
  echo $whois->whoislookup( $domain );
  echo '</pre>';
}

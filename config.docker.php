<?php
/**
 * Import ENV settings for Docker containers.
 *   ln -s config.docker.php config.php
 */

function file_env($var, $default) {
    $env_filename = getenv($var.'_FILE');

    if ($env_filename===false) {
        return getenv($var) ?: $default;
	} elseif (is_readable($env_filename)) {
        return trim(file_get_contents($env_filename), "\n\r");
    } else {
        // no i10n, gettext not yet loaded
        error_log("$var:$env_filename can not be read");
        return $default;
    }
}

# set connection type
# 	api,mysql;
# ******************************/
$config['type'] = file_env('IPAM_AGENT_TYPE', "mysql");

# set agent key
# ******************************/
$config['key'] = file_env('IPAM_KEY', "aad984d8314fcf644d3fb46886ea461f"); 

# set scan method and path to ping file
#	ping, fping or pear
# ******************************/
//$config['method'] 	= "pear";
//$config['pingpath'] = "/sbin/ping";

$config['method'] 	= "fping";
$config['pingpath'] = "/usr/bin/fping";

# permit non-threaded checks (default: false)
# ******************************/
$config['nonthreaded'] = false;

# how many concurrent threads (default: 32)
# ****************************************/
$config['threads']  = 32;

# api settings, if api selected
# ******************************/
$config['api']['key'] = file_env('IPAM_API_KEY', "");

# send mail diff
# ******************************/
$config['sendmail'] = boolval(file_env('IPAM_SENDMAIL', false));

# remove inactive DHCP addresses
#
# 	reset_autodiscover_addresses: will remove addresses if description -- autodiscovered -- and is offline
# 	remove_inactive_dhcp		: will remove inactive dhcp addresses
# ******************************/
$config['reset_autodiscover_addresses'] = boolval(file_env('IPAM_REMOVE_AUTODISCOVER', false));
$config['remove_inactive_dhcp']         = boolval(file_env('IPAM_INACTIVE_DHCP', false));


# mysql db settings, if mysql selected
# ******************************/
$config['db']['host'] = file_env('IPAM_DATABASE_HOST', "localhost");
$config['db']['user'] = file_env('IPAM_DATABASE_USER', "phpipam");
$config['db']['pass'] = file_env('IPAM_DATABASE_PASS', "password");
$config['db']['name'] = file_env('IPAM_DATABASE_NAME', "phpipam");
$config['db']['port'] = file_env('IPAM_DATABASE_PORT', 3306);

/**
 *  SSL options for MySQL
 *
 See http://php.net/manual/en/ref.pdo-mysql.php
     https://dev.mysql.com/doc/refman/5.7/en/ssl-options.html

     Please update these settings before setting 'ssl' to true.
     All settings can be commented out or set to NULL if not needed

     php 5.3.7 required
 ******************************/
$config['db']['ssl']        = boolval(file_env('SSL_ENABLED', false));                               // true/false, enable or disable SSL as a whole
$config['db']['ssl_key']    = file_env('SSL_CLIENT_KEY', '');                               // path to an SSL key file. Only makes sense combined with ssl_cert
$config['db']['ssl_cert']   = file_env('SSL_CLIENT_CERT', '');                              // path to an SSL certificate file. Only makes sense combined with ssl_key
$config['db']['ssl_ca']     = file_env('SSL_CA_CERT', '/phpipam-agent/ssl/ca.crt');         // path to a file containing SSL CA certs
$config['db']['ssl_capath'] = file_env('SSL_CA_PATH', '');                                  // path to a directory containing CA certs
$config['db']['ssl_cipher'] = file_env('SSL_CIPHERS', '');                                  // one or more SSL Ciphers
$config['db']['ssl_verify'] = boolval(file_env('SSL_VERIFY', false));                                // Verify Common Name (CN) of server certificate?



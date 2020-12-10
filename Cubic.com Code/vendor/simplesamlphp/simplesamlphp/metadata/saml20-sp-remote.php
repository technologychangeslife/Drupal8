<?php
/**
 * SAML 2.0 remote SP metadata for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote
 */

$metadata_url_for = array(
    'cubic-okta-dev' => 'https://cubic.oktapreview.com/app/exkbmlzqun7sQEnNr0h7/sso/saml/metadata',
    'cubic-okta-stage' => 'https://cubic.oktapreview.com/app/exkbmm0u8cf5svoeC0h7/sso/saml/metadata',
    'cubic-okta-prod' => 'https://cubic.okta.com/app/exkj1px1rhs5ttVJZ0x7/sso/saml/metadata',
);

foreach($metadata_url_for as $idp_name => $metadata_url) {
  /*
   * Fetch SAML metadata from the URL.
   * NOTE:
   *  SAML metadata changes very rarely. On a production system,
   *  this data should be cached as approprate for your production system.
   */
  $metadata_xml = file_get_contents($metadata_url);

  /*
   * Parse the SAML metadata using SimpleSAMLphp's parser.
   * See also: modules/metaedit/www/edit.php:34
   */
  SimpleSAML_Utilities::validateXMLDocument($metadata_xml, 'saml-meta');
  $entities = SimpleSAML_Metadata_SAMLParser::parseDescriptorsString($metadata_xml);
  $entity = array_pop($entities);
  $idp = $entity->getMetadata20IdP();
  $entity_id = $idp['entityid'];

  /*
   * Remove HTTP-POST endpoints from metadata,
   * since we only want to make HTTP-GET AuthN requests.
   */
  for($x = 0; $x < sizeof($idp['SingleSignOnService']); $x++) {
    $endpoint = $idp['SingleSignOnService'][$x];
    if($endpoint['Binding'] == 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST') {
      unset($idp['SingleSignOnService'][$x]);
    }
  }

  /*
   * Don't sign AuthN requests.
   */
  if(isset($idp['sign.authnrequest'])) {
    unset($idp['sign.authnrequest']);
  }

  $metadata[$entity_id] = $idp;
}


//// Freshform Dev
//$metadata['http://www.okta.com/exk4oao4a9LBxgkSD2p6'] = array (
//  'entityid' => 'http://www.okta.com/exk4oao4a9LBxgkSD2p6',
//  'contacts' =>
//    array (
//    ),
//  'metadata-set' => 'saml20-idp-remote',
//  'SingleSignOnService' =>
//    array (
//      0 =>
//        array (
//          'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
//          'Location' => 'https://freshform.okta.com/app/freshform_ffdrupaldev_1/exk4oao4a9LBxgkSD2p6/sso/saml',
//        ),
//      1 =>
//        array (
//          'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
//          'Location' => 'https://freshform.okta.com/app/freshform_ffdrupaldev_1/exk4oao4a9LBxgkSD2p6/sso/saml',
//        ),
//    ),
//  'SingleLogoutService' =>
//    array (
//    ),
//  'ArtifactResolutionService' =>
//    array (
//    ),
//  'NameIDFormats' =>
//    array (
//      0 => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
//      1 => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
//    ),
//  'keys' =>
//    array (
//      0 =>
//        array (
//          'encryption' => false,
//          'signing' => true,
//          'type' => 'X509Certificate',
//          'X509Certificate' => 'MIIDojCCAoqgAwIBAgIGAV8QoegUMA0GCSqGSIb3DQEBBQUAMIGRMQswCQYDVQQGEwJVUzETMBEG
//A1UECAwKQ2FsaWZvcm5pYTEWMBQGA1UEBwwNU2FuIEZyYW5jaXNjbzENMAsGA1UECgwET2t0YTEU
//MBIGA1UECwwLU1NPUHJvdmlkZXIxEjAQBgNVBAMMCWZyZXNoZm9ybTEcMBoGCSqGSIb3DQEJARYN
//aW5mb0Bva3RhLmNvbTAeFw0xNzEwMTIxMjQ4MjdaFw0yNzEwMTIxMjQ5MjdaMIGRMQswCQYDVQQG
//EwJVUzETMBEGA1UECAwKQ2FsaWZvcm5pYTEWMBQGA1UEBwwNU2FuIEZyYW5jaXNjbzENMAsGA1UE
//CgwET2t0YTEUMBIGA1UECwwLU1NPUHJvdmlkZXIxEjAQBgNVBAMMCWZyZXNoZm9ybTEcMBoGCSqG
//SIb3DQEJARYNaW5mb0Bva3RhLmNvbTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAIdx
//Eg/D1wzpFgPPAXgjnwuyuWTxx45Y3EGIoATRcKBd2v1IRu36UK4vFbPTIst2kURpjwWt9Us3pX1L
//aLZj5dw0aVlJ9vYaopBzVLdsQVeWXIw883PtOWWIO8VPkyirQvyd/rTN+WIbUyFm791qo7NUxh/J
//OSattQz2tf5gbAXe8WF6AQSYY0jklJpdqdmQ2cE+IiCmuAlv7NDpwK/RWm6o3Dlsu3/9a4fZVx52
//4P9bromiyBtSMeSCgqSswOluReuqXNskFf7W/hXWXLj0Q4CyDmJu5Pelli9PyZHlFe5QW4zqlTf3
//MrZxSPiC5ZJ8mv/6xu6wKd81bjxJD1I1V+8CAwEAATANBgkqhkiG9w0BAQUFAAOCAQEALnH0Ch+6
//pB8J695ZvJl1K9QhhJC85OLNcFd6gl6hanwqb5zg7BUhjovx67Uq85kGo+YCXVnxav0aKB9sBirT
//0NXmQNYIPkJkT+2qenMKIfbZ7ocCQv4jGVIE4R87wAcOX1n32Zl0enQ+Q9VDpHs48qSgytskm1cS
//8zyKO+EVBMWiXELFBwckCxHJ6xo12Gb3mORq6eBWO90Yov2VMGgeIm6wnIQvMxMsmYbnxmL3AODz
//tRkkyvlbbL1OmpD2rLyZqtOz9PbU23tOtBPHLYiW/cYZSm/X/vk+sZGzeYKqJsmArx/kw2SFJteP
//C8qwh7qhxZjRHMQyHHeIgliTqMrsyA==',
//        ),
//    ),
//);
//
//// Cubic Dev
//$metadata['http://www.okta.com/exkbmlzqun7sQEnNr0h7'] = array (
//  'entityid' => 'http://www.okta.com/exkbmlzqun7sQEnNr0h7',
//  'contacts' =>
//    array (
//    ),
//  'metadata-set' => 'saml20-idp-remote',
//  'SingleSignOnService' =>
//    array (
//      0 =>
//        array (
//          'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
//          'Location' => 'https://cubic.oktapreview.com/app/cubic_drupaladmindev_1/exkbmlzqun7sQEnNr0h7/sso/saml',
//        ),
//      1 =>
//        array (
//          'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
//          'Location' => 'https://cubic.oktapreview.com/app/cubic_drupaladmindev_1/exkbmlzqun7sQEnNr0h7/sso/saml',
//        ),
//    ),
//  'SingleLogoutService' =>
//    array (
//    ),
//  'ArtifactResolutionService' =>
//    array (
//    ),
//  'NameIDFormats' =>
//    array (
//      0 => 'urn:oasis:names:tc:SAML:1.1:nameid-format:NameID',
//      1 => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
//    ),
//  'keys' =>
//    array (
//      0 =>
//        array (
//          'encryption' => false,
//          'signing' => true,
//          'type' => 'X509Certificate',
//          'X509Certificate' => 'MIIDmjCCAoKgAwIBAgIGAV2Sgp26MA0GCSqGSIb3DQEBCwUAMIGNMQswCQYDVQQGEwJVUzETMBEG
//A1UECAwKQ2FsaWZvcm5pYTEWMBQGA1UEBwwNU2FuIEZyYW5jaXNjbzENMAsGA1UECgwET2t0YTEU
//MBIGA1UECwwLU1NPUHJvdmlkZXIxDjAMBgNVBAMMBWN1YmljMRwwGgYJKoZIhvcNAQkBFg1pbmZv
//QG9rdGEuY29tMB4XDTE3MDczMDA3NTkxOVoXDTI3MDczMDA4MDAxOVowgY0xCzAJBgNVBAYTAlVT
//MRMwEQYDVQQIDApDYWxpZm9ybmlhMRYwFAYDVQQHDA1TYW4gRnJhbmNpc2NvMQ0wCwYDVQQKDARP
//a3RhMRQwEgYDVQQLDAtTU09Qcm92aWRlcjEOMAwGA1UEAwwFY3ViaWMxHDAaBgkqhkiG9w0BCQEW
//DWluZm9Ab2t0YS5jb20wggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCdK4WIFyZzoBq0
//A77cVHAkFkp52QOq86oSgOGNnKyP0DNvg7SujCuMG3uyQonE5WdBEEvHpmiMD4LwXjE3LyAw4C/J
//jXrpBlJzSaVLeJF4IWIGS30rvLharZC7ExBmWmzfaUSGDqxQg9F+VEp/uUCGcZD5eYDssDU34r+M
//uDsRwBM9gF1jBP+zXaAmF+6BDCBWciM+4fWJjmYYXhbxGj5tNBAM1sXXHja2bDLwuzN7ArzGb1T+
//lhpYSJXUNIzqNtPqgbfhgmJTz/LcKYv59AViKM8YMxUTEsRNjtXv+VcBrOW2JzCTbsaXF9LHTToN
//TKT6q8LqecaZWsQqAmZIAq4ZAgMBAAEwDQYJKoZIhvcNAQELBQADggEBABklwo3wj3B1jaeWAv77
//jOMzu6mRobMt576NAL9UdsTDLuEBDy4yEp69QFBUBhv8SMJ5w7A/cGlvw7Dww7D3ruSplhC679+C
//UxSSP5qsGU8IPTMRxo8PFBEf+sJ372SBWCWlEpo+DXD3Ln0d3lGEqHMbJAELK0B4rYiRL+XjEcEe
//n0eiWa1dOu+6bPjTRPJ5iVIzUL5n8HgjneKzmxu1DFkceFQ0L+xpMy5dpOSFyXztD7if9hrAbyQh
//eB6eWVXQcPPw0tv+6lX3xLCIcLG94nBOlcc6Wt4h+6ThwDFJslI31MueNqGjJfEH+m7rvOXbQHva
//SoXuiEBrXBKtS1wpQI0=',
//        ),
//    ),
//);

//$metadata['https://idp.ssocircle.com'] = array (
//  'entityid' => 'https://idp.ssocircle.com',
//  'contacts' =>
//    array (
//    ),
//  'metadata-set' => 'saml20-idp-remote',
//  'SingleSignOnService' =>
//    array (
//      0 =>
//        array (
//          'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
//          'Location' => 'https://idp.ssocircle.com:443/sso/SSORedirect/metaAlias/publicidp',
//        ),
//      1 =>
//        array (
//          'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
//          'Location' => 'https://idp.ssocircle.com:443/sso/SSOPOST/metaAlias/publicidp',
//        ),
//      2 =>
//        array (
//          'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
//          'Location' => 'https://idp.ssocircle.com:443/sso/SSOSoap/metaAlias/publicidp',
//        ),
//    ),
//  'SingleLogoutService' =>
//    array (
//      0 =>
//        array (
//          'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
//          'Location' => 'https://idp.ssocircle.com:443/sso/IDPSloRedirect/metaAlias/publicidp',
//          'ResponseLocation' => 'https://idp.ssocircle.com:443/sso/IDPSloRedirect/metaAlias/publicidp',
//        ),
//      1 =>
//        array (
//          'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
//          'Location' => 'https://idp.ssocircle.com:443/sso/IDPSloPost/metaAlias/publicidp',
//          'ResponseLocation' => 'https://idp.ssocircle.com:443/sso/IDPSloPost/metaAlias/publicidp',
//        ),
//      2 =>
//        array (
//          'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
//          'Location' => 'https://idp.ssocircle.com:443/sso/IDPSloSoap/metaAlias/publicidp',
//        ),
//    ),
//  'ArtifactResolutionService' =>
//    array (
//      0 =>
//        array (
//          'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
//          'Location' => 'https://idp.ssocircle.com:443/sso/ArtifactResolver/metaAlias/publicidp',
//          'index' => 0,
//          'isDefault' => true,
//        ),
//    ),
//  'NameIDFormats' =>
//    array (
//      0 => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
//      1 => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',
//      2 => 'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
//      3 => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
//      4 => 'urn:oasis:names:tc:SAML:2.0:nameid-format:kerberos',
//    ),
//  'keys' =>
//    array (
//      0 =>
//        array (
//          'encryption' => false,
//          'signing' => true,
//          'type' => 'X509Certificate',
//          'X509Certificate' => '
//MIIEYzCCAkugAwIBAgIDIAZmMA0GCSqGSIb3DQEBCwUAMC4xCzAJBgNVBAYTAkRF
//MRIwEAYDVQQKDAlTU09DaXJjbGUxCzAJBgNVBAMMAkNBMB4XDTE2MDgwMzE1MDMy
//M1oXDTI2MDMwNDE1MDMyM1owPTELMAkGA1UEBhMCREUxEjAQBgNVBAoTCVNTT0Np
//cmNsZTEaMBgGA1UEAxMRaWRwLnNzb2NpcmNsZS5jb20wggEiMA0GCSqGSIb3DQEB
//AQUAA4IBDwAwggEKAoIBAQCAwWJyOYhYmWZF2TJvm1VyZccs3ZJ0TsNcoazr2pTW
//cY8WTRbIV9d06zYjngvWibyiylewGXcYONB106ZNUdNgrmFd5194Wsyx6bPvnjZE
//ERny9LOfuwQaqDYeKhI6c+veXApnOfsY26u9Lqb9sga9JnCkUGRaoVrAVM3yfghv
///Cg/QEg+I6SVES75tKdcLDTt/FwmAYDEBV8l52bcMDNF+JWtAuetI9/dWCBe9VTC
//asAr2Fxw1ZYTAiqGI9sW4kWS2ApedbqsgH3qqMlPA7tg9iKy8Yw/deEn0qQIx8Gl
//VnQFpDgzG9k+jwBoebAYfGvMcO/BDXD2pbWTN+DvbURlAgMBAAGjezB5MAkGA1Ud
//EwQCMAAwLAYJYIZIAYb4QgENBB8WHU9wZW5TU0wgR2VuZXJhdGVkIENlcnRpZmlj
//YXRlMB0GA1UdDgQWBBQhAmCewE7aonAvyJfjImCRZDtccTAfBgNVHSMEGDAWgBTA
//1nEA+0za6ppLItkOX5yEp8cQaTANBgkqhkiG9w0BAQsFAAOCAgEAAhC5/WsF9ztJ
//Hgo+x9KV9bqVS0MmsgpG26yOAqFYwOSPmUuYmJmHgmKGjKrj1fdCINtzcBHFFBC1
//maGJ33lMk2bM2THx22/O93f4RFnFab7t23jRFcF0amQUOsDvltfJw7XCal8JdgPU
//g6TNC4Fy9XYv0OAHc3oDp3vl1Yj8/1qBg6Rc39kehmD5v8SKYmpE7yFKxDF1ol9D
//KDG/LvClSvnuVP0b4BWdBAA9aJSFtdNGgEvpEUqGkJ1osLVqCMvSYsUtHmapaX3h
//iM9RbX38jsSgsl44Rar5Ioc7KXOOZFGfEKyyUqucYpjWCOXJELAVAzp7XTvA2q55
//u31hO0w8Yx4uEQKlmxDuZmxpMz4EWARyjHSAuDKEW1RJvUr6+5uA9qeOKxLiKN1j
//o6eWAcl6Wr9MreXR9kFpS6kHllfdVSrJES4ST0uh1Jp4EYgmiyMmFCbUpKXifpsN
//WCLDenE3hllF0+q3wIdu+4P82RIM71n7qVgnDnK29wnLhHDat9rkC62CIbonpkVY
//mnReX0jze+7twRanJOMCJ+lFg16BDvBcG8u0n/wIDkHHitBI7bU1k6c6DydLQ+69
//h8SCo6sO9YuD+/3xAGKad4ImZ6vTwlB4zDCpu6YgQWocWRXE+VkOb+RBfvP755PU
//aLfL63AFVlpOnEpIio5++UjNJRuPuAA=
//                   ',
//        ),
//      1 =>
//        array (
//          'encryption' => true,
//          'signing' => false,
//          'type' => 'X509Certificate',
//          'X509Certificate' => '
//MIIEYzCCAkugAwIBAgIDIAZmMA0GCSqGSIb3DQEBCwUAMC4xCzAJBgNVBAYTAkRF
//MRIwEAYDVQQKDAlTU09DaXJjbGUxCzAJBgNVBAMMAkNBMB4XDTE2MDgwMzE1MDMy
//M1oXDTI2MDMwNDE1MDMyM1owPTELMAkGA1UEBhMCREUxEjAQBgNVBAoTCVNTT0Np
//cmNsZTEaMBgGA1UEAxMRaWRwLnNzb2NpcmNsZS5jb20wggEiMA0GCSqGSIb3DQEB
//AQUAA4IBDwAwggEKAoIBAQCAwWJyOYhYmWZF2TJvm1VyZccs3ZJ0TsNcoazr2pTW
//cY8WTRbIV9d06zYjngvWibyiylewGXcYONB106ZNUdNgrmFd5194Wsyx6bPvnjZE
//ERny9LOfuwQaqDYeKhI6c+veXApnOfsY26u9Lqb9sga9JnCkUGRaoVrAVM3yfghv
///Cg/QEg+I6SVES75tKdcLDTt/FwmAYDEBV8l52bcMDNF+JWtAuetI9/dWCBe9VTC
//asAr2Fxw1ZYTAiqGI9sW4kWS2ApedbqsgH3qqMlPA7tg9iKy8Yw/deEn0qQIx8Gl
//VnQFpDgzG9k+jwBoebAYfGvMcO/BDXD2pbWTN+DvbURlAgMBAAGjezB5MAkGA1Ud
//EwQCMAAwLAYJYIZIAYb4QgENBB8WHU9wZW5TU0wgR2VuZXJhdGVkIENlcnRpZmlj
//YXRlMB0GA1UdDgQWBBQhAmCewE7aonAvyJfjImCRZDtccTAfBgNVHSMEGDAWgBTA
//1nEA+0za6ppLItkOX5yEp8cQaTANBgkqhkiG9w0BAQsFAAOCAgEAAhC5/WsF9ztJ
//Hgo+x9KV9bqVS0MmsgpG26yOAqFYwOSPmUuYmJmHgmKGjKrj1fdCINtzcBHFFBC1
//maGJ33lMk2bM2THx22/O93f4RFnFab7t23jRFcF0amQUOsDvltfJw7XCal8JdgPU
//g6TNC4Fy9XYv0OAHc3oDp3vl1Yj8/1qBg6Rc39kehmD5v8SKYmpE7yFKxDF1ol9D
//KDG/LvClSvnuVP0b4BWdBAA9aJSFtdNGgEvpEUqGkJ1osLVqCMvSYsUtHmapaX3h
//iM9RbX38jsSgsl44Rar5Ioc7KXOOZFGfEKyyUqucYpjWCOXJELAVAzp7XTvA2q55
//u31hO0w8Yx4uEQKlmxDuZmxpMz4EWARyjHSAuDKEW1RJvUr6+5uA9qeOKxLiKN1j
//o6eWAcl6Wr9MreXR9kFpS6kHllfdVSrJES4ST0uh1Jp4EYgmiyMmFCbUpKXifpsN
//WCLDenE3hllF0+q3wIdu+4P82RIM71n7qVgnDnK29wnLhHDat9rkC62CIbonpkVY
//mnReX0jze+7twRanJOMCJ+lFg16BDvBcG8u0n/wIDkHHitBI7bU1k6c6DydLQ+69
//h8SCo6sO9YuD+/3xAGKad4ImZ6vTwlB4zDCpu6YgQWocWRXE+VkOb+RBfvP755PU
//aLfL63AFVlpOnEpIio5++UjNJRuPuAA=
//                    ',
//        ),
//    ),
//);

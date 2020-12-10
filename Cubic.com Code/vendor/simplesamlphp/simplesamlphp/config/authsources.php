<?php

/**
 * Default authsources at the top
 * Additional Acquia Cloud overrides at the bottom.
 */

$env = !empty($_ENV['AH_SITE_ENVIRONMENT']) ? $_ENV['AH_SITE_ENVIRONMENT'] : '';

$config = array(
  'admin' => array(
    'core:AdminPassword',
  ),
);

$idp_configs = array(
  'cubic-okta-dev' => 'https://cubic.oktapreview.com/app/exkbmlzqun7sQEnNr0h7/sso/saml/metadata',
  'cubic-okta-stage' => 'https://cubic.oktapreview.com/app/exkbmm0u8cf5svoeC0h7/sso/saml/metadata',
  'cubic-okta-prod' => 'https://cubic.okta.com/app/exkj1px1rhs5ttVJZ0x7/sso/saml/metadata',
);

foreach($idp_configs as $idp_name => $idp_config) {
  /*
   * Fetch SAML metadata from the URL.
   * NOTE:
   *  SAML metadata changes very rarely. On a production system,
   *  this data should be cached as approprate for your production system.
   */
  $metadata_xml = file_get_contents($idp_config);

  /*
   * Parse the SAML metadata using SimpleSAMLphp's parser.
   * See also: modules/metaedit/www/edit.php:34
   */
  SimpleSAML_Utilities::validateXMLDocument($metadata_xml, 'saml20');
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

  /*
   * Set up the "$config" and "$metadata" variables as used by SimpleSAMLphp.
   */
  $config[$idp_name] = array(
    'saml:SP',
    'entityID' => null,
    'idp' => $entity_id,
  );

  // $metadata[$entity_id] = $idp;
}
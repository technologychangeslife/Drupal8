<?php

namespace Drupal\tmgmt_contentapi\Swagger\Client\Model;

use ArrayAccess;
use Drupal\tmgmt_contentapi\Swagger\Client\ObjectSerializer;

/**
 * TranslationContent Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class TranslationContent implements ModelInterface, ArrayAccess {
  const DISCRIMINATOR = NULL;

  /**
   * The original name of the model.
   *
   * @var string
   */
  protected static $swaggerModelName = 'TranslationContent';

  /**
   * Array of property to type mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerTypes = [
        'request_id' => 'string',
        'source_native_id' => 'string',
        'source_native_language_code' => 'string',
        'target_native_id' => 'string',
        'target_native_language_code' => 'string',
        'structured_content' => '\Drupal\tmgmt_contentapi\Swagger\Client\Model\KeyValuePair[]',
        'unstructured_content' => 'string[]'
    ];

  /**
   * Array of property to format mappings. Used for (de)serialization.
   *
   * @var string[]
   */
  protected static $swaggerFormats = [
        'request_id' => NULL,
        'source_native_id' => NULL,
        'source_native_language_code' => NULL,
        'target_native_id' => NULL,
        'target_native_language_code' => NULL,
        'structured_content' => NULL,
        'unstructured_content' => NULL
    ];

  /**
   * Array of property to type mappings. Used for (de)serialization.
   *
   * @return array
   */
  public static function swaggerTypes() {
    return self::$swaggerTypes;
  }

  /**
   * Array of property to format mappings. Used for (de)serialization.
   *
   * @return array
   */
  public static function swaggerFormats() {
    return self::$swaggerFormats;
  }

  /**
   * Array of attributes where the key is the local name,
   * and the value is the original name.
   *
   * @var string[]
   */
  protected static $attributeMap = [
        'request_id' => 'requestId',
        'source_native_id' => 'sourceNativeId',
        'source_native_language_code' => 'sourceNativeLanguageCode',
        'target_native_id' => 'targetNativeId',
        'target_native_language_code' => 'targetNativeLanguageCode',
        'structured_content' => 'structuredContent',
        'unstructured_content' => 'unstructuredContent'
    ];

  /**
   * Array of attributes to setter functions (for deserialization of responses)
   *
   * @var string[]
   */
  protected static $setters = [
        'request_id' => 'setRequestId',
        'source_native_id' => 'setSourceNativeId',
        'source_native_language_code' => 'setSourceNativeLanguageCode',
        'target_native_id' => 'setTargetNativeId',
        'target_native_language_code' => 'setTargetNativeLanguageCode',
        'structured_content' => 'setStructuredContent',
        'unstructured_content' => 'setUnstructuredContent'
    ];

  /**
   * Array of attributes to getter functions (for serialization of requests)
   *
   * @var string[]
   */
  protected static $getters = [
        'request_id' => 'getRequestId',
        'source_native_id' => 'getSourceNativeId',
        'source_native_language_code' => 'getSourceNativeLanguageCode',
        'target_native_id' => 'getTargetNativeId',
        'target_native_language_code' => 'getTargetNativeLanguageCode',
        'structured_content' => 'getStructuredContent',
        'unstructured_content' => 'getUnstructuredContent'
    ];

  /**
   * Array of attributes where the key is the local name,
   * and the value is the original name.
   *
   * @return array
   */
  public static function attributeMap() {
    return self::$attributeMap;
  }

  /**
   * Array of attributes to setter functions (for deserialization of responses)
   *
   * @return array
   */
  public static function setters() {
    return self::$setters;
  }

  /**
   * Array of attributes to getter functions (for serialization of requests)
   *
   * @return array
   */
  public static function getters() {
    return self::$getters;
  }

  /**
   * The original name of the model.
   *
   * @return string
   */
  public function getModelName() {
    return self::$swaggerModelName;
  }

  /**
   * Associative array for storing property values.
   *
   * @var mixed[]
   */
  protected $container = [];

  /**
   * Constructor.
   *
   * @param mixed[] $data
   *   Associated array of property values
   *   initializing the model.
   */
  public function __construct(array $data = NULL) {
    $this->container['request_id'] = isset($data['request_id']) ? $data['request_id'] : NULL;
    $this->container['source_native_id'] = isset($data['source_native_id']) ? $data['source_native_id'] : NULL;
    $this->container['source_native_language_code'] = isset($data['source_native_language_code']) ? $data['source_native_language_code'] : NULL;
    $this->container['target_native_id'] = isset($data['target_native_id']) ? $data['target_native_id'] : NULL;
    $this->container['target_native_language_code'] = isset($data['target_native_language_code']) ? $data['target_native_language_code'] : NULL;
    $this->container['structured_content'] = isset($data['structured_content']) ? $data['structured_content'] : NULL;
    $this->container['unstructured_content'] = isset($data['unstructured_content']) ? $data['unstructured_content'] : NULL;
  }

  /**
   * Show all the invalid properties with reasons.
   *
   * @return array invalid properties with reasons
   */
  public function listInvalidProperties() {
    $invalidProperties = [];

    return $invalidProperties;
  }

  /**
   * Validate all the properties in the model
   * return true if all passed.
   *
   * @return bool True if all properties are valid
   */
  public function valid() {

    return TRUE;
  }

  /**
   * Gets request_id.
   *
   * @return string
   */
  public function getRequestId() {
    return $this->container['request_id'];
  }

  /**
   * Sets request_id.
   *
   * @param string $request_id
   *   ID of the translation request.
   *
   * @return $this
   */
  public function setRequestId($request_id) {
    $this->container['request_id'] = $request_id;

    return $this;
  }

  /**
   * Gets source_native_id.
   *
   * @return string
   */
  public function getSourceNativeId() {
    return $this->container['source_native_id'];
  }

  /**
   * Sets source_native_id.
   *
   * @param string $source_native_id
   *   Source ID of the request in the content system.
   *
   * @return $this
   */
  public function setSourceNativeId($source_native_id) {
    $this->container['source_native_id'] = $source_native_id;

    return $this;
  }

  /**
   * Gets source_native_language_code.
   *
   * @return string
   */
  public function getSourceNativeLanguageCode() {
    return $this->container['source_native_language_code'];
  }

  /**
   * Sets source_native_language_code.
   *
   * @param string $source_native_language_code
   *   Source language code of the request in the content system.
   *
   * @return $this
   */
  public function setSourceNativeLanguageCode($source_native_language_code) {
    $this->container['source_native_language_code'] = $source_native_language_code;

    return $this;
  }

  /**
   * Gets target_native_id.
   *
   * @return string
   */
  public function getTargetNativeId() {
    return $this->container['target_native_id'];
  }

  /**
   * Sets target_native_id.
   *
   * @param string $target_native_id
   *   Target ID of the request in the content system.
   *
   * @return $this
   */
  public function setTargetNativeId($target_native_id) {
    $this->container['target_native_id'] = $target_native_id;

    return $this;
  }

  /**
   * Gets target_native_language_code.
   *
   * @return string
   */
  public function getTargetNativeLanguageCode() {
    return $this->container['target_native_language_code'];
  }

  /**
   * Sets target_native_language_code.
   *
   * @param string $target_native_language_code
   *   Target language code of the request in the content system.
   *
   * @return $this
   */
  public function setTargetNativeLanguageCode($target_native_language_code) {
    $this->container['target_native_language_code'] = $target_native_language_code;

    return $this;
  }

  /**
   * Gets structured_content.
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\KeyValuePair[]
   */
  public function getStructuredContent() {
    return $this->container['structured_content'];
  }

  /**
   * Sets structured_content.
   *
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\KeyValuePair[] $structured_content
   *   structured_content.
   *
   * @return $this
   */
  public function setStructuredContent($structured_content) {
    $this->container['structured_content'] = $structured_content;

    return $this;
  }

  /**
   * Gets unstructured_content.
   *
   * @return string[]
   */
  public function getUnstructuredContent() {
    return $this->container['unstructured_content'];
  }

  /**
   * Sets unstructured_content.
   *
   * @param string[] $unstructured_content
   *   unstructured_content.
   *
   * @return $this
   */
  public function setUnstructuredContent($unstructured_content) {
    $this->container['unstructured_content'] = $unstructured_content;

    return $this;
  }

  /**
   * Returns true if offset exists. False otherwise.
   *
   * @param int $offset
   *   Offset.
   *
   * @return bool
   */
  public function offsetExists($offset) {
    return isset($this->container[$offset]);
  }

  /**
   * Gets offset.
   *
   * @param int $offset
   *   Offset.
   *
   * @return mixed
   */
  public function offsetGet($offset) {
    return isset($this->container[$offset]) ? $this->container[$offset] : NULL;
  }

  /**
   * Sets value based on offset.
   *
   * @param int $offset
   *   Offset.
   * @param mixed $value
   *   Value to be set.
   *
   * @return void
   */
  public function offsetSet($offset, $value) {
    if (is_null($offset)) {
      $this->container[] = $value;
    }
    else {
      $this->container[$offset] = $value;
    }
  }

  /**
   * Unsets offset.
   *
   * @param int $offset
   *   Offset.
   *
   * @return void
   */
  public function offsetUnset($offset) {
    unset($this->container[$offset]);
  }

  /**
   * Gets the string presentation of the object.
   *
   * @return string
   */
  public function __toString() {
    // Use JSON pretty print.
    if (defined('JSON_PRETTY_PRINT')) {
      return json_encode(
        ObjectSerializer::sanitizeForSerialization($this),
        JSON_PRETTY_PRINT
      );
    }

    return json_encode(ObjectSerializer::sanitizeForSerialization($this));
  }

}

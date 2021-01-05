<?php

namespace EnglishStudy;

class Cache
{
  static function fetch ($options) {
    $key = md5(json_encode($options));
    return self::fetchByKey($key);
  }

  static function fetchByKey ($key) {
    if (self::hasCache($key)) {
      return self::readCache($key);
    }
    return false;
  }

  static function hasCache ($key) {
    if (file_exists(self::tempPath($key))) {
      return true;
    }
    return false;
  }

  static function tempPath ($key) {
    return "tmp/{$key}";
  }

  static function readCache ($key) {
    $data = file_get_contents(self::tempPath($key));
    $object = json_decode($data);
    $object->__key__ = $key;
    return $object;
  }

  static function saveCache ($options, $data) {
    $key = md5(json_encode($options));
    $fp = fopen(self::tempPath($key), 'w');
    fwrite($fp, json_encode($data));
    fclose($fp);
    return $key;
  }
}

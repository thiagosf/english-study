<?php

require_once('lib/Utils.php');
require_once('lib/ApiResponse.php');
require_once('lib/Dictionary.php');
require_once('lib/Scrap.php');
require_once('lib/Cache.php');
$settings = require_once('settings.php');

$output = null;

switch ($_GET['method']) {
    case 'scrap':
        $url = $_GET['url'];
        $url_hash = $_GET['url_hash'];
        if (!empty($url)) {
          $options = ['url' => $url];
          if ($data = EnglishStudy\Cache::fetch($options)) {
            $output = ['success' => true, 'data' => $data];
          } else {
            $scrap = new EnglishStudy\Scrap($url);
            $data = $scrap->getHTML();
            $key = EnglishStudy\Cache::saveCache($options, $data);
            $data['__key__'] = $key;
            $output = ['success' => true, 'data' => $data];
          }
        } else if (!empty($url_hash)) {
          if ($data = EnglishStudy\Cache::fetchByKey($url_hash)) {
            $output = ['success' => true, 'data' => $data];
          } else {
            $output = ['success' => false];
          }
        }
        break;

    case 'dictionary':
        $word = $_GET['query'];
        $engine = 'oxford';
        $options = ['word' => $word, 'engine' => $engine];
        if ($data = EnglishStudy\Cache::fetch($options)) {
          $output = ['success' => true, 'data' => $data];
        } else {
          $dictionary = new EnglishStudy\Dictionary($word, $engine, $settings);
          $data = $dictionary->search();
          EnglishStudy\Cache::saveCache($options, $data);
          $output = ['success' => true, 'data' => $data];
        }
        break;
}

EnglishStudy\ApiResponse::toJson($output);

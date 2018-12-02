<?php

namespace EnglishStudy;

class Scrap
{
  function __construct ($url) {
    $this->url = $url;
  }

  function getHTML () {
    if ($_GET['mock'] === 'true') {
      $html = file_get_contents('fixtures/manythings.html');
      return ['html' => $html, 'audios' => $this->getAudios($html)];
    } else {
      ini_set('display_errors', 'Off');
      error_reporting(0);
      $dom = new \DOMDocument();
      $dom->loadhtmlfile($this->url);
      $body = $dom->getElementsByTagName('body');
      $body = $body->item(0);
      $body = $dom->savehtml($body);
      $body = str_replace(array('<body>', '</body>'), '', $body);
      $body = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $body);
      $body = preg_replace('#<!--([^-->]+)-->#is', '', $body);
      return ['html' => $body, 'audios' => $this->getAudios($body)];
    }
  }

  function getAudios ($html) {
    $audios = [];
    $pattern = '/(https?:\/\/[^"\']+\.mp3)/im';
    if (preg_match_all($pattern, $html, $matches)) {
      $audios = $matches[1];
      $audios = array_unique($audios);
      $audios = array_map(function ($audio) {
        return $this->getRealUrl($audio);
      }, $audios);
    }
    return $audios;
  }

  function getRealUrl ($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_exec($ch);
    $target = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    if ($target) {
      return $target;
    }
    return false;
  }
}

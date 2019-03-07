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
      return ['html' => $this->cleanHtml($html), 'audios' => $this->getAudios($html)];
    } else {
      ini_set('display_errors', 'Off');
      error_reporting(0);
      $dom = new \DOMDocument();
      $dom->loadhtmlfile($this->url);
      $html = $dom->savehtml();
      $body = $dom->getElementsByTagName('body');
      $body = $body->item(0);
      $body = $dom->savehtml($body);
      return ['html' => $this->cleanHtml($body), 'audios' => $this->getAudios($html)];
    }
  }

  function cleanHtml ($html) {
    $html = str_replace(array('<body>', '</body>'), '', $html);
    $html = preg_replace('#<link(.*?)>#is', '', $html);
    $html = preg_replace('#<link(.*?)>(.*?)</link>#is', '', $html);
    $html = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $html);
    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
    $html = preg_replace('#<!--([^-->]+)-->#im', '', $html); // @todo: fix this
    $html = preg_replace('#style\s*=\s*"?\'?[^\"?\'?]+\"?\'?#im', '', $html);
    $html = preg_replace('#class\s*=\s*"?\'?[^\"?\'?]+\"?\'?#im', '', $html);
    $html = preg_replace('#id\s*=\s*"?\'?[^\"?\'?]+\"?\'?#im', '', $html);
    return $html;
  }

  function getAudios ($html) {
    $audios = [];
    $pattern = '/(https?:\/\/[^"\']+\.(mp3|m4a))/im';
    if (preg_match_all($pattern, $html, $matches)) {
      $audios = $matches[1];
      $audios = array_unique($audios);
      if ($_GET['mock'] !== 'true') {
        $audios = array_map(function ($audio) {
          return $this->getRealUrl($audio);
        }, $audios);
      }
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

<?php

namespace EnglishStudy;

class Scrap
{
  function __construct ($url) {
    $this->url = $url;
  }

  function getHTML () {
    if ($_GET['mock']) {
      return file_get_contents('fixtures/manythings.html');
    } else {
      ini_set('display_errors', 'Off');
      error_reporting(0);
      $dom = new DOMDocument();
      $dom->loadhtmlfile($this->url);
      $body = $dom->getElementsByTagName('body');
      $body = $body->item(0);
      $body = $dom->savehtml($body);
      $body = str_replace(array('<body>', '</body>'), '', $body);
      $body = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $body);
      $body = preg_replace('#<!--([^-->]+)-->#is', '', $body);
      echo $body;exit;
      return $body;
    }
  }
}

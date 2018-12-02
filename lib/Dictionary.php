<?php

namespace EnglishStudy;

require_once('OxfordDicionary.php');

class Dictionary
{
  public function __construct ($word, $engine, $settings) {
    $this->word = $word;
    $this->engine = $engine;
    $this->settings = $settings;
  }

  public function search () {
    switch ($this->engine) {
      case 'oxford':
        return OxfordDicionary::search($this->word, [
          'OXFORD_APP_ID' => $this->settings['OXFORD_APP_ID'],
          'OXFORD_APP_KEY' => $this->settings['OXFORD_APP_KEY'],
        ]);
        break;
    }
  }
}

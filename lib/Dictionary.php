<?php

namespace EnglishStudy;

require_once('OxfordDicionary.php');

class Dictionary
{
  public function __construct ($word, $engine) {
    $this->word = $word;
    $this->engine = $engine;
  }

  public function search () {
    switch ($this->engine) {
      case 'oxford':
        return OxfordDicionary::search($this->word);
        break;
    }
  }
}

<?php

namespace EnglishStudy;

class OxfordDicionary
{
  static public function search ($word) {
    $content = null;
    if ($_GET['mock']) {
      $content = file_get_contents('fixtures/oxford.json');
      $content = json_decode($content);
      $content = self::formatOutput($content);
    } else {
      // api call...
    }
    return $content;
  }

  static public function formatOutput ($content) {
    $output = [];
    // $output[] = $content;
    if ($content->results) {
      foreach ($content->results as $item) {
        foreach ($item->lexicalEntries as $entries) {
          $items = [];
          $type = $entries->lexicalCategory;
          $audios = [];
          if (!empty($entries->pronunciations)) {
            foreach ($entries->pronunciations as $audio) {
              $audios[] = [
                'url' => $audio->audioFile,
                'phonetic' => $audio->phoneticSpelling,
              ];
            }
          }
          if (!empty($entries->entries)) {
            foreach ($entries->entries as $entry) {
              foreach ($entry->senses as $sense) {
                $definition = '';
                $examples = [];
                if (!empty($sense->examples)) {
                  $examples = array_map(function ($item) {
                    return $item->text;
                  }, $sense->examples);
                }
                if (!empty($sense->definitions)) {
                  $definition = implode(', ', $sense->definitions);
                }
                $items[] = [
                  'definition' => $definition,
                  'examples' => $examples,
                ];
              }
            }
          }
          $output[] = [
            'type' => $type,
            'audios' => $audios,
            'items' => $items,
          ];
        }
      }
    }
    return $output;
  }
}

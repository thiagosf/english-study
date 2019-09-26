<?php

namespace EnglishStudy;

class OxfordDicionary
{
  static public function search ($word, $settings) {
    $content = null;
    if ($_GET['mock'] === 'true') {
      $content = file_get_contents('fixtures/oxford.json');
      $content = json_decode($content);
    } else {
      $auth = [
        'Accept: application/json',
        "app_id: {$settings['OXFORD_APP_ID']}",
        "app_key: {$settings['OXFORD_APP_KEY']}",
      ];
      $language = 'en';
      $url = "https://od-api.oxforddictionaries.com:443/api/v2/entries/{$language}/{$word}";
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $auth);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $content  = curl_exec($curl);
      curl_close($curl);
      try {
        $content = json_decode($content);
      } catch (\Exception $e) {
        //
      }
    }
    return self::formatOutput($content);
  }

  static public function formatOutput ($content) {
    $output = [];
    if ($content && $content->results) {
      foreach ($content->results as $item) {
        foreach ($item->lexicalEntries as $entries) {
          $items = [];
          $type = $entries->lexicalCategory;
          $audios = [];
          if (!empty($entries->pronunciations)) {
            foreach ($entries->pronunciations as $audio) {
              if (!empty($audio->audioFile)) {
                $audios[] = [
                  'url' => $audio->audioFile,
                  'phonetic' => $audio->phoneticSpelling,
                ];
              }
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
            'reference' => "https://en.oxforddictionaries.com/definition/{$item->word}",
          ];
        }
      }
    }
    return $output;
  }
}

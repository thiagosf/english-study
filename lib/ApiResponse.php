<?php

namespace EnglishStudy;

class ApiResponse
{
  static function toJson ($data) {
    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($data);
    exit;
  }
}

<?php

namespace App\Component\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class RequestValidator{

  static function validate($data, ValidatorInterface $validator)
  {
    $errors = $validator->validate($data);
    return [
      "errors" => self::printErrors($errors)
    ];
  }

  static function printErrors(ConstraintViolationList $errors) {
    $result = [];
    foreach ($errors as $error) {
      $property = $error->getPropertyPath();
      $errorMessage = $error->getMessage();
      if(!array_key_exists($property, $result)){
        $result[$property] = [];
      }
      array_push($result[$property], $errorMessage);

    }
    return $result;
  }

}
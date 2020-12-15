<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidateService {

  private $validator;

  public function __construct(ValidatorInterface $validator)
  {
      $this->validator = $validator;
  }

  public function validate($data)
  {
    $errors = $this->validator->validate($data);
    if($errors) {
      return $this->printErrors($errors);
    }
    return null;
  }

  private function printErrors($errors) {
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
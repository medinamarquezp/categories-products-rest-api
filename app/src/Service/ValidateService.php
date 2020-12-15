<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidateService
{

  private $validator;

  public function __construct(ValidatorInterface $validator)
  {
    $this->validator = $validator;
  }

  public function validate($data)
  {
    $errors = $this->validator->validate($data);
    if ($errors) {
      return $this->printErrors($errors);
    }
    return null;
  }

  public function validateCategory($request, $data)
  {
    $hasCategoryOnRequest = !empty($request->get("category"));
    $categoryIsNull = empty($data->getCategory());
    $errorCategory = $hasCategoryOnRequest && $categoryIsNull;

    $errors = $this->validate($data);

    if ($errors) {
      if ($errorCategory) {
        return $this->printCategoryError($errors);
      }
      return $errors;
    } else {
      if ($errorCategory) {
        return $this->printCategoryError($errors);
      }
    }
    return null;
  }

  private function printCategoryError($errors){
    $errorCategoryMessage = "Category selected does not exists";
    $errors["category"] = [];
    array_push($errors["category"], $errorCategoryMessage);
    return $errors;
  }

  private function printErrors($errors)
  {
    $result = [];
    foreach ($errors as $error) {
      $property = $error->getPropertyPath();
      $errorMessage = $error->getMessage();
      if (!array_key_exists($property, $result)) {
        $result[$property] = [];
      }
      array_push($result[$property], $errorMessage);
    }
    return $result;
  }
}

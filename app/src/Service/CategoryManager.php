<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Component\Validator\RequestValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryManager
{

  private $em;
  private $categoryRepository;
  private $validator;
  private $serializer;

  public function __construct(
    EntityManagerInterface $em,
    CategoryRepository $categoryRepository,
    ValidatorInterface $validator,
    SerializerInterface $serializer
  )
  {
    $this->em = $em;
    $this->categoryRepository = $categoryRepository;
    $this->validator = $validator;
    $this->serializer = $serializer;
  }

  public function serialize(Request $request)
  {
    return $this->serializer->deserialize($request->getContent(), Category::class, 'json');
  }


  public function validate(Category $category)
  {
    $validation = RequestValidator::validate($category, $this->validator);
    $hasValidationErrors = !empty($validation["errors"]);
    if ($hasValidationErrors) {
      return $validation["errors"];
    }
    return null;
  }

  public function find(int $id): ?Category
  {
    return $this->categoryRepository->find($id);
  }

  public function update($category, $requestData)
  {
    $category->setName($requestData->getName());
    $category->setDescription($requestData->getDescription());
    return $this->save($category);
  }

  public function delete($category)
  {
    $this->em->remove($category);
    $this->em->flush();
  }

  public function save(Category $category)
  {
    $this->em->persist($category);
    $this->em->flush();
    return $this->parseResponse($category);
  }

  private function parseResponse(Category $category)
  {
    return [
      "id" => $category->getId(),
      "name" => $category->getName(),
      "description" => $category->getDescription()
    ];
  }
}

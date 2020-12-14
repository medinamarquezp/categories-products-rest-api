<?php

namespace App\Controller\Api;

use App\Service\CategoryManager;
use App\Exceptions\BaseException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class CategoryController extends BaseController
{

  /**
   * @Route("/category", methods={"POST"})
   */
  public function create(Request $request, CategoryManager $categoryManager): JsonResponse
  {
    try {
      $category = $categoryManager->serialize($request);

      $errors = $categoryManager->validate($category);
      if ($errors) throw new ValidationException($errors);

      $categoryCreated = $categoryManager->save($category);
      return $this->success("Category created correctly", $categoryCreated);
    } catch (BaseException $ex) {
      return $this->fail($ex);
    }
  }

  /**
   * @Route("/category/{id}",
   * requirements={"id"="\d+"},
   * methods={"PUT"})
   */
  public function update(Request $request, CategoryManager $categoryManager): JsonResponse
  {
    try {
      $category = $categoryManager->find($request->get('id'));
      if (!$category) throw new NotFoundException("Category not found");

      $requestData =  $categoryManager->serialize($request);

      $errors = $categoryManager->validate($requestData);
      if ($errors) throw new ValidationException($errors);

      $categoryUpdated = $categoryManager->update($category, $requestData);
      return $this->success("Category updated correctly", $categoryUpdated);
    } catch (BaseException $ex) {
      return $this->fail($ex);
    }
  }

  /**
   * @Route("/category/{id}",
   * requirements={"id"="\d+"},
   * methods={"DELETE"})
   */
  public function delete(Request $request, CategoryManager $categoryManager): JsonResponse
  {
    try {
      $category = $categoryManager->find($request->get('id'));
      if (!$category) throw new NotFoundException("Category not found");

      $categoryManager->delete($category);
      return $this->success("Category deleted correctly");
    } catch (BaseException $ex) {
      return $this->fail($ex);
    }
  }
}

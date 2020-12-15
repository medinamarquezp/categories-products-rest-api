<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Service\ValidateService;
use App\Exceptions\BaseException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Repository\CategoryRepository;
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
  public function create(Request $request, CategoryRepository $repo, ValidateService $v): JsonResponse
  {
    try {
      $category =  $this->deserialize($request, Category::class);
      $errors = $v->validate($category);
      if ($errors) throw new ValidationException($errors);

      $categoryCreated = $repo->save($category);
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
  public function update(Request $request, CategoryRepository $repo, ValidateService $v): JsonResponse
  {
    try {
      $category = $repo->find($request->get('id'));
      if (!$category) throw new NotFoundException("Category not found");

      $requestData =  $this->deserialize($request, Category::class);

      $errors = $v->validate($requestData);
      if ($errors) throw new ValidationException($errors);

      $categoryUpdated = $repo->update($requestData);
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
  public function delete(Request $request, CategoryRepository $repo): JsonResponse
  {
    try {
      $category = $repo->find($request->get('id'));
      if (!$category) throw new NotFoundException("Category not found");

      $repo->delete($category);
      return $this->success("Category deleted correctly");
    } catch (BaseException $ex) {
      return $this->fail($ex);
    }
  }
}

<?php

namespace App\Controller\Api;

use App\Component\Http\ApiResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CategoryManager;


/**
 * @Route("/api")
 */
class CategoryController {

  /**
   * @Route("/category", methods={"POST"})
   */
  public function create(Request $request, CategoryManager $categoryManager): JsonResponse
  {
      $category = $categoryManager->serialize($request);

      $errors = $categoryManager->validate($category);
      if ($errors) {
        $response = new ApiResponse(Response::HTTP_BAD_REQUEST);
        return $response->error($errors);
      }

      $categoryManager->save($category);

      $response = new ApiResponse(Response::HTTP_OK);
      return $response->success("Category created correctly");
  }

  /**
   * @Route("/category/{id}",
   * requirements={"id"="\d+"},
   * methods={"PUT"})
   */
  public function update(Request $request, CategoryManager $categoryManager): JsonResponse
  {
      $category = $categoryManager->find($request->get('id'));

      if (!$category) {
        $response = new ApiResponse(Response::HTTP_NOT_FOUND);
        return $response->error("Category not found");
      }

      $requestData =  $categoryManager->serialize($request);

      $errors = $categoryManager->validate($requestData);
      if ($errors) {
        $response = new ApiResponse(Response::HTTP_BAD_REQUEST);
        return $response->error($errors);
      }

      $categoryManager->update($category, $requestData);

      $response = new ApiResponse(Response::HTTP_OK);
      return $response->success("Category updated correctly");
  }

   /**
   * @Route("/category/{id}",
   * requirements={"id"="\d+"},
   * methods={"DELETE"})
   */
  public function delete(Request $request, CategoryManager $categoryManager): JsonResponse
  {
      $category = $categoryManager->find($request->get('id'));

      if (!$category) {
        $response = new ApiResponse(Response::HTTP_NOT_FOUND);
        return $response->error("Category not found");
      }

      $categoryManager->delete($category);

      $response = new ApiResponse(Response::HTTP_OK);
      return $response->success("Category deleted correctly");
  }
}
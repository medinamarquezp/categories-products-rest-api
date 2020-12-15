<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByFeatured()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.featured = true')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function save(Product $product): Product
    {
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();
        return $product;
    }

    public function getList(): ?array
    {
        $productList = $this->findAll();
        $response = $this->parseResponseList($productList);
        return $response;
    }

    public function getFeaturedList(): ?array
    {
        $productListFeatured = $this->findByFeatured();
        $response = $this->parseResponseList($productListFeatured);
        return $response;
    }

    private function parseResponseList(array $productList): array
    {
        $response = [];
        if (!empty($productList)) {
            foreach ($productList as $product) {
                $categoryName = (!empty($product->getCategory())) ? $product->getCategory()->getName() : "";
                array_push($response, [
                    "id" => $product->getId(),
                    "name" => $product->getName(),
                    "price" => $product->getPrice(),
                    "currency" => $product->getCurrency(),
                    "categoryName" => $categoryName,
                ]);
            }
        }
        return $response;
    }
}

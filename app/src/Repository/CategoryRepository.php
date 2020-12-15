<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function update(Category $data)
    {
        $category = new Category();
        $category->setName($data->getName());
        $category->setDescription($data->getDescription());
        return $this->save($category);
    }

    public function delete(Category $category)
    {
        $this->getEntityManager()->remove($category);
        $this->getEntityManager()->flush();
    }

    public function save(Category $category)
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();
        return $category->getResponse();
    }
}

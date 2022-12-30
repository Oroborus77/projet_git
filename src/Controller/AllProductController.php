<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllProductController extends AbstractController
{
    /**
     * @Route("/allproduct", name="app_all_product")
     */
    public function index(EntityManagerInterface $entitymanager): Response
    {
        $products = $entitymanager->getRepository(Product::class)->findAll();
        $categories = $entitymanager->getRepository(Category::class)->findAll();

        return $this->render('all_product/index.html.twig', [
            'controller_name' => 'AllProductController',
            'products' => $products,
            'categories' => $categories
        ]);
    }
}

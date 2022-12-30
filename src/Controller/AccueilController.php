<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="app_accueil")
     */
    public function index(EntityManagerInterface $entitymanager): Response
    {
        $products = $entitymanager->getRepository(Product::class)->findAll();
        $categories = $entitymanager->getRepository(Category::class)->findAll();


        return $this->render('accueil/index.html.twig', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CategoryType;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;

class ProductController extends AbstractController
{
    private $entitymanager;

    public function __construct(EntityManagerInterface $entitymanager)
    {
        $this->entitymanager = $entitymanager;
    }

    /**
     *
     * @Route("/admin/product", name="app_product")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $product = new Product();
        $productform = $this->createForm(ProductType::class, $product);

        $productform->handleRequest($request);

        if($productform->isSubmitted() && $productform->isValid())
        {
            $image = $productform->get('image')->getData();
            if($image){

                $imagename = $image->getClientOriginalName();
                $image->move($this->getParameter('images_directory'),$imagename);
            }
            $product->setImage($imagename);

            $this->entitymanager->persist($product);
            $this->entitymanager->flush();

            $this->addFlash('success',"Le produit a bien été ajouté");
            return $this->redirectToRoute('app_product_index');
        }

        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('product/index.html.twig', [
            'productform' => $productform->createView(),
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/products", name="app_product_index")
     */
    public function listproduct(EntityManagerInterface $entityManager)
    {
        $products = $this->entitymanager->getRepository(Product::class)->findAll();
        $chemin = $this->getParameter('images_directory');

        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('product/products.html.twig', [
            'products' => $products,
            'chemin' => $chemin,
            'categories' => $categories

        ]);
    }

    /**
     * @Route("/produit/{id}", name="productfront")
     */
    public function productpage(EntityManagerInterface $entitymanager,$id)
    {
        $produit = $this->entitymanager->getRepository(Product::class)->find($id);
        $categories = $entitymanager->getRepository(Category::class)->findAll();

        return $this->render('product/frontproduct.html.twig', [
           'produit' => $produit,
            'categories' => $categories
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Product $product, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'products' => $products,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/delete/{id}", name="app_product_delete")
     */
    public function deleteproduct($id)
    {

        $product = $this->entitymanager->getRepository(Product::class)->find($id);

        if ($product) {
            $this->entitymanager->remove($product);
            $this->entitymanager->flush();
        }

        $this->addFlash('success', "Le produit a bien été supprimée.");
        return $this->redirectToRoute('app_product_index');

    }

}

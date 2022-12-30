<?php

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="app_category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->renderForm('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/{id}", name="app_category_show", methods={"GET"})
     */
    public function show(Category $category, EntityManagerInterface $entityManager): Response
    {

        $categories = $entityManager->getRepository(Category::class)->findAll();
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->renderForm('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/{id}", name="app_category_delete", methods={"POST"})
     */
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/front/category/{id}", name="frontcategory")
     */
    public function frontcategory(EntityManagerInterface $entitymanager, $id)
    {
        $products = $entitymanager->getRepository(Product::class)->findBy(['category' => $id]);
        $categories = $entitymanager->getRepository(Category::class)->findAll();


        return $this->render('category/frontcategory.html.twig',[
            'products' => $products,
            'categories' => $categories
        ]);

    }
}

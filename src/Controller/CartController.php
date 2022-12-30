<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;

class CartController  extends AbstractController {

    private $entitymanager;

    public function __construct(EntityManagerInterface $entitymanager)
    {
        $this->entitymanager = $entitymanager;
    }

    /**
     * @Route("/panier", name="mycart")
     */

    public function index(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        //création de la session
        $panier = $session->get('panier',[]);
        $panierWithData = [];

        foreach($panier as $id => $quantity)
        {
            $panierWithData[] = [
                'product' => $this->entitymanager->getRepository(Product::class)->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;

        foreach($panierWithData as $item) {
            $totalItem = $item['product']->getPrix() * $item['quantity'];
            $total += $totalItem;
        }
        $categories = $entityManager->getRepository(Category::class)->findAll();
        return $this->render('cart/index.html.twig', [
           'items' => $panierWithData,
           'total' => $total,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/panier/add/{id}", name="cartadd")
     */
    public function add(SessionInterface $session, $id)
    {
        $panier = $session->get('panier',[]);

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else {
            $panier[$id] = 1;
        }

        $session->set('panier',$panier);

        return $this->redirectToRoute('mycart');
    }

    /**
     * @Route("/panier/remove/{id}", name="cartremove")
     */
    public function remove($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('mycart');
    }

}
<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\InscriptionType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entitymanager): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $categories = $entitymanager->getRepository(Category::class)->findAll();
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'categories' => $categories]);
        return $this->redirectToRoute('app_accueil');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $entitymanager, UserPasswordHasherInterface $passwordhasher)
    {
        $user = new User();
        $insform = $this->createForm(InscriptionType::class, $user);

        $insform->handleRequest($request);

        if($insform->isSubmitted() && $insform->isValid())
        {

            $inscription = $request->request->get('inscription');
            $password = $inscription['password'];

            $newpassword = $passwordhasher->hashPassword($user, $password);


            $user->setPassword($newpassword);

            $entitymanager->persist($user);
            $entitymanager->flush();

            $this->addFlash('success',"L'utilisateur a bien été ajouté");
            return $this->redirectToRoute('app_accueil');
        }

        $categories = $entitymanager->getRepository(Category::class)->findAll();

       return  $this->render('user/inscription.html.twig',[
            'insform' => $insform->createView(),
            'categories' => $categories
        ]);
    }
}

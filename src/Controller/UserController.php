<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Article;
use App\Form\RegisterFormType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    /**
    * @Route("/inscription", name= "user_register", methods={"GET|POST"})
    */
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
       $user = New User();
       
       $form = $this->createForm(RegisterFormType::class, $user)
           ->handleRequest($request); 
           
           if($form->isSubmitted() && $form->isValid()) {
               $user->setRoles(['ROLE_ADMIN']);
               $user->setCreatedAt(new DateTime());
               $user->setUpdatedAt(new DateTime());
               //On setUpdatedAt parce que tres svt on va afficher ds l espace perso ou profil la derniere date de modif, donc si c est pas modifié ça sera la meme valeur que  la date de création.
               $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
                               // ici on a pas besoin de getData() vu qu il a ete auto hydraté, c est plus rapide a ecrire.
    
               $entityManager->persist($user);
               $entityManager->flush();
                              // success et le second ; le message.
               $this->addFlash('success', "Vous vous êtes inscrit avec succès !");
               return $this->redirectToRoute('app_login');
           
          }

    return $this->render("user/register.html.twig", [
        'form' => $form->createView()   // ici le scond parametre st envoyé dans le register.html.twig 
        // nb: consequence "clé" => valeur  pour appeller le formulaire et la variable s'appellera 'form' (le nom de la clé).
     ]); 



    }

 /**
      * @Route("/profile/mon-espace-perso", name="show_profile", methods={"GET"})
      */
      public function showProfile(EntityManagerInterface $entityManager): Response
      {
          $articles = $entityManager->getRepository(Article::class)->findBy(['author' => $this->getUser()]);
          // on veut afficher ici les articles publiés , NB: l'admin est aussi un utilisateur donc il a aussi un profil
          return $this->render("user/show_profile.html.twig", [
              'articles' => $articles
          ]);
  
      }
 /**
     * @Route("/profile/changer-mon-mot-de-passe", name="change_password", methods={"GET|POST"})
     */
    public function changePassword(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, Request $request): Response
    {
        //NB: change password peut être mis ici ou a SecurityController (puisqu'il s'git ici d 'une action de sécurité).
        // rappel un DELIMITER ($) est un opération de fin d'instruction ( exp le ;)

          $form = $this->createForm(ChangePasswordFormType::class)
                ->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {

             
        /** @var User $user  */  
        // qui sert a typer pour l'auto-completion , pr que l ide sache que $user c est pour UserEntity , pour que lorsqu'on fait une flche pour acceder aux methodes il nous liste tts les methodes qu'il y ' ds USER.
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $this->getUser()]);
   
        $user->setUpdatedAt(new Datetime());

        $user->setPassword($passwordHasher->hashPassword(
            $user, $form->get('plainPassword')->getData()
        )   // get('plainPassword') inout en entier mais quand on met get('plainPassword')->getData() on veut les données qui st à l'interieur.
            
        );

        $entityManager->persist($user);
        $entityManager->flush();

        $this ->addFlash('success', "Votre mot de passe a bien été changé");
        return $this->redirectToRoute('show_profile');
    }

          return $this->render('user/change_password.html.twig', [
            'form' => $form->createView()
          ]);

    }

}

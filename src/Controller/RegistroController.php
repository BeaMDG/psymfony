<?php

namespace App\Controller;



use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;

class RegistroController extends AbstractController
{
    #[Route('/registro', name: 'app_registro')]
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $user->setRoles(["ROLE_USER"]);

            // Encriptar la contraseña antes de guardarla en la base de datos
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            // ...

            $em->persist($user);
            $em->flush();

            $this->addFlash('exito', User::REGISTRO_EXITOSO);



            return $this->redirectToRoute('app_registro');
        }

        return $this->render('registro/index.html.twig', [
            'formulario' => $form->createView()
        ]);
    }
}









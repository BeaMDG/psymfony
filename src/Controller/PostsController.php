<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsType;
use App\Entity\Comentarios;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\throwException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PostsController extends AbstractController
{
    #[Route('/registrar-posts', name: 'registrar-posts')]
    public function index(Request $request, ManagerRegistry $registry, SluggerInterface $slugger)
    {
        $post = new Posts();
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('foto')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename)->ascii();
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Ha ocurrido un error inesperado');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $post->setFoto($newFilename);
            }
            $user = $this->getUser();
            $post->setUser($user);
            $em = $registry->getManager();
            $em->persist($post);
            $em->flush();
            
            return $this->redirectToRoute('app_dashboard');
        }
        
        return $this->render('posts/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{id}', name: 'Ver-Post')]
    public function VerPost($id, Request $request, PaginatorInterface $paginator, ManagerRegistry $doctrine){
        $em = $doctrine->getManager();
        $comentario = new Comentarios();
        $post = $em->getRepository(Posts::class)->find($id);

        return $this->render('posts/verPost.html.twig',['post'=>$post]);
    }


    #[Route('/mis-posts', name: 'MisPosts')]
    public function MisPost(ManagerRegistry $doctrine){
        $em = $doctrine->getManager();
        $user = $this->getUser();
        $posts = $em->getRepository(Posts::class)->findBy(['user'=>$user]);
        return $this->render('posts/MisPosts.html.twig',['posts'=>$posts]);
    }
}

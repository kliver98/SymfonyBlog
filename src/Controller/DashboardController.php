<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Comentarios;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $user = $this->getUser();
       
        if($user){
            $em= $this->getDoctrine()->getManager();
            $comentarios = $em->getRepository(Comentarios::class)->BuscarComentarios($user->getId());           
            $query = $em->getRepository(Posts::class)->findAllPosts();
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                2 /*limit per page*/
            );
            return $this->render('dashboard/index.html.twig', [
                'pagination' => $pagination,
                'comentarios'=>$comentarios
            ]);

        }else{
            return $this->RedirectToRoute('app_login');
        }
        
        //El entity manager es el encargado de la gestión de BD
       
    }
}

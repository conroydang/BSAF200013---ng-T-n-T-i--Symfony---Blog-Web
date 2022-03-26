<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


class BlogController extends AbstractController
{
    // private $postRepository;
    // private $categoryRepository;
    // private $em;
    // public function __construct(PostRepository $postRepository, CategoryRepository $categoryRepository, EntityManagerInterface $em){
    //     $this -> postRepository = $postRepository;
    //     $this -> categoryRepository = $categoryRepository;
    //     $this -> $em = $em;
    // }

    #[Route('/home', name: 'home', methods: ['GET'])]
    public function getBlogs(ManagerRegistry $doctrine): Response
    {   
        $em = $doctrine->getManager();
        $getCate = $em -> getRepository('App:Category')->findAll();
        $getPost = $em -> getRepository('App:Post')->findAll();
        return $this -> render('blog/home.html.twig',['posts' => $getPost, 'categories' => $getCate]);
    }

    #[Route('/', name: 'main', methods: ['GET'])]
    public function indexAction(ManagerRegistry $doctrine): Response
    {   
        $em = $doctrine->getManager();
        $getCate = $em -> getRepository('App:Category')->findAll();
      
        return $this -> render('blog/index.html.twig',['categories' => $getCate]);
    }

    #[Route('/blog/details/{id}', name: 'details_blog', methods: ['GET'])]
    public function getDetailsBlog(ManagerRegistry $doctrine,$id): Response
    {   
        $em = $doctrine->getManager();
        $getCate = $em -> getRepository('App:Category')->findAll();
        $getPost = $em -> getRepository('App:Post')->find($id) -> getCategoryId();
        return $this -> render('blog/details.html.twig',['details_blog' => $getPost, 'categories' => $getCate]);
    }

    #[Route('/filter/category/{id}', name: 'filter_category', methods: ['GET'])]
    public function filterCategory(PostRepository $postRepository,$id): Response
    {    
        
        return $this -> render('blog/details.html.twig',['filterCate' =>'']);
    }
    
}

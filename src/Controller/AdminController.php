<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Entity\Category;
use App\Form\PostType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
class AdminController extends AbstractController
{
    private $em;
    private $categoryRepository;
    private $postRepository;
    
    public function __construct(CategoryRepository $categoryRepository, PostRepository $postRepository, EntityManagerInterface $em){
        $this-> categoryRepository = $categoryRepository;
        $this-> postRepository = $postRepository;
        $this -> em = $em;
    }

    #[Route('/admin/list/blog',name:'list_blog', methods:['GET'])]
    public function getAllBlogAction():Response {
       
        return $this -> render('admin/index.html.twig',['list_blog'=>$this->postRepository->findAll()]);
    }

    #[Route('/admin/list/category',name:'list_cateogry', methods:['GET'])]
    public function getAllCategoryAction():Response {
        return $this -> render('admin/index.html.twig',['list_category'=>$this->categoryRepository->findAll()]);
    }

    #[Route('/admin/list/blog/{id}',name:'list_blog', methods:['GET'])]
    public function getOneBlogAction($id):Response {
        return $this -> render('admin/index.html.twig',['one_blog'=>$this->postRepository->find($id)]);
    }

    #[Route('/admin/list/category/{id}',name:'list_blog', methods:['GET'])]
    public function getOneCateAction($id):Response {
        return $this -> render('admin/index.html.twig',['one_category'=>$this->categoryRepository->find($id)]);
    }

    #[Route('/admin/create/blog', name: 'create_blog', methods:['GET','POST'])]
    public function createBlogAction(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $newBlog = $form->getData();
            $imgPath = $form->get('image')->getData();

            if($imgPath){
                $newFileName = uniqid(). '.' . $imgPath->guessExtension();

                try{
                    $imgPath->move($this -> getParameter('kernel.project_dir').'/public/uploads', $newFileName);
                }
                catch(FileException $e){
                    return new Response($e->getMessage());
                }
                $newBlog->setImage('/uploads/' . $newFileName);

                
            }
            $this -> em -> persist($newBlog);
            $this -> em -> flush();

            return $this -> redirectToRoute('list_blog');
        }

        return $this->render('admin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/create/category', name: 'create_category', methods:['GET','POST'])]
    public function createCategoryAction(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $newCat = $form->getData();
            $this -> em -> persist($newCat);
            $this -> em -> flush();
            return $this -> redirectToRoute('list_category');
        }

        return $this->render('admin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/list/blog/{id}', name: 'update_blog', methods:['GET','POST'])]
    public function updateBlogAction(Request $request, $id): Response
    {
        $data = $this -> postRepository -> find($id);
        $form = $this->createForm(PostType::class, $data);
        $form->handleRequest($request);
        $imgPath = $form->get('image')->getData();
        if($form->isSubmitted() && $form->isValid()){
           

            if($imgPath){
              if($data -> getImage() !== null ) {
                  if(file_exists($this -> getParameter('kernel.project_dir') . $data -> getImage())){
                    $newFileName = uniqid(). '.' . $imgPath->guessExtension();
                    $this -> getParameter('kernel.project_dir') . $data->getImage();

                    try{
                        $imgPath->move($this -> getParameter('kernel.project_dir').'/public/uploads', $newFileName);
                    }
                    catch(FileException $e){
                        return new Response($e->getMessage());
                    }
                    $data->setImage('/uploads/' . $newFileName);
                  }
              }

                
            }
            else{
                $data -> setTitle($form->get('title')->getData());
                $data -> setCategoryId($form->get('categoryId')->getData());
                $data -> setContent($form->get('content')->getData());
                $data -> setSummary($form->get('summary')->getData());
                $this -> em -> flush();
                return $this -> redirectToRoute('list_blog');
            }
            // $this -> em -> persist($newBlog);
            // $this -> em -> flush();
        }

        return $this->render('admin/update.html.twig', [
            'data'=>$data,
            'form' => $form->createView(),
        ]);
    }

}

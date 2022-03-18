<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Post;
use App\Form\CreateCategoryType;
use App\Form\CreateContentType;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use App\shared\Common;
use App\Entity\Category;
use App\Controller\CommonController;
use ArrayObject;

 class AdminController extends AbstractController
{

     /**
     * @Route("/list/blog",name="list_content", methods={"GET", "POST"})
     */
    public function getListContent():Response{
        $category = $this -> forward('App\Controller\CommonController::getManyCategory');
        $data = $this -> forward('App\Controller\CommonController::getManyContent');
        return $this -> render('admin/managamentBlog.html.twig',['data' => $data, 'category'=>$category]);
    }

    /**
     * @Route("/list/blog/{id}",name="one_content")
     */
    public function getAContent($id):Response{
        $data = $this -> forward('App\Controller\CommonController::getOneContent',['id'=>$id]);
        return $this -> render('admin/detailBlog.html.twig',['data',$data]);
    }

    /**
     * @Route("/list/category",name="list_cateogry")
     */
    public function getListCategory():Response{
        $data = $this -> forward('App\Controller\CommonController::getManyCategory');
        return $this -> render('admin/managementCategory.html.twig',['category'=>$data]);
    }

    /**
     * @Route("/list/category/${id}",name="one_category")
     */
    public function getACategory($id):Response{
        // $data =$this -> container->get('Common') -> getOneCategory($id);
        $data = $this -> forward('App\Controller\CommonController::getOneContent',['id'=>$id]);
        return $this -> render('admin/index.html.twig',['data'=>$data]);
    }

    /**
     * @Route("/create/blog",name="create_content", methods = {"GET", "POST"})
     */
    public function createContentAction(Request $req):Response{
        $Post = new Post();
        $form = $this->createForm(CreateContentType::class,$Post);
        if($this->isSaveContent($form,$req,$Post)){
            $this -> addFlash(
                'Post',
                'Add Success'
            );
        }
        return $this->render('admin/createBlog.html.twig', [
            'form' => $form->createView()
          ]);
    }


     /**
     * @Route("/create/category",name="category", methods = {"GET", "POST"})
     */
    public function createCategoryAction(Request $req):Response{
        $category = new Category();
        $form = $this->createForm(CreateCategoryType::class,$category);
        if($this->isSaveCategory($form,$req,$category)){
            $this -> addFlash(
                'Category',
                'Add Success'
            );
          
        }
        return $this->render('admin/createCateogry.html.twig', [
            'form' => $form->createView()
          ]);
    }

     /**
     * @Route("/details/cateogry/{id}",name="update_category")
     */
    public function updateCateogryAction(Request $req,$id):Response{
      $doctrine = new ManagerRegistry();
      $em = $doctrine->getManager();
      $category = $em -> getRepository(Category::class)->findOneBy($id);
      $form = $this -> createForm(CreateCategoryType::class,$category);
      if($this->isSaveCategory($form,$req,$category)){
          $this -> addFlash(
              'Alert',
              'Updated Category Successfully'
          );
          return $this -> redirectToRoute('admin');
      }
    }

     /**
     * @Route("/details/blog/{id}",name="update_content")
     */
    public function updateContentAction(Request $req,$id):Response{
        $doctrine = new ManagerRegistry();
        $em = $doctrine->getManager();
        $content = $em -> getRepository(Post::class)->findOneBy($id);
        $form = $this -> createForm(CreateCategoryType::class,$content);
        if($this->isSaveCategory($form,$req,$content)){
            $this -> addFlash(
                'Alert',
                'Updated Content Successfully'
            );
            return $this -> redirectToRoute('admin');
        }
      }


     /**
     * @Route("/blog/{id}",name="update_content", methods = {"GET","POST"})
     */
    public function deleteContentAction($id) {
        $doctrine = new ManagerRegistry();
        $em = $doctrine -> getManager();
        $content = $em -> getRepository(Post::class) -> findOneBy($id);
        $em -> remove($content);
        $em -> flush();
        $this -> addFlash(
            'Alert',
            'Delete content successfully!'
        );
    } 

    /**
     * @Route("/cateogry/{id}",name="update_category", methods = {"GET","POST"})
     */

    public function deleteCategoryAction($id) {
        $doctrine = new ManagerRegistry();
        $em = $doctrine -> getManager();
        $category = $em -> getRepository(Category::class) -> findOneBy($id);
        $em -> remove($category);
        $em -> flush();
        $this -> addFlash(
            'Alert',
            'Delete category successfully!'
        );
    } 

   

    public function isSaveContent($form, $req, $content){
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $content -> setAuthorId($req -> request -> get(Post::class)['authorId']);
            $content -> setCategoryId($req -> request -> get(Post::class)['categoryId']);
            $content -> setTitle($req -> request -> get(Post::class)['title']);
            $content -> setSummary($req -> request -> get(Post::class)['summary']);
            $content -> setContent($req -> request -> get(Post::class)['content']);
            $doctrine = new ManagerRegistry();
            $em = $doctrine ->getManager();
            $em -> persist($content);
            $em -> flush();
            return true;
        }
        return false;
    }

    public function isSaveCategory($form, $req, $content){
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $content -> setTitle($req -> request -> get(Cateogry::class)['title']);
            $doctrine = new ManagerRegistry();
            $em = $doctrine ->getManager();
            $em -> persist($content);
            $em -> flush();
            return true;
        }
        return false;
    }


}
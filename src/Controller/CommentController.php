<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
class CommentController extends AbstractController
{
   public function getComments($postId){
        $doctrine = new ManagerRegistry();
        $em = $doctrine->getManager()->getRepository(Comment::class);
        $data = $em -> findOneBy($postId);
        return $this -> renderView('blog/comment.html.twig',['data'=>$data]);
   }


   public function postComment($userId, $content){

   }

   public function editComment($userId, $content) {

   }

   public function deleteComment($userId) {

   }
}

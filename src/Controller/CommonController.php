<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Category;
use App\Entity\Post;
use ArrayObject;
use Doctrine\DBAL\Types\ObjectType;

class CommonController extends AbstractController
{
    public function getManyContent(ManagerRegistry $doctrine){
//    $doctrine = new ManagerRegistry();
    $getData = $doctrine->getManager()->getRepository(Post::class)->findAll();
    return $getData;
    }

    public function getManyCategory(ManagerRegistry $doctrine){
        // $doctrine = new ManagerRegistry();
        $getData = $doctrine->getManager()->getRepository(Category::class)->findAll();
        return $getData;
    }

    public function getOneContent(ManagerRegistry $doctrine , $id){
        // $doctrine = new ManagerRegistry();
        $getData = $doctrine->getManager()->getRepository(Post::class)->findOneBy($id);
        return $getData;
    }

    public function getOneCategory(ManagerRegistry $doctrine,$id){
        // $doctrine = new ManagerRegistry();
        $getData = $doctrine->getManager()->getRepository(Category::class)->findOneBy($id);
        return $getData;
    }
}

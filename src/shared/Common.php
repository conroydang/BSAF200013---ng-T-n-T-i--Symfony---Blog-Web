<?php

namespace App\shared;
use Doctrine\Persistence\ManagerRegistry;

class Common
{
    public function getManyContent(){
    $doctrine = new ManagerRegistry();
    $getData = $doctrine->getManager()->getRepository(Post::class)->findAll();
    return $getData;
    }

    public function getManyCategory(){
        $doctrine = new ManagerRegistry();
        $getData = $doctrine->getManager()->getRepository(Category::class)->findAll();
        return $getData;
    }

    public function getOneContent($id){
        $doctrine = new ManagerRegistry();
        $getData = $doctrine->getManager()->getRepository(Post::class)->findOneBy($id);
        return $getData;
    }

    public function getOneCategory($id){
        $doctrine = new ManagerRegistry();
        $getData = $doctrine->getManager()->getRepository(Category::class)->findOneBy($id);
        return $getData;
    }
}
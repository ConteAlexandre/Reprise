<?php


namespace App\Controller;


use lsolesen\pel\PelJpeg;
use lsolesen\pel\PelTag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
//        var_dump($_SESSION);

        return $this->render('pages-principales/index.html.twig', [
            'title' => 'Accueil',
        ]);
    }
}
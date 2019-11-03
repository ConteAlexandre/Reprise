<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{

    /**
     * @Route("/informations", name="info")
     */
    public function index()
    {
        return $this->render('pages-principales/inforamtions.html.twig', [
            'title' => 'Informations'
        ]);
    }

}
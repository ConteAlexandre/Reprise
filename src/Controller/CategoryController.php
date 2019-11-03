<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @Route("/new_category", name="new_category")
     */
    public function newCategory(Request $request)
    {
        $cat = new Category();

        $form = $this->createForm(CategoryForm::class, $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($cat);
            $em->flush();

            return $this->redirectToRoute('list_post');
        }

        return $this->render('/Category/new_category.html.twig', [
            'title' => 'Nouvelle Catégorie',
            'form' => $form->createView()
        ]);
    }

}
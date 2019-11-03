<?php


namespace App\Controller;


use App\Form\ContactForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    /**
     * @Route("/contact", name="contact")
     */
    public function contactIndex(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createForm(ContactForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $message = (new \Swift_Message($form->get('subject')->getData()))
                ->setContentType('text/html', 'utf8')
                ->setFrom('lesptitspoussinss@gmail.com')
                ->setTo($form->get('email')->getData())
                ->setBody($form->get('content')->getData())
                ;

            $mailer->send($message);

            return $this->redirectToRoute('index');
        }


        return $this->render('/pages-principales/contact.html.twig', [
            'title' => 'Contact',
            'form' => $form->createView()
        ]);
    }

}
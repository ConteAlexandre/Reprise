<?php


namespace App\Controller;


use App\Entity\Post;
use App\Entity\Users;
use App\Form\PostForm;
use App\Repository\PostRepository;
use App\Services\ImageUpload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{

    /**
     * @Route("/post", name="list_post")
     */
    public function listPost()
    {


        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();


        return $this->render('/Posts/index_post.html.twig', [
            'title' => 'Posts',
            'posts' => $posts,
        ]);

    }

    /**
     * @Route("/post/new", name="new_post")
     */
    public function newPost(Request $request, ImageUpload $upload)
    {
        $post = new Post();

        $form = $this->createForm(PostForm::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $image = $form->get('image')->getData();
            if (isset($image)){
                $imagename = $upload->upload($image);
                $post->setImage($imagename);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('list_post');
        }

        return $this->render('/Posts/new_post.html.twig', [
            'form' => $form->createView(),
            'title' => 'Créer Post'
        ]);
    }

    /**
     * @Route("/post/edit/{id}", name="edit_post")
     */
    public function editPost(Request $request, Post $post, ImageUpload $upload)
    {

        $id = $post->getId();

        $form = $this->createForm(PostForm::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $image = $form->get('image')->getData();
            if (isset($image)){
                $imagename = $upload->upload($image);
                $post->setImage($imagename);
            }
            $em = $this->getDoctrine()->getManager();
            $em->getRepository(Post::class);
            $em->persist($post);
            $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $em->flush();

            return $this->redirectToRoute('list_post');
        }

        return $this->render('/Posts/edit_post.html.twig', [
            'id' => $id,
            'title' => 'Edit',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/post/show/{id}/{userid}", name="show_post")
     * @param PostRepository $postRepository
     * @param $id
     * @param $userid
     * @return Response
     */
    public function showPost(PostRepository $postRepository, $id, $userid)
    {

        $entitymanager = $this->getDoctrine()->getManager();
        $cat = $postRepository->findCategory($id);
        $posts = $this->getDoctrine()->getManager()->getRepository(Post::class)->find($id);

        $name = $posts->getImage();

        $image = 'images/'.$name;

        $vue = $posts->getVue();

        $entitymanager->persist($posts->setVue($vue+1));
        $entitymanager->flush();

        $likepost = $postRepository->findUserid($posts->getId());

        foreach ($likepost as $value){
//            var_dump($value);
        }

        if ($name){
            $exif = exif_read_data($image, '0', true);
            return $this->render('/Posts/show_post.html.twig', [
                'title' => 'Détail',
                'posts' => $posts,
                'category' => $cat,
                'exif' => $exif,
                'likepost' => $value
            ]);
        } else {
            return $this->render('/Posts/show_post.html.twig', [
                'title' => 'Détail',
                'posts' => $posts,
                'category' => $cat,
            ]);
        }
    }

    /**
     * @Route("/post/delete/{id}", name="delete_post")
     */
    public function removePost($id)
    {
        $entitymanager = $this->getDoctrine()->getManager();
        $post = $entitymanager->getRepository(Post::class)->find($id);

        $entitymanager->remove($post);
        $entitymanager->flush();

        return $this->redirectToRoute('list_post');
    }

    /**
     * @param Post $post
     * @Route("/download/image/{id}", name="download_actio")
     * @return BinaryFileResponse
     */
    public function DownloadAction(Post $post)
    {

        $entitymanager = $this->getDoctrine()->getManager();
        $imagename = $post->getImage();
        $imagepath = $this->getParameter('images_directory').$imagename;

        $download = $post->getDownload();

        $entitymanager->persist($post->setDownload($download + 1));
        $entitymanager->flush();

        return $this->file($imagepath);

    }

    /**
     * @param Post $post
     * @param $user_id
     * @return RedirectResponse
     * @Route("/like/post/{id}/{user_id}", name="like_action")
     */
    public function LikeAction(Post $post, $user_id)
    {
        $entitymanager = $this->getDoctrine()->getManager();
        $users = $this->getDoctrine()->getRepository(Users::class)->find($user_id);

        $entitymanager->persist($users->addPost($post));
        $entitymanager->flush();

        $entitymanager->persist($post->setNbLikes($post->getNbLikes()+1));
        $entitymanager->flush();


        return $this->redirectToRoute('show_post', [
            'id' => $post->getId(),
            'userid' => $users->getId()
        ]);

    }

    /**
     * @param Post $post
     * @param $user_id
     * @return RedirectResponse
     * @Route("/dislike/post/{id}/{user_id}", name="dislike_action")
     */
    public function DisLikeAction(Post $post, $user_id)
    {
        $entitymanager =$this->getDoctrine()->getManager();
        $users = $this->getDoctrine()->getRepository(Users::class)->find($user_id);

        $entitymanager->persist($users->removePost($post));
        $entitymanager->flush();

        $entitymanager->persist($post->setNbLikes($post->getNbLikes()-1));
        $entitymanager->flush();


        return $this->redirectToRoute('show_post', [
            'id' => $post->getId(),
            'userid' => $users->getId()
        ]);

    }
}
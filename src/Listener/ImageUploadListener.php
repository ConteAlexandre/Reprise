<?php


namespace App\Listener;


use App\Entity\Post;
use App\Services\ImageUpload;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploadListener
{
    private $uploader;

    public function __construct(ImageUpload $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        if (!$entity instanceof Post){
            return;
        }

        $file = $entity->getImage();

        if ($file instanceof UploadedFile){
            $fileName = $this->uploader->upload($file);
            $entity->setImage($fileName);
        }elseif ($file instanceof File){
            $entity->setImage($file->getFilename());
        }
    }

}
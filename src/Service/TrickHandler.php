<?php

namespace App\Service;

use App\Entity\Media;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class TrickHandler
{
    /**
     * @var FileUploader
     */
    private FileUploader $fileUploader;
    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var VideoHelper
     */
    private VideoHelper $videoHelper;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(
        FileUploader $fileUploader,
        Security $security,
        VideoHelper $videoHelper,
        EntityManagerInterface $entityManager
    ) {
        $this->fileUploader = $fileUploader;
        $this->videoHelper = $videoHelper;
        $this->security = $security;
        $this->em = $entityManager;
    }

    /**
     * @param Trick         $trick
     * @param FormInterface $form
     *
     * @throws JsonException
     */
    public function handleTrick(Trick $trick, FormInterface $form): void
    {
        $trick = $this->setContributor($trick);

        if ($form->get('tricksMedia')) {
            $trick = $this->handleMediaCollection($trick, $form);
        }

        $this->em->persist($trick);
        $this->em->flush();
    }

    /**
     * @param Trick         $trick
     * @param FormInterface $form
     *
     * @throws JsonException
     *
     * @return Trick
     */
    private function handleMediaCollection(Trick $trick, FormInterface $form): Trick
    {
        $hasCoverImage = empty($trick->getTricksMedia()) ? false : true;
        foreach ($form->get('tricksMedia') as $trickMedia) {
            $type = $trickMedia->get('media')->getData()->getType();

            if (Media::IMAGE === $type) {
                $fileName = $this->handleImageUpload($trickMedia);
                $trickMedia->getData()->getMedia()->setUrl($fileName);
                if (!$hasCoverImage) {
                    $trickMedia->getData()->setIsCoverImage(true);
                    $hasCoverImage = true;
                }
            } else {
                $data = $this->videoHelper->getVideoData($trickMedia->get('media')->get('video_url')->getData());
                $trickMedia->getData()->getMedia()->setUrl($data['id']);
                $trickMedia->getData()->getMedia()->setType($data['type']);
            }

            $trick->addTricksMedium($trickMedia->getData());
        }

        return $trick;
    }

    /**
     * @param FormInterface $form
     *
     * @return string
     */
    private function handleImageUpload(FormInterface $form): string
    {
        return $this->fileUploader->upload($form->get('media')->get('image')->getData());
    }

    /**
     * @param Trick $trick
     *
     * @return Trick
     */
    private function setContributor(Trick $trick): Trick
    {
        $currentUser = $this->security->getUser();

        if (null === $trick->getAuthor()) {
            $trick->setAuthor($currentUser);
        }

        if ($currentUser !== $trick->getAuthor()) {
            $trick->addUser($currentUser);
        }

        return $trick;
    }
}

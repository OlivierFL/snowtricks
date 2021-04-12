<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileFormType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/profil", name="profile_")
 */
class UserProfileController extends AbstractController
{
    /**
     * @Route("/", name="index")
     *
     * @param Request                $request
     * @param FileUploader           $uploader
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function index(Request $request, FileUploader $uploader, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form->get('avatar')->getData();

            if ($avatar) {
                $avatarFileName = $this->handleAvatarUpload($user, $uploader, $avatar);

                $user->setAvatar($avatarFileName);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', User::PROFILE_UPDATED);

            return $this->redirectToRoute('profile_index');
        }

        return $this->render('user_profile/profile_index.html.twig', [
            'editProfileForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tricks", name="tricks")
     *
     * @param TrickRepository $trickRepository
     *
     * @return Response
     */
    public function tricks(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findBy(['author' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('user_profile/profile_tricks.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    /**
     * @Route("/comments", name="comments")
     *
     * @param CommentRepository $commentRepository
     *
     * @return Response
     */
    public function comments(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findBy(['author' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('user_profile/profile_comments.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @param null|object|UserInterface $user
     * @param FileUploader              $uploader
     * @param UploadedFile              $avatar
     *
     * @return string
     */
    private function handleAvatarUpload(?UserInterface $user, FileUploader $uploader, UploadedFile $avatar): string
    {
        $oldAvatar = $user->getAvatar();
        $avatarFileName = $uploader->upload($avatar, FileUploader::AVATARS_DIRECTORY);
        if ($oldAvatar) {
            $uploader->remove($oldAvatar, FileUploader::AVATARS_DIRECTORY);
        }

        return $avatarFileName;
    }
}

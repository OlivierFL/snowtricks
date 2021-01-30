<?php

namespace App\Controller;

use App\Form\EditProfileFormType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProfileController extends AbstractController
{
    /**
     * @Route("/profil", name="profile_index")
     * @param Request      $request
     * @param FileUploader $uploader
     *
     * @return Response
     */
    public function index(Request $request, FileUploader $uploader): Response
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès');

            return $this->redirectToRoute('profile_index');
        }

        return $this->render('user_profile/profile_index.html.twig', [
            'editProfileForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profil/tricks", name="profile_tricks")
     *
     * @return Response
     */
    public function tricks(): Response
    {
        $tricks = $this->getUser()->getAuthorTricks();

        return $this->render('user_profile/profile_tricks.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    /**
     * @param UserInterface|object|null $user
     * @param FileUploader              $uploader
     * @param UploadedFile              $avatar
     *
     * @return string
     */
    private function handleAvatarUpload(?UserInterface $user, FileUploader $uploader, UploadedFile $avatar): string
    {
        $oldAvatar = $user->getAvatar();
        $avatarFileName = $uploader->upload($avatar);
        if ($oldAvatar) {
            $uploader->remove($oldAvatar);
        }

        return $avatarFileName;
    }
}

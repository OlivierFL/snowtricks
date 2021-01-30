<?php

namespace App\Controller;

use App\Form\EditProfileFormType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
                $oldAvatar = $user->getAvatar();
                $avatarFileName = $uploader->upload($avatar);
                if ($oldAvatar) {
                    $uploader->remove($oldAvatar);
                }

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
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\StudentProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/profile')]
class ProfileController extends AbstractController
{

    #[Route(
        "/",
        name: "app_profile_index",
        methods: ["GET"]
    )]


    public function index(UserRepository $userRepository): Response
    {
        // Fetch all users
        $users = $userRepository->findAll();

        // Check if user is not an admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            /** @var \App\Entity\User $sessionUser */
            $sessionUser = $this->getUser();

            // Fetch the student associated with the user
            $student = $sessionUser->getStudent();

            // Fetch the school year of the student
            $schoolYear = $student->getSchoolYear();

            // Fetch users by school year
            $users = $userRepository->findBySchoolYear($schoolYear);
        }

        return $this->render(
            'profile/index.html.twig',
            [
                'users' => $users,
            ]
        );
    }


    #[Route(
        '/new',
        name: 'app_profile_new',
        methods: ['GET', 'POST']
    )]

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Check if the current user has the 'ROLE_ADMIN' role
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Create a new User instance
        $user = new User();

        // Create a form using the StudentProfileType form class and bind it to the User instance
        $form = $this->createForm(StudentProfileType::class, $user);
        $form->handleRequest($request);

        // Check if the form has been submitted and is valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the User object to the database
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect the user to the profile index page
            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        // Render the new profile form
        return $this->renderForm(
            'profile/new.html.twig',
            [
                'user' => $user,
                'form' => $form,
            ]
        );
    }

    #[Route(
        '/{id}',
        name: 'app_profile_show',
        methods: ['GET']
    )]
    /**
     * Deletes a user profile.
     *
     * @param Request $request The request object.
     * @param User $user The user to delete.
     * @param EntityManagerInterface $entityManager The entity manager.
     * @throws Some_Exception_Class description of exception
     * @return Response The response object.
     */
    public function show(User $user): Response
    {
        $this->filterSessionUser($user);

        return $this->render(
            'profile/show.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    #[Route(
        '/{id}/edit',
        name: 'app_profile_edit',
        methods: ['GET', 'POST']
    )]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->filterSessionUser($user);

        $form = $this->createForm(StudentProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            'profile/edit.html.twig',
            [
                'user' => $user,
                'form' => $form,
            ]
        );
    }

    #[Route(
        '/{id}',
        name: 'app_profile_delete',
        methods: ['POST']
    )]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/password', name: 'app_profile_password', methods: ['GET', 'POST'])]
    public function password(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->filterSessionUser($user);

        $form = $this->createForm(UserPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    private function filterSessionUser(User $user)
    {
        $sessionUser = $this->getUser();

        if ($user != $sessionUser) {
            // le user connecté essaie de consulter le profile d'un autre user.
            throw new AccessDeniedException();
        }
    }
}

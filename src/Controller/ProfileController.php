<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]

class ProfileController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_profile_edit')]
    public function edit(Request $request, Student $student, EntityManagerInterface $entityManager): Response
    {
        // TODO user == role_admin ? => accès
        // TODO user == role_user ? user ==propriétaire du profil ? => accès
        
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_show', [
                'id' => $student->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/edit.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_profile_show', methods: ['GET'])]
    public function show(Student $student): Response
    {
        return $this->render('profile/show.html.twig', [
            'student' => $student,
        ]);
    }
}

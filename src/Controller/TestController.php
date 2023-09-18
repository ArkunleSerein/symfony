<?php

namespace App\Controller;

use Exception;
use DateTime;
use App\Entity\Student;
use App\Entity\SchoolYear;
use App\Entity\Tag;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test')]

class TestController extends AbstractController
{
    #[Route('/tag', name: 'app_test_tag')]
    public function tag(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $studentRepository = $em->getRepository(Student::class);
        $tagRepository = $em->getRepository(Tag::class);

        // Création d'un nouvel objet
        $foo = new Tag();
        $foo->setName('foo');
        $foo->setDescription('Foo Bar Baz');
        $em->persist($foo);

        try {
            $em->flush();
        } catch (Exception $e) {
            // gérer l'erreur
            dump($e->getMessage());
        }


        // récupération de l'objet dont l'id est 1
        $tag = $tagRepository->find(1);

        // récupération de l'objet dont l'id est 1
        $tag20 = $tagRepository->find(20);

        // suppression de l'objet seulement s'il existe
        if ($tag20) {
            // suppression de l'objet
            $em->remove($tag20);
            $em->flush();
        }
        // récupération de l'objet dont l'id est 1
        $tag4 = $tagRepository->find(4);

        // pas la peine d'utiliser persist() si l'objet provient de la BDD
        $em->flush();


        $student = $studentRepository->find(1);
        // association du tag 4 au student 1
        $student->addTag($tag4);
        $em->flush();

        //* récupération d'un tag dont le nom est css
        $cssTag = $tagRepository->findOneBy([
            //* critères de recherche
            'name' => 'CSS',
        ]);

        //* récupération des tags dont la description est nulle
        $nullDescriptionTags = $tagRepository->findBy([
            //* critères de recherche
            'description' => null,
        ],[ 
            //critères de tri
            'name' => 'ASC',
        ]);

        //* récupération de tout les tags avec description
        $notNullDescriptionTags = $tagRepository->findByNotNullDescription();

        // récupération de la liste complète des objets
        $tags = $tagRepository->findAll();

        // récupération des tags qui contiennent certains mot-clés
        $keywordTag1 = $tagRepository->findByKeyword('HTML');
        $keywordTags2 = $tagRepository->findByKeyword('CSS');

        //récupération des tags à partir d'une schoolYear// Récupération des tags contenant certains mots clés

        $schoolYearRepository = $em->getRepository(SchoolYear::class);
        $schoolYear = $schoolYearRepository->find(1);
        $schoolYearTags = $tagRepository->findBySchoolYear($schoolYear);

        // mise à jour des relations d'un tag
        $studentRepository = $em->getRepository(Student::class);
        //  Récupération du student dont l'id est 2
        $student = $studentRepository->find(2);
        $htmlTag = $tagRepository->find(1);
        $htmlTag->addStudent($student);
        $em->flush();
        


        $title = 'Test des tags';

        return $this->render('test/tag.html.twig', [
            'controller_name' => 'TestController',
            'title' => $title,
            'tags' => $tags,
            'tag' => $tag,
            'cssTag' => $cssTag,
            'nullDescriptionTags' => $nullDescriptionTags,
            'notNullDescriptionTags' => $notNullDescriptionTags,
            'keywordTags1' => $keywordTag1,
            'schoolYearTags' => $schoolYearTags,
            'htmlTag' => $htmlTag,
        ]);
    }

    #[Route('/school-year', name: 'app_test_schoolYear')]
    public function schoolYear(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $schoolYearRepository = $em->getRepository(SchoolYear::class);
        $schoolYears = $schoolYearRepository->findAll();

        $title2 = 'Test des schoolYear';

        // Création d'un nouvel objet
        $bar = new SchoolYear();
        $bar->setName('bar');

        $startDate = new DateTime('2022-01-01'); // Utilisez des tirets (-) pour les dates
        $bar->setStartDate($startDate);

        $endDate = new DateTime('2022-02-02'); // Utilisez des tirets (-) pour les dates
        $bar->setEndDate($endDate);

        // Persiste l'objet dans la base de données
        $em->persist($bar); // Ajoutez cette ligne pour persiste l'objet

        // Récupère l'objet avec l'ID 1
        $schoolYear = $schoolYearRepository->find(1); // Utilisez simplement "find" pour l'ID

        // Récupère l'objet avec l'ID 7 ou `null` si l'objet n'existe pas
        $ipsum = $schoolYearRepository->find(7); // Utilisez simplement "find" pour l'ID

        // Récupère l'objet avec l'ID 4
        $baz = $schoolYearRepository->find(4); // Utilisez simplement "find" pour l'ID

        // Met à jour la description de l'objet
        if ($baz) {
            $baz->setDescription('Python');
            // Pas besoin de "flush" ici car Symfony gère automatiquement les modifications des entités persistantes
        }

        // Persiste les modifications dans la base de données
        $em->flush();

        return $this->render('test/school-year.html.twig', [
            'controller_name' => 'TestController',
            'title' => $title2,
            'schoolYears' => $schoolYears,
        ]);
    }
}

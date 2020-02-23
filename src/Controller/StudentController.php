<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Student;
use App\Repository\StudentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class StudentController extends AbstractController
{
    /**
     * @Route("/student", name="student_list")
     */
    public function index()
    {
        $studentRepository = $this->getDoctrine()->getRepository('App:Student');
        $students = $studentRepository->findAll();

        $template = 'student/index.html.twig';
        $args = [
            'students' => $students
        ];
        return $this->render($template, $args);
    }


    /**
     * @Route("/student/new", name="student_new_form")
     */
    public function newForm()
    {
        $template = 'student/new.html.twig';
        $args = [
        ];
        return $this->render($template, $args);
    }

    /**
     * @Route("/student/processNewForm", name="student_process_new_form")
     */
    public function processNewForm(Request $request)
    {
        // extract name values from POST data
        $firstName = $request->request->get('firstName');
        $surname = $request->request->get('surname');

        // forward this to the createAction() method
        return $this->create($firstName, $surname);
    }

    /**
     * @Route("/student/{id}", name="student_show")
     */
    public function show(Student $student)
    {
        $template = 'student/show.html.twig';
        $args = [
            'student' => $student
        ];

        if (!$student) {
            $template = 'error/404.html.twig';
        }

        return $this->render($template, $args);
    }

    private function create($firstName, $surname)
    {
        $student = new Student();
        $student->setFirstName($firstName);
        $student->setSurname($surname);

        // entity manager
        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($student);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return $this->redirectToRoute('student_list');
    }

    /**
     * @Route("/student/delete/{id}")
     */
    public function delete(Student $student)
    {
        // entity manager
        $em = $this->getDoctrine()->getManager();

        // store ID before deleting, so can report ID later
        $id = $student->getId();

        // tells Doctrine you want to (eventually) delete the Student (no queries yet)
        $em->remove($student);

        // actually executes the queries (i.e. the DELETE query)
        $em->flush();

        return new Response('Deleted student with id '.$id);
    }

    /**
     * @Route("/student/update/{id}/{newFirstName}/{newSurname}")
     */
    public function update(Student $student, $newFirstName, $newSurname)
    {
        $em = $this->getDoctrine()->getManager();

        $student->setFirstName($newFirstName);
        $student->setSurname($newSurname);
        $em->flush();

        return $this->redirectToRoute('student_show', [
            'id' => $student->getId()
        ]);
    }



}

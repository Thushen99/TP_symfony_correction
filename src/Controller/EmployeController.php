<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeController extends AbstractController
{
    /**
     * @Route("/", name="app_employe")
     */
    public function index(): Response
    {
        return $this->render('employe/index.html.twig', [
            'controller_name' => 'EmployeController',
        ]);
    }

    /**
     * @Route("employes", name="list_employe")
     */
    public function listEmploye(EmployeRepository $employeRepository)
    {
        $employes = $employeRepository->findAll();

        return $this->render("employe/list_employe.html.twig", ['employes' => $employes]);
    }

    /**
     * @Route("/employe/{id}", name="show_employe")
     */
    public function showEmploye(EmployeRepository $employeRepository, $id)
    {
        $employe = $employeRepository->find($id);

        return $this->render("employe/show_employe.html.twig", ['employe' => $employe]);
    }

    /**
     * @Route("create/employe", name="create_employe")
     */
    public function createEmploye(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $employe = new Employe();

        $employeForm = $this->createForm(EmployeType::class, $employe);

        $employeForm->handleRequest($request);

        if ($employeForm->isSubmitted() && $employeForm->isValid()) {
            $entityManagerInterface->persist($employe);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('list_employe');
        }

        return $this->render("employe/employe_form.html.twig", ['employeForm' => $employeForm->createView()]);
    }

    /**
     * @Route("update/employe/{id}", name="update_employe")
     */
    public function updateEmploye($id,  EmployeRepository $employeRepository, EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $employe = $employeRepository->find($id);

        $employeForm = $this->createForm(EmployeType::class, $employe);

        $employeForm->handleRequest($request);

        if ($employeForm->isSubmitted() && $employeForm->isValid()) {
            $entityManagerInterface->persist($employe);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('list_employe');
        }

        return $this->render("employe/employe_form.html.twig", ['employeForm' => $employeForm->createView()]);
    }

    /**
     * @Route("delete/employe/{id}", name="delete_employe")
     */
    public function deleteEmploye(EmployeRepository $employeRepository, $id, EntityManagerInterface $entityManagerInterface)
    {
        $employe = $employeRepository->find($id);
        $entityManagerInterface->remove($employe);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('list_employe');
    }
}

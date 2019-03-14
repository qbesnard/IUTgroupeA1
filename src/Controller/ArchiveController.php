<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/archive/task")
 */
class ArchiveController extends AbstractController
{
    /**
     * @Route("/", name="archive_task_index", methods={"GET"})
     */
    public function index(TaskRepository $taskRepository, EntityManagerInterface $entityManager): Response
    {
        $entityManager->getFilters()->disable("deleted");
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findDeleted(),
        ]);
    }
}

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
 * @Route("/task")
 */
class TaskController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $entityManager->getFilters()->disable('deleted');
    }

    /**
     * @Route("/", name="task_index", methods={"GET"})
     */
    public function index(TaskRepository $taskRepository, EntityManagerInterface $entityManager): Response
    {
        $entityManager->getFilters()->enable('deleted');
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findBy([], ['priority' => 'DESC']),
        ]);
    }

    /**
     * @Route("/archives", name="task_archive_index", methods={"GET"})
     */
    public function archiveIndex(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findDeleted(),
        ]);
    }

    /**
     * @Route("/new", name="task_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $last = $em->getRepository(Task::class)->findLast();
            $rank = empty($last) ? 0 : $last[0]->getId();
            $task->setRank($rank);
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_show", methods={"GET"})
     */
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="task_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Task $task): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_edit', [
                'id' => $task->getId(),
            ]);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Task $task)
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }

        return $this->redirectToRoute('task_index');
    }

    /**
     * @Route("/{id}/delete", name="task_soft_delete", methods={"POST"})
     */
    public function softDelete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('soft-delete' . $task->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            if (null === $task->getDeletedAt()) {
                $task->setDeletedAt(new \DateTime());
            } else {
                $task->setDeletedAt(null);
            }
            $em->flush();
        }

        return $this->redirectToRoute('task_index');
    }
}

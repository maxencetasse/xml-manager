<?php

namespace App\Controller;

use App\Entity\Node;
use App\Form\NodeType;
use App\Repository\NodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/node')]
final class NodeController extends AbstractController
{
    #[Route(name: 'app_node_index', methods: ['GET'])]
    public function index(NodeRepository $nodeRepository): Response
    {
        return $this->render('node/index.html.twig', [
            'nodes' => $nodeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_node_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $node = new Node();
        $form = $this->createForm(NodeType::class, $node);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($node);
            $entityManager->flush();

            return $this->redirectToRoute('app_node_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('node/new.html.twig', [
            'node' => $node,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_node_show', methods: ['GET'])]
    public function show(Node $node): Response
    {
        return $this->render('node/show.html.twig', [
            'node' => $node,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_node_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Node $node, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NodeType::class, $node);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_node_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('node/edit.html.twig', [
            'node' => $node,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_node_delete', methods: ['POST'])]
    public function delete(Request $request, Node $node, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$node->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($node);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_node_index', [], Response::HTTP_SEE_OTHER);
    }
}

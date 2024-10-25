<?php

namespace App\Controller\Admin;

use App\Entity\News;
use App\Form\NewsType;
use App\Service\NewsService;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(['/Admin/News', '/admin/news'], name: 'admin_news_')]
class NewsAdminController extends AbstractController
{
    private NewsService $newsService;
    private EntityManagerInterface $EntityManager;

    public function __construct(NewsService $newsService, EntityManagerInterface $EntityManager)
    {
        $this->newsService = $newsService;
        $this->EntityManager = $EntityManager;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $newsList = $this->newsService->getListNewsByLang();
        return $this->render('Admin/News/Index.twig', [
            'newsList' => $newsList,
        ]);
    }

    #[Route(['/New', '/new'], name: 'new')]
    public function new(Request $request): Response
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $news->setCreatedAt(new \DateTime());
            $this->EntityManager->persist($news);
            $this->EntityManager->flush();
            return $this->redirectToRoute('admin_news_index');
        }

        return $this->render('admin/news/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(string $id): Response
    {
        $newsData = $this->newsService->getNewsById(Uuid::fromString($id));
    
        if (!$newsData) {
            throw $this->createNotFoundException('News not found');
        }

        return $this->render('Admin/News/Show.twig', [
            'news' => $newsData,
        ]);
    }

    #[Route(['/Edit/{id}', '/edit/{id}'], name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, News $news): Response
    {
        $form = $this->createForm(NewsType::class, $news);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->EntityManager->flush();
            return $this->redirectToRoute('admin_news_index');
        }

        return $this->render('Admin/News/Edit.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(['Delete/{id}', 'delete/{id}'], name: 'delete', methods: ['POST'])]
    public function delete(Request $request, News $news): Response
    {
        if ($this->isCsrfTokenValid('delete' . $news->getId(), $request->request->get('_token'))) {
            $this->EntityManager->remove($news);
            $this->EntityManager->flush();
        }
        return $this->redirectToRoute('admin_news_index');
    }
}

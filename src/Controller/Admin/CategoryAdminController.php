<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\CategoryService;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Contrôleur pour la gestion des catégories dans l'administration.
 * Fournit des actions pour lister, créer, modifier et supprimer des catégories.
 */
#[IsGranted('ROLE_ADMIN')] 
#[Route(['/Admin/Category', '/admin/category'], name: 'admin_category_')]
class CategoryAdminController extends AbstractController {

    private $categoryService;

    /**
     * Affiche la liste des catégories.
     *
     * @param CategoryRepository $categoryRepository Le repository pour accéder aux catégories.
     * @return Response La réponse HTTP avec la vue affichant les catégories.
     */
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository, CategoryService $categoryService): Response {
        // Récupère toutes les catégories
        $categories = $categoryRepository->findAll();

        // Rend la vue avec les catégories
        return $this->render('Admin/Category/Index.twig', [
            'categories' => $categories,
            //'categories' => $this->categoryService->getByLang() ?? [],
        ]);
    }

    /**
     * Crée une nouvelle catégorie.
     *
     * @param Request $request La requête HTTP.
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     * @return Response La réponse HTTP avec la vue du formulaire de création.
     */
    #[Route(['/New', '/new'], name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Persiste la nouvelle catégorie en base de données
            $entityManager->persist($category);
            $entityManager->flush();

            // Ajoute un message flash de succès
            $this->addFlash('success', 'Category added successfully.');

            // Redirige vers la liste des catégories
            return $this->redirectToRoute('admin_category_index');
        }

        // Rend la vue avec le formulaire de création
        return $this->render('Admin/Category/Form.twig', [
            'form' => $form->createView(),
            'action' => 'Create',
        ]);
    }

    /**
     * Modifie une catégorie existante.
     *
     * @param Request $request La requête HTTP.
     * @param Category $category La catégorie à modifier.
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     * @return Response La réponse HTTP avec la vue du formulaire de modification.
     */
    #[Route(['/Edit/{id}', '/edit/{id}'], name: 'edit')]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response {

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Met à jour la catégorie en base de données
            $entityManager->flush();

            // Ajoute un message flash de succès
            $this->addFlash('success', 'Category updated successfully.');

            // Redirige vers la liste des catégories
            return $this->redirectToRoute('admin_category_index');
        }

        // Rend la vue avec le formulaire de modification
        return $this->render('Admin/Category/Form.twig', [
            'form' => $form->createView(),
            'action' => 'Edit',
        ]);
    }

    /**
     * Supprime une catégorie.
     *
     * @param Request $request La requête HTTP.
     * @param Category $category La catégorie à supprimer.
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités.
     * @return Response La réponse HTTP redirigeant vers la liste des catégories.
     */
    #[Route(['/Delete/{id}', '/delete/{id}'], name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response {

        // Vérifie la validité du token CSRF
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {

            // Supprime la catégorie de la base de données
            $entityManager->remove($category);
            $entityManager->flush();

            // Ajoute un message flash de succès
            $this->addFlash('success', 'Category deleted successfully.');
        }

        // Redirige vers la liste des catégories
        return $this->redirectToRoute('admin_category_index');
    }
}

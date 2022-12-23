<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookRepository;
use App\Entity\Book;

class BookController extends AbstractController
{
    #[Route('/books', name: 'books_list', methods: ['GET'])]
    public function index(BookRepository $bookRepository): JsonResponse
    {
        return $this->json([
            'data' => $bookRepository->findAll()
        ]);
    }

    #[Route('/books/{bookId}', name: 'books_single', methods: ['GET'])]
    public function single(int $bookId, BookRepository $bookRepository):JsonResponse
    {
        $book = $bookRepository->find($bookId);

        if(!$book) throw $this->createNotFoundException();

        return $this->json([
            'data' => $book
        ]);
    }

    #[Route('/books/{bookId}', name: 'books_update', methods: ['PUT', 'PATCH'])]
    public function update(int $bookId, BookRepository $bookRepository, Request $request): JsonResponse
    {
        $book = $bookRepository->find($bookId);
        if(!$book) throw $this->createNotFoundException();

        $data = $request->request->all();
        
        $book->setTitle($data['title']);
        $book->setIsbn($data['isbn']);
        $book->setUpdatedAt();

        $bookRepository->update($book);

        return $this->json([
            'message' => 'Book updated successfully',
            'data' => $book
        ]);
    }

    #[Route('/books', name:'books_create', methods: ['POST'])]
    public function create(Request $request, BookRepository $bookRepository):JsonResponse
    {
        $data = $request->request->all();

        $book = new Book();
        $book->setTitle($data['title']);
        $book->setIsbn($data['isbn']);
        $book->setCreatedAt();
        $book->setUpdatedAt();

        $bookRepository->save($book, true);

        return $this->json([
            'message' => 'Book created successfully',
            'data' => $book
        ], 201);
    }

    #[Route('/books/{bookId}', name: 'books_remove', methods: ['DELETE'])]
    public function remove(int $bookId, BookRepository $bookRepository):JsonResponse
    {
        $book = $bookRepository->find($bookId);
        if(!$book) throw $this->createNotFoundException();

        $bookRepository->remove($book, true);
        return $this->json([
            'message' => 'Book deleted successfully',
            'data' => $book
        ]);
    }
}

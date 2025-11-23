<?php
namespace App\Controllers;
require_once __DIR__ . '/../models/BookModel.php';

use App\Models\BookModel;

class BookController {
    private $twig;
    private $bookModel;

    public function __construct($twig) {
        $this->twig = $twig;
        $this->bookModel = new BookModel();
    }

    private function requireLogin() {
        if (empty($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?p=auth/login');
            exit;
        }
    }

    public function list() {
        $q = $_GET['q'] ?? '';
        $genre = $_GET['genre'] ?? '';
        $year = $_GET['year'] ?? '';

        $books = $this->bookModel->search($q, $genre, $year);
        echo $this->twig->render('books/list.html.twig', ['books' => $books, 'q' => $q, 'genre' => $genre, 'year' => $year, 'csrf' => \App\csrf_token()]);
    }

    public function create() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf'] ?? '';
            if (!\App\verify_csrf($token)) die('Invalid CSRF');

            $data = [
                'title' => $_POST['title'] ?? '',
                'author' => $_POST['author'] ?? '',
                'genre' => $_POST['genre'] ?? '',
                'year' => $_POST['year'] ?? null,
                'description' => $_POST['description'] ?? '',
                'created_by' => $_SESSION['user']['id']
            ];
            $this->bookModel->create($data);
            header('Location: ' . BASE_URL . '?p=books/list');
            exit;
        }
        echo $this->twig->render('books/form.html.twig', ['action' => 'create', 'csrf' => \App\csrf_token()]);
    }

    public function edit($id = null) {
        $this->requireLogin();
        if (!$id) { header('Location: ' . BASE_URL . '?p=books/list'); exit; }
        $book = $this->bookModel->find($id);
        if (!$book) { header('Location: ' . BASE_URL . '?p=books/list'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf'] ?? '';
            if (!\App\verify_csrf($token)) die('Invalid CSRF');
            $data = [
                'title' => $_POST['title'] ?? '',
                'author' => $_POST['author'] ?? '',
                'genre' => $_POST['genre'] ?? '',
                'year' => $_POST['year'] ?? null,
                'description' => $_POST['description'] ?? '',
            ];
            $this->bookModel->update($id, $data);
            header('Location: ' . BASE_URL . '?p=books/list');
            exit;
        }

        echo $this->twig->render('books/form.html.twig', ['action' => 'edit', 'book' => $book, 'csrf' => \App\csrf_token()]);
    }

    public function delete($id = null) {
        $this->requireLogin();
        if (!$id) { header('Location: ' . BASE_URL . '?p=books/list'); exit; }
        $token = $_POST['csrf'] ?? '';
        if (!\App\verify_csrf($token)) die('Invalid CSRF');
        $this->bookModel->delete($id);
        header('Location: ' . BASE_URL . '?p=books/list');
        exit;
    }
}

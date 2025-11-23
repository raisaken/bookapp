<?php
namespace App\Controllers;
require_once __DIR__ . '/../models/BookModel.php';
use App\Models\BookModel;

class AjaxController {
    private $bookModel;
    public function __construct($twig=null) {
        $this->bookModel = new BookModel();
    }

    public function titles() {
        header('Content-Type: application/json');
        $term = $_GET['term'] ?? '';
        $res = $this->bookModel->autocompleteTitles($term);
        echo json_encode($res);
    }
}

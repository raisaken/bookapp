<?php

namespace App\Models;

require_once __DIR__ . '/../../src/helpers.php';

class BookModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = \App\getPDO();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO books (title, author, genre, year, description, created_by) VALUES (:title, :author, :genre, :year, :description, :created_by)');
        $stmt->execute([
            'title' => $data['title'],
            'author' => $data['author'],
            'genre' => $data['genre'],
            'year' => $data['year'] ?: null,
            'description' => $data['description'],
            'created_by' => $data['created_by']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM books WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE books SET title = :title, author = :author, genre = :genre, year = :year, description = :description WHERE id = :id');
        $stmt->execute([
            'title' => $data['title'],
            'author' => $data['author'],
            'genre' => $data['genre'],
            'year' => $data['year'] ?: null,
            'description' => $data['description'],
            'id' => $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM books WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }

    // Search with multiple criteria
    public function search($q = '', $genre = '', $year = '')
    {
        $sql = 'SELECT b.*, u.username FROM books b JOIN users u ON u.id = b.created_by WHERE 1=1';
        $params = [];

        if ($q !== '') {
            $sql .= ' AND (b.title LIKE :q1 OR b.author LIKE :q2 OR b.description LIKE :q3)';
            $search_term = '%' . $q . '%';

            $params['q1'] = $search_term;
            $params['q2'] = $search_term;
            $params['q3'] = $search_term;
        }
        if ($genre !== '') {
            $sql .= ' AND b.genre = :genre';
            $params['genre'] = $genre;
        }
        if ($year !== '') {
            $sql .= ' AND b.year = :year';
            $params['year'] = (int)$year;
        }

        $sql .= ' ORDER BY b.created_at DESC LIMIT 200';
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            echo "SQL Error: " . $e->getMessage();
            return [];
        }
    }

    public function autocompleteTitles($term)
    {
        $stmt = $this->pdo->prepare('SELECT id, title FROM books WHERE title LIKE :t LIMIT 10');
        $stmt->execute(['t' => $term . '%']);
        return $stmt->fetchAll();
    }
}

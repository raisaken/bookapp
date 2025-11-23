<?php
namespace App\Models;
require_once __DIR__ . '/../../src/helpers.php';

class UserModel {
    private $pdo;

    public function __construct() {
        $this->pdo = \App\getPDO();
    }

    public function findByUsername($username) {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE username = :u LIMIT 1');
        $stmt->execute(['u' => $username]);
        return $stmt->fetch();
    }

    public function create($username, $email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare('INSERT INTO users (username, email, password) VALUES (:u, :e, :p)');
        $stmt->execute(['u' => $username, 'e' => $email, 'p' => $hash]);
        return $this->pdo->lastInsertId();
    }
}

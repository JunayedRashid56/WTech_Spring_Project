<?php

class User
{

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM users WHERE email = ?"
        );

        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($name, $email, $password_hash, $address)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users(name,email,password_hash,delivery_address)
             VALUES(?,?,?,?)"
        );

        return $stmt->execute([
            $name,
            $email,
            $password_hash,
            $address
        ]);
    }
    public function updateProfile($id, $name, $email, $address)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE users
             SET name=?, email=?, delivery_address=?
             WHERE id=?"
        );

        return $stmt->execute([
            $name,
            $email,
            $address,
            $id
        ]);
    }
    public function updatePassword($id, $password_hash)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE users SET password_hash=? WHERE id=?"
        );

        return $stmt->execute([
            $password_hash,
            $id
        ]);
    }
    public function updateRememberToken($id, $token)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE users SET remember_token=? WHERE id=?"
        );

        return $stmt->execute([
            $token,
            $id
        ]);
    }
    public function findByRememberToken($token)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM users WHERE remember_token=?"
        );

        $stmt->execute([$token]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

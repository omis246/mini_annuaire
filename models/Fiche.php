<?php
require_once __DIR__ . '/../config/Database.php';

class Fiche
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query('SELECT * FROM fiches');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM fiches WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($libelle, $description, $categories = [])
    {
        $stmt = $this->db->prepare('INSERT INTO fiches (libelle, description) VALUES (?, ?)');
        $stmt->execute([$libelle, $description]);
        $fiche_id = $this->db->lastInsertId();
        $this->updateCategories($fiche_id, $categories);
        return $fiche_id;
    }

    public function update($id, $libelle, $description, $categories = [])
    {
        $stmt = $this->db->prepare('UPDATE fiches SET libelle = ?, description = ? WHERE id = ?');
        $stmt->execute([$libelle, $description, $id]);
        $this->updateCategories($id, $categories);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM fiches WHERE id = ?');
        $stmt->execute([$id]);
        $stmt = $this->db->prepare('DELETE FROM fiche_categorie WHERE fiche_id = ?');
        $stmt->execute([$id]);
    }

    public function getCategories($id_fiche)
    {
        $stmt = $this->db->prepare('SELECT c.* FROM categories c JOIN fiche_categorie fc ON c.id = fc.categorie_id WHERE fc.fiche_id = ?');
        $stmt->execute([$id_fiche]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategorie($id_categorie)
    {
        $stmt = $this->db->prepare('SELECT f.* FROM fiches f JOIN fiche_categorie fc ON f.id = fc.fiche_id WHERE fc.categorie_id = ?');
        $stmt->execute([$id_categorie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function updateCategories($fiche_id, $categories)
    {
        $stmt = $this->db->prepare('DELETE FROM fiche_categorie WHERE fiche_id = ?');
        $stmt->execute([$fiche_id]);
        if (!empty($categories)) {
            $stmt = $this->db->prepare('INSERT INTO fiche_categorie (fiche_id, categorie_id) VALUES (?, ?)');
            foreach ($categories as $cat_id) {
                $stmt->execute([$fiche_id, $cat_id]);
            }
        }
    }
}

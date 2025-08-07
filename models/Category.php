<?php
require_once __DIR__ . '/../config/Database.php';

class Categorie
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query('SELECT * FROM categories');
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($libelle, $id_parent = null)
    {
        // Empêcher l'insertion de doublons exacts (libellé + parent)
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM categories WHERE libelle = ? AND '.($id_parent === null ? 'id_parent IS NULL' : 'id_parent = ?'));
        $stmt->execute($id_parent === null ? [$libelle] : [$libelle, $id_parent]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Déjà existant
        }
        $stmt = $this->db->prepare('INSERT INTO categories (libelle, id_parent) VALUES (?, ?)');
        $stmt->execute([$libelle, $id_parent]);
        return $this->db->lastInsertId();
    }

    public function update($id, $libelle, $id_parent = null)
    {
        $stmt = $this->db->prepare('UPDATE categories SET libelle = ?, id_parent = ? WHERE id = ?');
        return $stmt->execute([$libelle, $id_parent, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function getChildren($id_parent)
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id_parent = ?');
        $stmt->execute([$id_parent]);
        return $stmt->fetchAll();
    }

    public function getTree($id_parent = null)
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id_parent ' . ($id_parent === null ? 'IS NULL' : '= ?'));
        $stmt->execute($id_parent === null ? [] : [$id_parent]);
        $categories = $stmt->fetchAll();
        foreach ($categories as &$cat) {
            $cat['enfants'] = $this->getTree($cat['id']);
        }
        return $categories;
    }
}

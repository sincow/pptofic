<?php
require_once __DIR__ . '/../../config/Database.php';

class Usuario {
   private int $id_user;
   private string $nombre;
   private string $email;
   private string $password;
   private string $id_role;
   private string $status;

   // ✅ Constructor
   public function __construct(array $data = []) {
      if (!empty($data)) {
         $this->id_user   = $data['id'] ?? 0;
         $this->nombre   = $data['nombre'] ?? '';
         $this->email    = $data['email'] ?? '';
         $this->password = $data['password'] ?? '';
         $this->id_role  = $data['role'] ?? '';
         $this->status   = $data['estado'] ?? 'activo';
      }
   }

   // ================================
   // Métodos Estáticos (no requieren instancia)
   // ================================

   // 🔑 Login de usuario
   public static function login(string $email, string $password) {
      $db = Database::getConnectionSite();
      $stmt = $db->prepare("SELECT a.*, b.* FROM users a 
         LEFT JOIN companies b ON a.id_empresa = b.id_empresa
         WHERE email = :email LIMIT 1"
      );
      $stmt->bindParam(':email', $email);
      $stmt->execute();
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($data && password_verify($password, $data['password'])) {
			unset($data['password']);
         $resp =  $data;
         // return new Usuario($data);
      } else {
         $resp = null;
      }
		$db = null;
		return $resp;
   }


   // 📌 Obtener usuario por ID
   public static function obtenerPorId(int $id): ?Usuario {
      $db = Database::getConnection();
      $stmt = $db->prepare("SELECT * FROM users WHERE id_user = :id_user LIMIT 1");
      $stmt->bindParam(':id_user', $id);
      $stmt->execute();

      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      return $data ? new Usuario($data) : null;
   }


   // 📋 Obtener
   public static function obtenerTodos(): array {
      $db = Database::getConnection();
      $stmt = $db->query("SELECT * FROM users ORDER BY nombre ASC");
      $users = [];

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
         $users[] = new Usuario($row);
      }

      return $users;
   }

   // ================================
   // Métodos de Instancia (requieren objeto)
   // ================================

   // 📝 Guardar nuevo usuario
   public function guardar(): bool {
      $db = Database::getConnection();
      $hash = password_hash($this->password, PASSWORD_BCRYPT);
      $stmt = $db->prepare("INSERT INTO users (nombre, email, password, id_role) 
         VALUES (:nombre, :email, :password, :id_role)"
      );
      return $stmt->execute([
         ':nombre'   => $this->nombre,
         ':email'    => $this->email,
         ':password' => $hash,
         ':id_role'  => $this->id_role
      ]);
   }


   // ✏️ Actualizar usuario existente
   public function actualizar(): bool {
      $db = Database::getConnection();
      if (!empty($this->password)) {
         $hash = password_hash($this->password, PASSWORD_BCRYPT);
         $stmt = $db->prepare("UPDATE users SET nombre = :nombre, email = :email, 
            password  = :password, id_role = :id_role 
            WHERE id_user = :id_user"
         );
         return $stmt->execute([
            ':nombre'   => $this->nombre,
            ':email'    => $this->email,
            ':password' => $hash,
            ':role'     => $this->id_role,
            ':id_user'  => $this->id_user
         ]);
      } else {
         $stmt = $db->prepare("UPDATE users SET nombre = :nombre, email = :email, 
            id_role = :id_role WHERE id_user = :id_user");
         return $stmt->execute([
            ':nombre'   => $this->nombre,
            ':email'    => $this->email,
            ':role'     => $this->id_role,
            ':id_user'  => $this->id_user
         ]);
      }
   }


   // 🗑️ Eliminar usuario
   public function eliminar(): bool {
      $db = Database::getConnection();
      $stmt = $db->prepare("DELETE FROM users WHERE id_user = :id_user");
      return $stmt->execute([':id_user' => $this->id_user]);
   }


   // ================================
   // Getters y Setters
   // ================================
   public function getId(): int {
      return $this->id_user;
   }
   public function getNombre(): string {
      return $this->nombre;
   }
   public function getEmail(): string {
      return $this->email;
   }
   public function getRole(): string {
      return $this->id_role;
   }

   public function setNombre(string $nombre): void {
      $this->nombre = $nombre;
   }
   public function setEmail(string $email): void {
      $this->email = $email;
   }
   public function setPassword(string $password): void {
      $this->password = $password;
   }
   public function setRole(string $role): void {
      $this->id_role = $role;
   }
}

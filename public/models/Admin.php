<?php
require_once __DIR__ . '/../config/database.php';

class Admin {
    private $pdo;
    
    public function __construct() {
        $this->pdo = getConnection();
    }
    
    // Login
    public function login($email, $senha) {
        $sql = "SELECT * FROM admins WHERE email = :email AND ativo = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($senha, $admin['senha'])) {
            // Iniciar sessão
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nome'] = $admin['nome'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_avatar'] = $admin['avatar'];
            
            return ['success' => true, 'admin' => $admin];
        }
        
        return ['success' => false, 'error' => 'Email ou senha incorretos'];
    }
    
    // Logout
    public function logout() {
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_nome']);
        unset($_SESSION['admin_email']);
        unset($_SESSION['admin_avatar']);
        session_destroy();
        return true;
    }
    
    // Buscar por ID
    public function getById($id) {
        $sql = "SELECT id, nome, email, avatar, ativo, created_at FROM admins WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Buscar todos
    public function getAll() {
        $sql = "SELECT id, nome, email, avatar, ativo, created_at FROM admins ORDER BY nome ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Criar admin
    public function create($data) {
        // Verificar se email já existe
        $sql = "SELECT id FROM admins WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $data['email']]);
        
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'Este email já está cadastrado'];
        }
        
        $sql = "INSERT INTO admins (nome, email, senha, avatar, ativo) 
                VALUES (:nome, :email, :senha, :avatar, :ativo)";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->execute([
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':senha' => password_hash($data['senha'], PASSWORD_DEFAULT),
            ':avatar' => $data['avatar'] ?? null,
            ':ativo' => $data['ativo'] ?? 1
        ]);
        
        return ['success' => true, 'id' => $this->pdo->lastInsertId()];
    }
    
    // Atualizar admin
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];
        
        if (isset($data['nome'])) {
            $fields[] = "nome = :nome";
            $params[':nome'] = $data['nome'];
        }
        
        if (isset($data['email'])) {
            // Verificar se email já existe para outro admin
            $sql = "SELECT id FROM admins WHERE email = :email AND id != :check_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':email' => $data['email'], ':check_id' => $id]);
            
            if ($stmt->fetch()) {
                return ['success' => false, 'error' => 'Este email já está em uso'];
            }
            
            $fields[] = "email = :email";
            $params[':email'] = $data['email'];
        }
        
        if (!empty($data['senha'])) {
            $fields[] = "senha = :senha";
            $params[':senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        }
        
        if (isset($data['avatar'])) {
            $fields[] = "avatar = :avatar";
            $params[':avatar'] = $data['avatar'];
        }
        
        if (isset($data['ativo'])) {
            $fields[] = "ativo = :ativo";
            $params[':ativo'] = $data['ativo'];
        }
        
        if (empty($fields)) {
            return ['success' => false, 'error' => 'Nenhum dado para atualizar'];
        }
        
        $sql = "UPDATE admins SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        
        // Atualizar sessão se for o próprio admin
        if ($_SESSION['admin_id'] == $id) {
            if (isset($data['nome'])) $_SESSION['admin_nome'] = $data['nome'];
            if (isset($data['email'])) $_SESSION['admin_email'] = $data['email'];
            if (isset($data['avatar'])) $_SESSION['admin_avatar'] = $data['avatar'];
        }
        
        return ['success' => true];
    }
    
    // Deletar admin
    public function delete($id) {
        // Não permitir deletar o próprio usuário
        if ($_SESSION['admin_id'] == $id) {
            return ['success' => false, 'error' => 'Você não pode excluir sua própria conta'];
        }
        
        // Verificar se é o último admin
        $sql = "SELECT COUNT(*) as total FROM admins WHERE ativo = 1";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch();
        
        if ($result['total'] <= 1) {
            return ['success' => false, 'error' => 'Não é possível excluir. Deve existir pelo menos um administrador'];
        }
        
        $sql = "DELETE FROM admins WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return ['success' => true];
    }
    
    // Alterar senha
    public function changePassword($id, $senhaAtual, $novaSenha) {
        $admin = $this->getById($id);
        
        if (!$admin) {
            return ['success' => false, 'error' => 'Administrador não encontrado'];
        }
        
        // Buscar senha atual
        $sql = "SELECT senha FROM admins WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        
        if (!password_verify($senhaAtual, $result['senha'])) {
            return ['success' => false, 'error' => 'Senha atual incorreta'];
        }
        
        $sql = "UPDATE admins SET senha = :senha WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':senha' => password_hash($novaSenha, PASSWORD_DEFAULT)
        ]);
        
        return ['success' => true];
    }
}
?>

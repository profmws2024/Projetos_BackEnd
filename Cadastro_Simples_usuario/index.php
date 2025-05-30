<?php
// config.php - Configuração do banco de dados
$host = 'localhost';
$dbname = 'crud_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Processar ações CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, telefone) VALUES (?, ?, ?)");
                $stmt->execute([$_POST['nome'], $_POST['email'], $_POST['telefone']]);
                $message = "Usuário criado com sucesso!";
                break;
                
            case 'update':
                $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, telefone = ? WHERE id = ?");
                $stmt->execute([$_POST['nome'], $_POST['email'], $_POST['telefone'], $_POST['id']]);
                $message = "Usuário atualizado com sucesso!";
                break;
                
            case 'delete':
                $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $message = "Usuário excluído com sucesso!";
                break;
        }
    }
}

// Buscar usuário para edição
$editUser = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editUser = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Listar todos os usuários
$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD PHP - Gerenciamento de Usuários</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        h1, h2 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        
        button:hover {
            background-color: #0056b3;
        }
        
        button.delete {
            background-color: #dc3545;
        }
        
        button.delete:hover {
            background-color: #c82333;
        }
        
        button.edit {
            background-color: #28a745;
        }
        
        button.edit:hover {
            background-color: #218838;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #007bff;
            color: white;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        
        .actions {
            white-space: nowrap;
        }
        
        .cancel-btn {
            background-color: #6c757d;
            text-decoration: none;
            display: inline-block;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
        }
        
        .cancel-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Gerenciamento de Usuários - CRUD PHP</h1>
        
        <?php if (isset($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <h2><?php echo $editUser ? 'Editar Usuário' : 'Adicionar Novo Usuário'; ?></h2>
        
        <form method="POST" action="">
            <input type="hidden" name="action" value="<?php echo $editUser ? 'update' : 'create'; ?>">
            <?php if ($editUser): ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($editUser['id']); ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" 
                       value="<?php echo $editUser ? htmlspecialchars($editUser['nome']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo $editUser ? htmlspecialchars($editUser['email']) : ''; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" 
                       value="<?php echo $editUser ? htmlspecialchars($editUser['telefone']) : ''; ?>" 
                       required>
            </div>
            
            <button type="submit">
                <?php echo $editUser ? '✏️ Atualizar' : '➕ Adicionar'; ?>
            </button>
            
            <?php if ($editUser): ?>
                <a href="?" class="cancel-btn">❌ Cancelar</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="container">
        <h2>📋 Lista de Usuários</h2>
        
        <?php if (empty($users)): ?>
            <p>Nenhum usuário cadastrado ainda.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['nome']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['telefone']); ?></td>
                            <td class="actions">
                                <a href="?edit=<?php echo $user['id']; ?>">
                                    <button type="button" class="edit">✏️ Editar</button>
                                </a>
                                
                                <form method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="delete">🗑️ Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
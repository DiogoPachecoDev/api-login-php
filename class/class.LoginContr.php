<?php

    require_once('../inc/inc.config.php');

    class LoginContr
    {
        private $pdo;

        public function __construct() {
            $this->pdo = getDatabaseConnection();
        }

        public function saveUserHash($api_hash, $login) {

            $query = 'UPDATE user 
                    SET api_hash = :api_hash, hash_create_date = :hash_create_date
                    WHERE login = :login';

            $stmt = $this->pdo->prepare($query);

            $stmt->execute([
                ':api_hash' => $api_hash,
                ':hash_create_date' => date("Y-m-d H:i:s"),
                ':login' => $login
            ]);

        }

        public function login($data) {

            $query = 'SELECT name, login, email, api_hash
                    FROM user
                    WHERE status = "a"
                    AND login = :login
                    AND pass = :pass
                    ORDER BY name';

            $stmt = $this->pdo->prepare($query);

            $stmt->execute([
                ':login' => $data['user'],
                ':pass' => md5($data['pass'])
            ]);

            return $stmt->fetch(PDO::FETCH_ASSOC);

        }

        public function validateHash($api_hash) {

            $query = 'SELECT id, name, login, email, api_hash, hash_create_date
                    FROM usuarios
                    WHERE status = "a"
                    AND api_hash = :api_hash
                    ORDER BY name';

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':api_hash' => $api_hash]);
            $vet = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($vet) {
                $date = date('Y-m-d H:i:s', strtotime("+1 hours", strtotime($vet['hash_create_date'])));

                if (strtotime(date('Y-m-d H:i:s')) > strtotime($date)) {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => 'hash expired']);
                    die;
                } else {
                    $new_date = date('Y-m-d H:i:s');

                    $updateQuery = 'UPDATE user
                                    SET hash_create_date = :hash_create_date
                                    WHERE id = :id';

                    $updateStmt = $this->pdo->prepare($updateQuery);

                    $updateStmt->execute([
                        ':hash_create_date' => $new_date,
                        ':id' => $vet['id']
                    ]);
                }
            } else {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'hash not found']);
                die;
            }

            return $vet;
        }
        
    }

?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require_once APPPATH . 'libraries/JWT.php';

class Humhub_sso {
    protected $ci;
    private $user;

    public function __construct() {
        $this->ci = &get_instance();
        $this->ci->load->library('session');
        $this->user = $this->ci->session->userdata('user');
    
    }

    private function handleCurlError($ch, $url) {
        
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        log_message('error', "cURL Error ($errno): $error - URL: $url");
        return null;
    }

   
    /**
     * Envoie une requête HTTP à l'API HumHub
     * @param string $method GET|POST|PUT|DELETE
     * @param string $url URL de l'endpoint
     * @param array|null $data Payload JSON
     * @return array|null Réponse décodée ou null en cas d'erreur
     */
    private function httpRequest($method, $url, $data = null) {
        $ch = curl_init($url);
        $headers = [
            'Authorization: Bearer ' . HUMHUB_API_TOKEN,
            'Content-Type: application/json'
        ];
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => strtoupper($method),
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_FAILONERROR    => false
        ]);
        if (in_array(strtoupper($method), ['POST','PUT']) && $data) {
            $payload = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }
        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            log_message('error', 'cURL error: '. curl_error($ch));
            curl_close($ch);
            return null;
        }
        curl_close($ch);
        if ($httpCode >= 400) {
            log_message('error', "API $method $url returned status $httpCode: $result");
            return null;
        }
        return json_decode($result, true);
    }

    // provisionAndGetIframeUrl()
    // Objectif principal :
    
    // Provisionner l’utilisateur dans HumHub (création/mise à jour du compte).
    
    // Générer un lien sécurisé (via JWT) pour accéder à HumHub dans une iframe.
    
    /**
     * Provisionne l'utilisateur dans HumHub via l'API ou crée s'il n'existe pas,
     * puis retourne l'URL SSO JWT pour intégrer en iframe.
     * @return string URL /s/jwt?token=...
     */
    public function provisionAndGetIframeUrl()
    {
        $this->user = $this->ci->session->userdata('user');
        if (!$this->user || empty($this->user->email)) {
            show_error('Utilisateur non connecté pour SSO HumHub.');
        }

        // Le payload envoyé inclut un mot de passe haché (cbc78ad4456f76b62f79d4836a9706079203870f), qui semble être un haché SHA1.
        // HumHub, lors de la création d'un utilisateur via l'API, attend généralement un mot de passe en clair qu'il hache lui-même avec bcrypt.
        // Cela signifie que le mot de passe haché envoyé n'est pas compatible avec le système d'authentification de HumHub. 
        // Si l'utilisateur tente de se connecter manuellement (sans SSO), cela risque d'échouer.
      
           // Extraction explicite
           $email = $this->user->email;
           $name  = $this->user->name;
           $password  = $this->user->password;
        //    echo $password;die;
    
        try {
            // 1. Rechercher l'utilisateur HumHub
            // $existing = $this->httpRequest('GET', HUMHUB_BASE_URL . '/api/v1/user/get-by-email?email=' . urlencode($email));
            $existing = $this->getUserByEmail($email);

            if ($existing && isset($existing['id']) && isset($existing['account']['email']) && $existing['account']['email'] === $email) {
                $humhubId = $existing['id'];
                log_message('debug', 'HumHub user exists  with ID: ' . $humhubId);
            } else {
                 // Récupérez le mot de passe en clair depuis la session
                // $password = $this->ci->session->userdata('password');

                
                log_message('debug', 'Mot de passe récupéré depuis la session : ' . ($password ? 'Présent' : 'Absent'));

                if (empty($password)) {
                    show_error('Mot de passe SSO manquant. Veuillez vous reconnecter.');
                }
                // 2. Créer un nouvel utilisateur via l'API
                $newUser = [
                    'account' => [
                        'email' => $email,
                        'username' => $this->sanitizeUsername($name),
                        'newPassword' => $password,
                        'newPasswordConfirm' => $password,
                       // 'auth_key' => $authKey
                    ],
                    'profile' => [
                        'language' => 'fr'
                    ]
                ];
                
                log_message('debug', 'Payload envoyé à HumHub: ' . json_encode($newUser));
                $created = $this->createUser($newUser);
                log_message('debug', "SSO HumHub – utilisateur Wayo : email={$email}, name={$name}");
    
                if (!$created || empty($created['id'])) {
                    throw new Exception('Échec de la création de l\'utilisateur HumHub.');
                }
    
                $humhubId = $created['id'];
                log_message('debug', 'Created new HumHub user with ID: ' . $humhubId);
                
                // 3. Hacher et insérer le mot de passe dans user_password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $this->insertUserPassword($humhubId, $hashedPassword);
                //Sécurité : on supprime le mot de passe clair de la session
                 $this->ci->session->unset_userdata('password');
            }
    
            // 3. Génération du JWT
            $payload = [
                'sub'            => $humhubId, // Sujet du jeton : identifiant unique de l'utilisateur
                'user_id'        => $humhubId, // (Optionnel) Redondance de l'ID utilisateur pour compatibilité
                'email'          => $email,  // Adresse email de l'utilisateur
                'iat'            => time(), // Issued At Time : moment où le token est généré
                'exp'            => time() + 3600, // Expiration : 1 heure après la génération
                'authMode'       => 'external', // Indique à HumHub que c'est une authentification externe
                'disableSession' => false,   // Permet à HumHub de créer une session utilisateur
              //  'returnUrl'      => $returnPath,
            ];
    
            $token = JWT::encode($payload, HUMHUB_JWT_SECRET, 'HS256');//Génère  un nouveau token JWT signé avec la clé secrète et l'algorithme HS256
          
           // return HUMHUB_BASE_URL . '/user/auth/login?jwt=' . urlencode($token) . '&returnUrl=' . urlencode($returnPath);

            // Génère une URL de connexion automatique à HumHub via JWT (sans mot de passe)
           //  return HUMHUB_BASE_URL . '/user/auth/external' . '?authclient=jwt'. '&jwt=' . urlencode($token). '&returnUrl=' . urlencode($returnPath);
         return HUMHUB_BASE_URL . '/user/auth/external' . '?authclient=jwt'. '&jwt=' . urlencode($token);
    
        } catch (Exception $e) {
            log_message('error', 'SSO HumHub error: ' . $e->getMessage());
            return null; // Ensure a value is returned in case of an exception
        }
    }
    public function getUserByEmail($email) {
        return $this->httpRequest('GET', HUMHUB_BASE_URL . '/api/v1/user/get-by-email?email=' . urlencode($email));
    }
    public function getSpace($spaceId)
    {
        return $this->httpRequest('GET', HUMHUB_BASE_URL . '/api/v1/space/' . intval($spaceId));
    }
    public function createSpace(array $data)
    {
        return $this->httpRequest('POST', HUMHUB_BASE_URL . '/api/v1/space', $data);
    }
    public function updateSpace($SpaceId,array $data)
    {
        return $this->httpRequest('PUT', HUMHUB_BASE_URL . '/api/v1/space/' . intval($SpaceId),$data);
    }
    public function deleteSpace($SpaceId)
    {
        return $this->httpRequest('DELETE', HUMHUB_BASE_URL . '/api/v1/space/' . intval($SpaceId));
    }
    public function addUserSpace($SpaceId,$UserId)
    {
        return $this->httpRequest('POST', HUMHUB_BASE_URL . "/api/v1/space/{$SpaceId}/membership/{$UserId}");
    }
    /**
     * Insère le mot de passe haché dans la table user_password
     * @param int $userId ID de l'utilisateur dans HumHub
     * @param string $hashedPassword Mot de passe haché
     * @throws Exception Si l'insertion échoue
     */
    private function insertUserPassword($userId, $hashedPassword)
    {
        $db = $this->ci->load->database('humhub', TRUE);

        // Vérifier que l'utilisateur existe dans la table users
        $db->where('id', $userId);
        $userExists = $db->get('user')->num_rows() > 0;

        if (!$userExists) {
            throw new Exception('Utilisateur ID ' . $userId . ' non trouvé dans la table user.');
        }

        $data = [
            'user_id'    => $userId,
            'algorithm'  => 'bcrypt',
            'password'   => $hashedPassword,
            'salt'       => NULL,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $result = $db->insert('user_password', $data);

        if (!$result) {
            throw new Exception('Échec de l\'insertion du mot de passe dans user_password.');
        }

        log_message('debug', 'Mot de passe inséré pour l\'utilisateur ID: ' . $userId);
    }
 /**
     * Création d'un utilisateur HumHub via API
     * @param array $data ['email','username','language',...]
     * @return array|null Réponse API ou null
     */
    public function createUser(array $data) {
        return $this->httpRequest('POST', HUMHUB_BASE_URL . '/api/v1/user', $data);
    }
    
    /**
     * Mise à jour d'un utilisateur HumHub via API
     * @param int   $id   ID HumHub
     * @param array $data Champs à mettre à jour
     * @return array|null Réponse API ou null
     */
    public function updateUser($id, array $data) {
        return $this->httpRequest('PUT', HUMHUB_BASE_URL . '/api/v1/user/' . intval($id), $data);
    }

    /**
     * Suppression d'un utilisateur HumHub via API
     * @param int $id ID HumHub
     * @return bool Succès ou échec
     */
    public function deleteUser($id) {
        $res = $this->httpRequest('DELETE', HUMHUB_BASE_URL . '/api/v1/user/' . intval($id));
        return $res !== null;
    }

    /**
     * Sanitize a string to be a valid username
     */
    private function sanitizeUsername($str) {
        $u = strtolower(preg_replace('/[^a-z0-9]/i', '', $str));
        return $u ?: 'user' . rand(1000,9999);
    }



       /* === Méthode DB direct (recommandée si config database.php) ===
       $dbHumhub = $this->ci->load->database('humhub', TRUE);
       $wUser = $this->user;
       $exists = $dbHumhub->where('email', $wUser->email)
                            ->get('user')
                            ->row();
       if (! $exists) {
           // Insère directement l'utilisateur dans la table `user` de HumHub
           $now = date('Y-m-d H:i:s');
           $guid = bin2hex(random_bytes(16)); // UUID-like, 32 hex, HumHub n’en génère pas automatiquement
           $authKey = bin2hex(random_bytes(20)); 
     // Vérification de la propriété "username"
        $name = isset($wUser->name) ? $wUser->name : 'utilisateur' . rand(1000, 9999);

        // Insertion sans mot de passe
        $dbHumhub->insert('user', [
            'guid'               => $guid,
            'status'             => 1,
            'email'              => $wUser->email,
            'username'           => $name,
            //'password'           => password_hash(...), ❌ À retirer
            'auth_mode'          => 'external',
            'language'           => 'fr',
            'visibility'         => 1,
            'created_at'         => $now,
            'created_by'         => 0,
            'updated_at'         => $now,
            'updated_by'         => 0,
            'auth_key'    => $authKey

        ]);

           $humhubId = $dbHumhub->insert_id();
             // Log the creation for debugging
        log_message('debug', "Created new HumHub user with ID: {$humhubId}");
       } else {
           $humhubId = $exists->id;
       }

       // === Génération du JWT pour SSO ===
       $payload = [
           'sub' => $humhubId,
           'user_id' => $humhubId,
           'email' => $wUser->email,
           'iat' => time(),
           'exp' => time() + 3600,
           'authMode' => 'external',
           'disableSession' => true
       ];
       try {
           $jwt = JWT::encode($payload, HUMHUB_JWT_SECRET, 'HS256');
       } catch (Exception $e) {
           log_message('error', 'JWT Generation Error: ' . $e->getMessage());
           show_error('Erreur d\'authentification');
       }

      return HUMHUB_BASE_URL . '/s/jwt?token=' . urlencode($jwt);
      // return HUMHUB_BASE_URL . '/s/jwt?token=' . urlencode($jwt) . '&targetUrl=' . urlencode('/admin/central');
   }

    //    syncAfterAuth()
    // Objectif principal :

    // Déclencher le provisionnement uniquement après une authentification (login ou inscription).

    // Préparer l’affichage de l’iframe HumHub immédiatement après l’authentification.
    public function syncAfterAuth()
    {
        // On ne déclenche que sur les contrôleurs d'authentification
        $controller = $this->ci->router->fetch_class();
        $method     = $this->ci->router->fetch_method();
        if (! in_array($controller, ['auth', 'user']) || ! in_array($method, ['login', 'register'])) {
            return;
        }

        // Récupère l'utilisateur Wayo
        $wUser = $this->ci->session->userdata('user');
        if (empty($wUser) || ! filter_var($wUser->email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        // Provisionne dans HumHub et récupère l'URL SSO
        $iframeUrl = $this->provisionAndGetIframeUrl();

    //     // Duplique aussi en base HumHub
    //     $dbHumhub = $this->ci->load->database('humhub', TRUE);
    //     $exists = $dbHumhub->where('email', $wUser->email)->get('user')->row();
    //     if (! $exists) {
    //        // Insère directement l'utilisateur dans la table `user` de HumHub
    //        $now = date('Y-m-d H:i:s');
    //        $guid = bin2hex(random_bytes(16)); // UUID-like, 32 hex, HumHub n’en génère pas automatiquement
    //        $authKey = bin2hex(random_bytes(20)); 
    //  // Vérification de la propriété "username"
    //     $name = isset($wUser->name) ? $wUser->name : 'utilisateur' . rand(1000, 9999);

    //     // Insertion sans mot de passe
    //     $dbHumhub->insert('user', [
    //         'guid'               => $guid,
    //         'status'             => 1,
    //         'email'              => $wUser->email,
    //         'username'           => $name,
    //         //'password'           => password_hash(...), ❌ À retirer
    //         'auth_mode'          => 'external',
    //         'language'           => 'fr',
    //         'visibility'         => 1,
    //         'created_at'         => $now,
    //         'created_by'         => 0,
    //         'updated_at'         => $now,
    //         'updated_by'         => 0,
    //         'auth_key'          => $authKey
    //     ]);

        // On stocke l'URL SSO en flashdata pour le contrôleur
        $this->ci->session->set_flashdata('humhub_iframe', $iframeUrl);
    }*/
  
}
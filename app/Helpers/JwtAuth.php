<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use App\Models\UserAnswer;
use stdClass;
use Illuminate\Support\Facades\Hash;

class JwtAuth
{
    public $key;
    protected $user;

    public function __construct()
    {
        $this->key = "edwinmogollon";
    }

    /**
     * Generates JWT token for user authentication.
     *
     * @param string $email User's email
     * @param string $password User's password
     * @param bool|null $getToken Whether to return token or user data
     * @return array Response data
     */
    public function signup(string $email, string $password, ?bool $getToken = null): ?array
    {
        $user = User::where([
            'email'    => $email
        ])->first();
 
        // Verify credentials
        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }
        $this->user = $user;

        // Generate token
        $token = [
                'sub'   => $user->id,
                'email' => $user->email,
                'type'  => 'login',
                'iat'   => time(),
                'exp'   => time() + (7 * 24 * 60 * 60),
        ];
        return $this->tokenGenerator($token, 'login', $getToken);
    }

    public function signupAnswer(string $email,string $survey, ?bool $getToken = null):?array
    {
        $user = UserAnswer::where([
            'email'    => $email,
        ])->first();

        // Verify credentials
        if (empty($user)) {
            return null;
        }

        $this->user = $user;

        // Generate token
        $token = [
                'sub'   => $user->id,
                'email' => $user->email,
                'survey' => $survey,
                'type'  => 'Answer',
                'iat'   => time(),
                'exp'   => time() + (7 * 24 * 60 * 60),
        ];

        return $this->tokenGenerator($token, 'Answer', $getToken);
    }

    /**
     * Generates JWT token.
     *
     * @param array $token Token data
     * @param string $type Type of operation (login, etc.)
     * @param bool|null $getToken Whether to return token or user data
     * @return array Response data
     */
    private function tokenGenerator(array $token, string $type, ?bool $getToken = null): array
    {
        $jwt = JWT::encode($token, $this->key, 'HS256');
        $decode = JWT::decode($jwt, new Key($this->key, 'HS256'));
        if (is_null($getToken)) {
            $data = [
                'token'   => $jwt,
                'user'   => $this->user,
                'type'   => $type,
            ];
        }
        return $data ?? [];
    }

    public function checkTokenAdmin(string $jwt, bool $getIdentity = false)
    {
      $admin = $this->checkToken($jwt,true);
      if($admin->type == 'login'){
        return true;
      }
        return false;
    }

    /**
     * Checks if the JWT token is valid.
     *
     * @param string $jwt JWT token
     * @param bool $getIdentity Whether to return user data
     * @return mixed User data or authentication status
     */
    public function checkToken(string $jwt, bool $getIdentity = false)
    {
        try {
            $jwt = $this->cleanJwtToken($jwt);
            $auth = false;      
            $decode = JWT::decode($jwt, new Key($this->key, 'HS256'));
       
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if (!empty($decode) && is_object($decode) && isset($decode->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }

        if ($getIdentity) {
            return $decode ?? null;
        }
        return $auth;
    }

    /**
     * Decodes JWT token.
     *
     * @param string $token JWT token
     * @return mixed Decoded token data
     */
    public function jwtDecode(string $token)
    {
        return JWT::decode($token, new Key($this->key, 'HS256'));
    }


    private function cleanJwtToken(string $token): string{
        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }
        $cleaned = trim($token);
        $cleaned = preg_replace('/[^A-Za-z0-9\._\-]/', '', $cleaned);
        return $cleaned;
    }

}

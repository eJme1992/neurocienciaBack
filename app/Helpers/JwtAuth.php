<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use App\Models\UserAnswer;
use stdClass;

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
            'email'    => $email,
            'password' => $password
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
                'iat'   => time(),
                'exp'   => time() + (7 * 24 * 60 * 60),
        ];

        return $this->tokenGenerator($token, 'login', $getToken);
    }

    public function signupAnswer(string $email, ?bool $getToken = null):?array
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
            $jwt = str_replace('"', '', $jwt);
            $auth = false;
            $algorithms = new stdClass();
            $algorithms->algos = array('HS256');
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
        $algorithms = new stdClass();
        $algorithms->algos = array('HS256');
        return JWT::decode($token, new Key($this->key, 'HS256'));
    }
}

<?php
namespace Rivulet\Auth;

use Exception;
use Rivulet\Rivulet;

class Authentication
{
    protected static $app;

    /**
     * Initialize authentication with application instance
     */
    public static function init(Rivulet $app)
    {
        self::$app = $app;
    }

    /**
     * Verify JWT token and return authenticated user
     *
     * @param string $token JWT token to verify
     * @return mixed|null User object if valid, null otherwise
     * @throws Exception If user model is configured but missing
     */
    public static function verifyToken($token)
    {
        [$header, $payload, $signature] = explode('.', $token);
        $expectedSignature              = base64_encode(hash_hmac('sha256', $header . '.' . $payload, env('APP_KEY'), true));

        // Signature verification
        if ($signature !== $expectedSignature) {
            return null;
        }

        // Expiration check
        $payloadData = json_decode(base64_decode($payload), true);
        if ($payloadData['exp'] < time()) {
            return null;
        }

        // Database verification (if configured)
        $userModel = config('auth.user_model');
        if ($userModel && config('auth.store_token', true)) {
            if (! class_exists($userModel)) {
                throw new Exception("User model {$userModel} does not exist");
            }
            $user = $userModel::where('id', $payloadData['id'])
                ->where('authtoken', $token)
                ->first();

            return $user ?: null;
        }

        // Stateless fallback
        return (object) $payloadData;
    }

    /**
     * Generate new JWT token for user
     *
     * @param int|string $userId User identifier
     * @return string Generated JWT token
     * @throws Exception If user model is configured but missing
     */
    public static function generateToken($userId)
    {
        $header  = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode([
            'id'  => $userId,
            'exp' => time() + config('auth.token_expiry', 3600),
        ]));
        $signature = base64_encode(hash_hmac('sha256', $header . '.' . $payload, env('APP_KEY'), true));

        $token = $header . '.' . $payload . '.' . $signature;

        // Store token if configured
        $userModel = config('auth.user_model');
        if ($userModel && config('auth.store_token', true)) {
            if (! class_exists($userModel)) {
                throw new Exception("User model {$userModel} does not exist");
            }
            $user = $userModel::find($userId);
            if ($user) {
                $user->authtoken = $token;
                $user->save();
            }
        }

        return $token;
    }
}

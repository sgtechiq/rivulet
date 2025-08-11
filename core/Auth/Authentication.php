<?php

namespace Rivulet\Auth;

use Rivulet\Rivulet;
use Exception;

class Authentication {
    protected static $app;

    public static function init(Rivulet $app) {
        self::$app = $app;
    }

    public static function generateToken($userId) {
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode([
            'id' => $userId,
            'exp' => time() + config('auth.token_expiry', 3600),
        ]));
        $signature = base64_encode(hash_hmac('sha256', $header . '.' . $payload, env('APP_KEY'), true));

        $token = $header . '.' . $payload . '.' . $signature;

        $userModel = config('auth.user_model');
        if ($userModel && config('auth.store_token', true)) {
            if (!class_exists($userModel)) {
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

    public static function verifyToken($token) {
        [$header, $payload, $signature] = explode('.', $token);
        $expectedSignature = base64_encode(hash_hmac('sha256', $header . '.' . $payload, env('APP_KEY'), true));

        if ($signature !== $expectedSignature) {
            return null;
        }

        $payloadData = json_decode(base64_decode($payload), true);
        if ($payloadData['exp'] < time()) {
            return null;
        }

        $userModel = config('auth.user_model');
        if ($userModel && config('auth.store_token', true)) {
            if (!class_exists($userModel)) {
                throw new Exception("User model {$userModel} does not exist");
            }
            $user = $userModel::where('id', $payloadData['id'])->where('authtoken', $token)->first();
            if (!$user) {
                return null;
            }
            return $user;
        }

        // Stateless: return payload as "user" object if no model
        return (object) $payloadData;
    }
}
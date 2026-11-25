<?php

namespace App\Libraries;

use App\Models\TbUsuario;
use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\ResponseInterface;

final class AcessoLembrado
{
    private const COOKIE = 'estoque_acesso_lembrado';
    private const DURACAO = 30 * 24 * 60 * 60;

    public static function iniciarSessao(array $usuario): void
    {
        session()->regenerate(true);
        session()->set('usuario_logado', array_intersect_key($usuario, array_flip([
            'id_usuario', 'nome', 'email', 'cpf', 'cargo', 'ativo', 'avatar', 'permissoes',
        ])));
    }

    public static function lembrar(IncomingRequest $request, ResponseInterface $response, array $usuario): void
    {
        if (!self::permiteCookie($request)) {
            return;
        }

        $token = bin2hex(random_bytes(32));
        $agora = time();
        self::tokens()->where('expira_em <=', $agora)->delete();
        if (self::tokens()->insert([
            'token_hash' => hash('sha256', $token),
            'id_usuario' => $usuario['id_usuario'],
            'senha_hash' => hash('sha256', $usuario['senha']),
            'expira_em' => $agora + self::DURACAO,
        ])) {
            $response->setCookie(self::COOKIE, $token, self::DURACAO, '', '/', '', $request->isSecure(), true, 'Lax');
        }
    }

    public static function restaurar(IncomingRequest $request): ?array
    {
        $hash = self::hashDoCookie($request);
        if ($hash === null || !self::permiteCookie($request)) {
            return null;
        }

        $acesso = self::tokens()->where('token_hash', $hash)
            ->where('expira_em >', time())->get()->getRowArray();
        if (!$acesso) {
            return null;
        }

        $usuario = (new TbUsuario())->find($acesso['id_usuario']);
        if (!$usuario || !$usuario['ativo'] || !hash_equals($acesso['senha_hash'], hash('sha256', $usuario['senha']))) {
            self::tokens()->where('token_hash', $hash)->delete();
            return null;
        }

        self::iniciarSessao($usuario);
        return $usuario;
    }

    public static function revogar(IncomingRequest $request, ResponseInterface $response): void
    {
        $hash = self::hashDoCookie($request);
        if ($hash !== null) {
            self::tokens()->where('token_hash', $hash)->delete();
        }
        if ($request->getCookie(self::COOKIE) !== null) {
            $response->deleteCookie(self::COOKIE);
        }
    }

    private static function hashDoCookie(IncomingRequest $request): ?string
    {
        $token = $request->getCookie(self::COOKIE);
        return is_string($token) && preg_match('/\A[a-f0-9]{64}\z/', $token)
            ? hash('sha256', $token)
            : null;
    }

    private static function permiteCookie(IncomingRequest $request): bool
    {
        $host = strtolower($request->getUri()->getHost());
        return $request->isSecure() || $host === 'localhost' || str_ends_with($host, '.localhost');
    }

    private static function tokens(): BaseBuilder
    {
        return db_connect()->table('tb_acesso_lembrado');
    }
}

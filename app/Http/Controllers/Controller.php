<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 *  @OA\Server(url="http://localhost/api"),
 * @OA\Info(title="API Rest Truckpag", version="0.0.1")
 * @OA\Get(
 *     path="/",
 *     summary="Status da API",
 *     tags={"API"},
 *     @OA\Response(
 *         response="200",
 *         description="OK"
 *     )
 * )
 * @OA\Get(
 *     path="/products",
 *     summary="Listar produtos",
 *     tags={"Products"},
 *     @OA\Response(
 *         response="200",
 *         description="Lista de produtos"
 *     )
 * )
 * @OA\Get(
 *     path="/products/{code}",
 *     summary="Obter detalhes de um produto",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="code",
 *         in="path",
 *         required=true,
 *         description="Código do produto",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Detalhes do produto"
 *     )
 * )
 * @OA\Post(
 *     path="/products",
 *     summary="Criar um novo produto",
 *     tags={"Products"},
 *     @OA\Response(
 *         response="201",
 *         description="Produto criado com sucesso"
 *     )
 * )
 * @OA\Put(
 *     path="/products/{code}",
 *     summary="Atualizar um produto",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="code",
 *         in="path",
 *         required=true,
 *         description="Código do produto",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Produto atualizado com sucesso"
 *     )
 * )
 * @OA\Delete(
 *     path="/products/{code}",
 *     summary="Excluir um produto",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="code",
 *         in="path",
 *         required=true,
 *         description="Código do produto",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response="204",
 *         description="Produto excluído com sucesso"
 *     )
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

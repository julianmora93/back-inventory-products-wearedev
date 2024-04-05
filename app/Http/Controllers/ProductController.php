<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Responses\ApiResponse;
use App\Http\Requests\ProductRequest;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="Endpoints para productos"
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Listar todos los productos",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de productos"
     *     )
     * )
     */
    public function index()
    {
        try {
            $products = Product::all();
            return ApiResponse::success($products, 'Listado de productos.');
        } catch (\Exception $e) {
            return ApiResponse::error('Ha ocurrido un error al listar productos.', 500);
        }

    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Crear un nuevo producto",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del nuevo producto",
     *         @OA\JsonContent(
     *             required={"code", "description", "quantity"},
     *             @OA\Property(property="code", type="string", example="0001"),
     *             @OA\Property(property="description", type="string", example="Descripción del producto"),
     *             @OA\Property(property="quantity", type="number", format="float", example=10),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente",
     *     )
     * )
     */
    public function store(ProductRequest $request)
    {
        try {
            $product = Product::create($request->validated());
            return ApiResponse::success($product, 'Producto creado exitosamente.');
        } catch (\Exception $e) {
            return ApiResponse::error('Ha ocurrido un error al crear el producto.', 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Obtener un producto por su ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto encontrado",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $product = Product::find($id);
            if(is_null($product)) {
                return ApiResponse::error('Producto no encontrado.', 404);
            }
            return ApiResponse::success($product, 'Producto encontrado.');
        } catch (\Exception $e) {
            return ApiResponse::error('Ha ocurrido un error al obtener el producto.', 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Actualizar un producto por su ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos actualizados del producto",
     *         @OA\JsonContent(
     *             required={"code", "description", "quantity"},
     *             @OA\Property(property="code", type="string", example="0003"),
     *             @OA\Property(property="description", type="string", example="Nueva descripción del producto"),
     *             @OA\Property(property="quantity", type="number", format="float", example=5),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function update(ProductRequest $request, $id)
    {
        try {
            $product = Product::find($id);
            if(is_null($product)) {
                return ApiResponse::error('Producto no encontrado.', 404);
            }
            $product->update($request->validated());
            return ApiResponse::success($product, 'Producto actualizado exitosamente.');
        } catch (\Exception $e) {
            return ApiResponse::error('Ha ocurrido un error al actualizar el producto.', 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Eliminar un producto por su ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Producto eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            if(is_null($product)) {
                return ApiResponse::error('Producto no encontrado.', 404);
            }
            $product->delete();
            return ApiResponse::success($product, 'Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            return ApiResponse::error('Ha ocurrido un error al eliminar el producto.', 500);
        }
    }
}

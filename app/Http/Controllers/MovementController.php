<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Movement;
use App\Models\StockStatus;
use App\Models\ProductMovement;
use Illuminate\Support\Facades\DB;
use App\Http\Responses\ApiResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\MovementRequest;

/**
 * @OA\Tag(
 *     name="Movements",
 *     description="Endpoints para movimientos"
 * )
 */
class MovementController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/movements",
     *     tags={"Movements"},
     *     summary="Listar todos los movimientos",
     *     @OA\Response(
     *         response=200,
     *         description="Listado de movimientos"
     *     )
     * )
     */
    public function index()
    {
        try {
            $movement = ProductMovement::join('movements', 'movements.id', '=', 'products_movements.movement_id')
            ->join('products as products', 'products.id', '=', 'products_movements.product_id')
            ->join('stock_status as stock_status', 'stock_status.id', '=', 'movements.movement_status_id')
            ->select(
                DB::raw('CONCAT(products.code, \' - \', products.description) as product'),
                'stock_status.description as status',
                'movements.date_movement',
                'stock_status.in_out',
                DB::raw('TO_CHAR(movements.created_at, \'YYYY-MM-DD HH24:MI:SS\') as formatted_created_at')
            )
            ->get();
            return ApiResponse::success($movement, 'Listado de movimientos.');
        } catch (\Exception $e) {
            return ApiResponse::error('Ha ocurrido un error al listar movimientos.', 500);
        }

    }

    /**
     * @OA\Get(
     *     path="/api/movements/lists",
     *     tags={"Movements"},
     *     summary="Listas de movimientos",
     *     @OA\Response(
     *         response=200,
     *         description="Listas para crear el movimiento"
     *     )
     * )
     */
    public function lists()
    {
        try {
            $status = StockStatus::get();

            return ApiResponse::success([
                'status' => $status
            ], 'Listados.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ApiResponse::error('Ha ocurrido un error al listar.', 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/movements",
     *     tags={"Movements"},
     *     summary="Crear un movimiento de inventario",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del movimiento",
     *         @OA\JsonContent(
     *             required={"product_id", "stock_status_id", "quantity"},
     *             @OA\Property(property="product_id", type="integer", description="ID del producto"),
     *             @OA\Property(property="stock_status_id", type="integer", description="ID del estado de stock"),
     *             @OA\Property(property="quantity", type="integer", description="Cantidad del movimiento"),
     *             @OA\Property(property="date_movement", type="string", format="date", description="Fecha del movimiento en formato YYYY-MM-DD")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Movimiento creado exitosamente",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="La cantidad solicitada es mayor a la disponible o falta algún dato requerido en la solicitud",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto o estado de stock no encontrado",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ha ocurrido un error al crear el producto",
     *     )
     * )
     */
    public function store(MovementRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = Product::find($request->product_id);
            if(is_null($product)) {
                return ApiResponse::error('Producto no encontrado.', 404);
            }

            $statusStock = StockStatus::find($request->stock_status_id);
            if(is_null($statusStock)) {
                return ApiResponse::error('Status no encontrado.', 404);
            }

            $movement = Movement::create([
                'date_movement' => $request->date_movement,
                'movement_status_id' => $request->stock_status_id,
            ]);

            ProductMovement::create([
                'product_id' => $request->product_id,
                'movement_id' => $movement->id,
            ]);

            if($statusStock->in_out){
                $product->quantity = $product->quantity + $request->quantity;
            } else {
                if($product->quantity < $request->quantity){
                    DB::rollBack(); // Rollback de la transacción si la cantidad solicitada es mayor a la disponible
                    return ApiResponse::error('La cantidad solicitada es mayor a la disponible.', 400);
                }
                $product->quantity = $product->quantity - $request->quantity;
            }
            $product->save(); // Guardar el producto actualizado

            DB::commit(); // Confirmar la transacción

            return ApiResponse::success($product, 'Movimiento creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback de la transacción en caso de error
            return ApiResponse::error('Ha ocurrido un error al crear el movimiento.', 500);
        }
    }

}

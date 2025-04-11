<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Laravel API Документація",
 *     description="Swagger OpenAPI для лабораторної роботи",
 *     @OA\Contact(
 *         email="igor@example.com"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Subscribers",
 *     description="Управление подписчиками"
 * )
 */
class SubscriberController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/subscribers",
     *     summary="Получить список подписчиков",
     *     tags={"Subscribers"},
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Поле для сортировки",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="direction",
     *         in="query",
     *         description="Направление сортировки",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список успешно получен"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Subscriber::query();

        if ($sort = $request->query('sort')) {
            $direction = $request->query('direction', 'asc');
            $query->orderBy($sort, $direction);
        }

        return response()->json($query->paginate(10));
    }

    /**
     * @OA\Post(
     *     path="/api/subscribers",
     *     summary="Создать подписчика",
     *     tags={"Subscribers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email"},
     *             @OA\Property(property="name", type="string", example="Игорь"),
     *             @OA\Property(property="email", type="string", format="email", example="igor@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Подписчик успешно создан"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:subscribers,email',
        ]);

        $subscriber = Subscriber::create($validated);

        return response()->json($subscriber, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/subscribers/{id}",
     *     summary="Показать подписчика",
     *     tags={"Subscribers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID подписчика",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Подписчик найден"
     *     )
     * )
     */
    public function show(Subscriber $subscriber)
    {
        return response()->json($subscriber);
    }

    /**
     * @OA\Put(
     *     path="/api/subscribers/{id}",
     *     summary="Обновить подписчика",
     *     tags={"Subscribers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID подписчика",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Игорек"),
     *             @OA\Property(property="email", type="string", format="email", example="igorek@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешно обновлён"
     *     )
     * )
     */
    public function update(Request $request, Subscriber $subscriber)
    {
        $validated = $request->validate([
            'name'  => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:subscribers,email,' . $subscriber->id,
        ]);

        $subscriber->update($validated);

        return response()->json($subscriber);
    }

    /**
     * @OA\Delete(
     *     path="/api/subscribers/{id}",
     *     summary="Удалить подписчика",
     *     tags={"Subscribers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID подписчика",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Удалено успешно"
     *     )
     * )
     */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return response()->json(null, 204);
    }
}

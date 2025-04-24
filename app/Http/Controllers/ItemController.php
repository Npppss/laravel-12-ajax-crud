<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ItemController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Item',
            'url_json' => url('/items/get_data'),
            'url' => url('/items')

        ];
        return view('item', $data);
    }
    public function getData()
    {
        return response()->json([
            'status' => true,
            'data' => Item::all(),
            'message' => 'data berhasil ditemukan',
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }
    public function storeData(Request $request){
        $data = $request->only(['item_name', 'status']);

        $validator = Validator::make($data, [
            'item_name' => ['required', 'unique:items', 'min:3', 'max:255'],
            'status' => ['required', 'in:1,0'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 422)->header('Content-Type', 'application/json');
        }
        Item::create($data);

        return response()->json([
            'status' => true,
            'message' => 'data berhasil ditambahkan',
        ], 201)->header('Content-Type', 'application/json');
    }
    public function getDataById($idItem) // function untuk mengambil data berdasarkan id
{
    $item = Item::where('id', $idItem)->first();

    if (!$item) {
        return response()->json([
            'status' => false,
            'message' => 'data tidak ditemukan',
        ], 404)->header('Content-Type', 'application/json');
    }

    return response()->json([
        'status' => true,
        'data' => $item,
        'message' => 'data berhasil ditemukan',
    ], 200)->header('Content-Type', 'application/json');
}
public function updateData(Request $request, $idItem) // function untuk mengubah data
{
    $item = Item::where('id', $idItem)->first();

    if (!$item) {
        return response()->json([
            'status' => false,
            'message' => 'data tidak ditemukan',
        ], 404)->header('Content-Type', 'application/json');
    }

    $data = $request->only(['item_name', 'status']);

    $validator = Validator::make($data, [
        'item_name' => ['required', 'min:3', 'max:255', 'unique:items,item_name,' . $item->id],
        'status' => ['required', 'in:1,0'],
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => $validator->errors(),
        ], 422)->header('Content-Type', 'application/json');
    }

    $item->update($data);

    return response()->json([
        'status' => true,
        'message' => 'data berhasil diubah',
    ], 200)->header('Content-Type', 'application/json');
}
public function destroyData($idItem) // function untuk menghapus data
{
    $item = Item::where('id', $idItem)->first();

    if (!$item) {
        return response()->json([
            'status' => false,
            'message' => 'data tidak ditemukan',
        ], 404)->header('Content-Type', 'application/json');
    }

    $item->delete();

    return response()->json([
        'status' => true,
        'message' => 'data berhasil dihapus',
    ], 200)->header('Content-Type', 'application/json');
}

    
}

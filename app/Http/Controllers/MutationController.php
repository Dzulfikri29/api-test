<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MutationRequest;
use App\Mutation;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class MutationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = Mutation::all();

            $response = [
                'success' => true,
                'data' => $data,
            ];

            return response()->json($response);
        } catch (\Throwable$th) {
            return $th;
        }
    }

    public function store(MutationRequest $request)
    {
        try {
            $mutation = Mutation::create([
                'date' => Carbon::parse($request->date)->format('Y-m-d'),
                'amount' => $request->amount,
                'type' => $request->type,
                'source' => $request->source,
                'note' => $request->note,
            ]);

            $response = [
                "success" => true,
                "data" => $mutation,
            ];

        } catch (\Throwable$e) {
            DB::rollback();
            throw $e;
        }

        return response()->json($response);

    }

    public function show($id)
    {
        try {
            $mutation = Mutation::find($id);

            $response = [
                'success' => true,
                'data' => $mutation,
            ];

            return response()->json($response, 200);
        } catch (\Throwable$th) {
            throw $th;
            $response = [
                'success' => false,
                'message' => "Internal Server Error",
            ];
            return response()->json($response);

        }
    }

    public function update(MutationRequest $request, $id)
    {
        try {
            $mutation = Mutation::find($id);
            $mutation->update([
                'date' => Carbon::parse($request->date)->format('Y-m-d'),
                'amount' => $request->amount,
                'type' => $request->type,
                'source' => $request->source,
                'note' => $request->note,
            ]);

            $response = [
                "success" => true,
                "data" => $mutation,
            ];

        } catch (\Throwable$e) {
            throw $e;
        }

        return response()->json($response);
    }

    public function destroy($id)
    {
        try {
            $mutation = Mutation::find($id)->delete();
            $response = [
                "success" => true,
            ];

        } catch (\Throwable$th) {
            throw $th;
            $response = [
                "success" => false,
            ];
        }

        return response()->json($response);
    }
}

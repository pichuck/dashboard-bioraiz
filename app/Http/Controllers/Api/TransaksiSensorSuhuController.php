<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\TransaksiSensorSuhu;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransaksiSensorSuhuResource;

class TransaksiSensorSuhuController extends Controller
{    
    /**
     * index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all transactions from TransaksiSensorSuhu model, paginated
        $transaksiSensorSuhus = TransaksiSensorSuhu::latest()->paginate(5);

        // Return a collection of transactions as a resource
        return TransaksiSensorSuhuResource::collection($transaksiSensorSuhus);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nilai_temperatur' => 'required|numeric',
            'nilai_humidity' => 'required|integer',
        ]);

        $transaksiSensorSuhu = TransaksiSensorSuhu::create($validatedData);

        return new TransaksiSensorSuhuResource($transaksiSensorSuhu);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaksiSensorSuhu = TransaksiSensorSuhu::findOrFail($id);

        return new TransaksiSensorSuhuResource($transaksiSensorSuhu);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaksiSensorSuhu = TransaksiSensorSuhu::findOrFail($id);
        $transaksiSensorSuhu->delete();

        return response()->json(['message' => 'Deleted successfully'], 204);
    }

    /**
     * Get latest sensor reading
     *
     * @return \Illuminate\Http\Response
     */
    public function latest()
    {
        $latestReading = TransaksiSensorSuhu::latest()->first();
        
        if (!$latestReading) {
            return response()->json(['message' => 'No readings available'], 404);
        }

        return new TransaksiSensorSuhuResource($latestReading);
    }

    /**
     * Get readings by date range
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getByDateRange(Request $request)
    {
        $validatedData = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $readings = TransaksiSensorSuhu::byDateRange(
            $validatedData['start_date'], 
            $validatedData['end_date']
        )->get();

        return TransaksiSensorSuhuResource::collection($readings);
    }
}
<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\TransaksiSensorTanah;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransaksiSensorTanahResource;

class TransaksiSensorTanahController extends Controller
{    
    /**
     * index
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all transactions from TransaksiSensorTanah model, paginated
        $transaksiSensorTanahs = TransaksiSensorTanah::latest()->paginate(5);

        // Return a collection of transactions as a resource
        return TransaksiSensorTanahResource::collection($transaksiSensorTanahs);
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
            'nilai_humidity' => 'required|integer',
        ]);

        $transaksiSensorTanah = TransaksiSensorTanah::create($validatedData);

        return new TransaksiSensorTanahResource($transaksiSensorTanah);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaksiSensorTanah = TransaksiSensorTanah::findOrFail($id);

        return new TransaksiSensorTanahResource($transaksiSensorTanah);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaksiSensorTanah = TransaksiSensorTanah::findOrFail($id);
        $transaksiSensorTanah->delete();

        return response()->json(['message' => 'Deleted successfully'], 204);
    }

    /**
     * Get latest soil sensor reading
     *
     * @return \Illuminate\Http\Response
     */
    public function latest()
    {
        $latestReading = TransaksiSensorTanah::latest()->first();
        
        if (!$latestReading) {
            return response()->json(['message' => 'No readings available'], 404);
        }

        return new TransaksiSensorTanahResource($latestReading);
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

        $readings = TransaksiSensorTanah::byDateRange(
            $validatedData['start_date'], 
            $validatedData['end_date']
        )->get();

        return TransaksiSensorTanahResource::collection($readings);
    }

    /**
     * Get readings by soil humidity level
     * 
     * @param string $level
     * @return \Illuminate\Http\Response
     */
    public function getByHumidityLevel($level)
    {
        $readings = TransaksiSensorTanah::byHumidityLevel($level)->latest()->paginate(5);
        
        return TransaksiSensorTanahResource::collection($readings);
    }
}
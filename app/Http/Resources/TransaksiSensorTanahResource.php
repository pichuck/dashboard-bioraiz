<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiSensorTanahResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nilai_humidity' => $this->nilai_humidity,
            'formatted_humidity' => $this->getFormattedHumidityAttribute(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
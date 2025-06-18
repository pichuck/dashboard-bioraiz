<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiSensorSuhu extends Model
{
    use HasFactory;

    protected $table = 'transaksi_sensor_suhu';

    protected $fillable = [
        'nilai_temperatur',
        'nilai_humidity',
    ];

    protected $casts = [
        'nilai_temperatur' => 'decimal:2',
        'nilai_humidity' => 'integer',
    ];

    // Optional: Accessors untuk format data
    public function getNilaiTemperaturAttribute($value)
    {
        return (float) $value;
    }

    public function getNilaiHumidityAttribute($value)
    {
        return (int) $value;
    }

    // Optional: Method untuk format humidity dengan tanda %
    public function getFormattedHumidityAttribute()
    {
        return $this->nilai_humidity . '%';
    }

    // Optional: Scope untuk rentang waktu
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
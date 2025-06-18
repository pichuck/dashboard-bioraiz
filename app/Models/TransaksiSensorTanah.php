<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiSensorTanah extends Model
{
    use HasFactory;

    protected $table = 'transaksi_sensor_tanah';

    protected $fillable = [
        'nilai_humidity',
    ];

    protected $casts = [
        'nilai_humidity' => 'integer',
    ];

    // Optional: Accessor untuk memastikan nilai integer
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

    // Optional: Scope untuk kondisi tanah berdasarkan kelembaban
    public function scopeByHumidityLevel($query, $level)
    {
        switch ($level) {
            case 'kering':
                return $query->where('nilai_humidity', '<', 30);
            case 'normal':
                return $query->whereBetween('nilai_humidity', [30, 70]);
            case 'basah':
                return $query->where('nilai_humidity', '>', 70);
            default:
                return $query;
        }
    }
}
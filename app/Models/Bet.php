<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;
use Carbon\Carbon;

class Bet extends Model
{
    protected $guarded = ['id'];

    /**
     * Cast para la fecha
     */
    protected $casts = [
        'date_at' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            // Si no se proporciona un custom_code, generar uno
            if (empty($ticket->custom_code)) {
                $ticket->custom_code = self::generateUniqueCustomCodeNumeric($ticket->date_at);
            }
            
            // Generar el código completo (custom_code + fecha)
            $ticket->code = $ticket->custom_code . $ticket->date_at->format('Ymd');
        });
    }

    /**
     * Genera un código personalizado único para la fecha
     */
    public static function generateUniqueCustomCode($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        
        // Longitud del código (puedes cambiar esto)
        $length = 5;
        
        do {
            // Generar código aleatorio (puedes personalizar esto)
            $customCode = Str::upper(Str::random($length));
            
            // Verificar si ya existe para esta fecha
            $exists = self::where('custom_code', $customCode)
                         ->whereDate('date_at', $date->format('Y-m-d'))
                         ->exists();
                         
        } while ($exists);
        
        return $customCode;
    }

    public static function generateUniqueCustomCodeNumeric($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        
        // Ejemplo: código numérico de 4 dígitos
        do {
            $customString = Str::upper(Str::random(1));
            $customCode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            $exists = self::where('custom_code', $customCode)
                        ->whereDate('date_at', $date->format('Y-m-d'))
                        ->exists();
                        
        } while ($exists);
        
        return $customCode;
    }

    /**
     * Scope para buscar por fecha
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date_at', $date);
    }

    // relaciones
    public function betLines()
    {
        return $this->hasMany(BetLine::class);
    }

    public function racing()
    {
        return $this->belongsTo(Racing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;

    public function penerima() {
        return $this->belongsTo(User::class);
    }

    public function pengirim() {
        return $this->belongsTo(User::class);
    }
}
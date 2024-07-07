<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commit extends Model
{
    use HasFactory;

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "commit_id",
        "author_login",
        "author_id",
        'type',
        "created_at"
    ];

    public function repository() {
        return $this->belongsTo(Repository::class);
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
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
        "commited_at"
    ];

    public function getCommitedAtAttribute($value)
    {
        return Carbon::parse($value)->format('ymd');
    }

    public function setCommitedAtAttribute($value)
    {
        $this->attributes['commited_at'] = Carbon::createFromFormat('ymd', $value)->format('Y-m-d');
    }
}

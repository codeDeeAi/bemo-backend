<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AccessTokens;
use App\Models\KabanCard;

class KabanColumn extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'access_token_id',
        'title',
    ];

    /**
     * Get all of the cards for the KabanColumn
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards()
    {
        return $this->hasMany(KabanCard::class, 'kaban_column_id', 'id');
    }

    /**
     * Scope a query to return all table data.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeLoadAll($query)
    {
        $query->where('access_token_id', AccessTokens::where('token', request()->query('access_token') ?? null)->value('id'))
            ->select('id', 'title')
            ->with('cards', function ($query) {
                $query->select('id', 'kaban_column_id', 'title', 'description', 'created_at');
            });
    }
}

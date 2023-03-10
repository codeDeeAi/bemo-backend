<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KabanCard extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kaban_column_id',
        'title',
        'status',
        'description'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'date:Y-m-d',
    ];

    /**
     * Get the column that owns the KabanCard
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function column()
    {
        return $this->belongsTo(KabanColumn::class, 'kaban_column_id', 'id');
    }

    /**
     * Scope a query to return all cards.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeLoadAll($query)
    {
        $kaban_column_id = (request()->query('access_token'))
            ? KabanColumn::where('access_token_id', AccessTokens::where('token', request()->query('access_token'))->value('id'))->value('id')
            : null;

        $query = $query->where('kaban_column_id', $kaban_column_id);

        if (request()->query('date')) {
            $query = $query->whereDate('created_date', '=',  request()->query('date'));
        }
        if (request()->query('status')) {
            $status = (request()->query('status') == '1') ? true : false;
            $query = $query->where('status', $status);
        }

        $query = $query->select('id', 'title', 'description', 'created_at');
    }
}

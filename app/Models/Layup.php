<?php

namespace App\Models;

use Database\Factories\LayupFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseFactory(LayupFactory::class)]
class Layup extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'supplier_id',
        'name',
    ];

    protected $casts = [
        'layer_order' => 'integer',
        'thickness' => 'decimal:2',
        'width' => 'decimal:2',
        'angle' => 'decimal:2',
    ];
    
    public function supplier() : BelongsTo {
        return $this->belongsTo(Supplier::class);
    }


    public function layers(): HasMany
    {
        return $this->hasMany(Layer::class);
    }
}

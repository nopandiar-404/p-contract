<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ContractType extends Model
{
    use HasFactory;

    protected $table = 'contract_types';

    protected $fillable = [
        'slug',
        'name',
        'description',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->slug)) {
                $baseSlug = Str::slug($model->name, '_');
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->where('id', '!=', $model->id ?? 0)->exists()) {
                    $slug = $baseSlug . '_' . $counter++;
                }
                $model->slug = $slug;
            }
        });
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'contract_type', 'slug');
    }
}


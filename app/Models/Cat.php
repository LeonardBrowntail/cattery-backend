<?php

namespace App\Models;

use Database\Factories\CatFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

#[UseFactory(CatFactory::class)]
#[UsePolicy(CatPolicy::class)]
class Cat extends Model
{
  use HasFactory;

  protected $table = 'cats';

  protected $fillable = [
    'father_id',
    'mother_id',
    'birthdate',
    'name',
    'breed',
    'sex',
    'color',
    'price',
    'description',
    'status',
  ];

  protected $casts = [
    'birthdate' => 'date',
    'price' => 'decimal:2'
  ];

  public function father() {
    return $this->belongsTo(Cat::class);
  }

  public function mother() {
    return $this->belongsTo(Cat::class);
  }

  public function childrenAsFather() {
    return $this->hasMany(Cat::class, 'father_id');
  }

  public function childrenAsMother() {
    return $this->hasMany(Cat::class, 'mother_id');
  }

  public function images() {
    return $this->hasMany(CatImage::class);
  }

  public function primaryImage() {
    return $this->hasOne(CatImage::class)->where('is_primary', true);
  }

  // Query scopes

  public function scopeBreed(Builder $query, ?string $breed) {
    return $breed ? $query->where('breed', $breed) : $query;
  }

  public function scopeSex(Builder $query, ?string $sex) {
    return $sex ? $query->where('sex', $sex) : $query;
  }

  public function scopeStatus(Builder $query, ?string $status) {
    return $status ? $query->where('status', $status) : $query;
  }

  public function scopePriceBetween(Builder $query, ?float $minPrice, ?float $maxPrice) {
    if ($minPrice !== null) {
      $query->where('price', '>=', $minPrice);
    }
    if ($maxPrice !== null) {
      $query->where('price', '<=', $maxPrice);
    }

    return $query;
  }

  public function scopeSearch(Builder $query, ?string $keyword) {
    if (!$keyword) {
      return $query;
    }

    $like = '%'.str_replace(['%', '_'], ['\%','\_'],$keyword).'%';

    return $query->where(function(Builder $query) use ($like) {
      $query->where('name', 'like', $like)
        ->orWhere('breed', 'like', $like)
        ->orWhere('color', 'like', $like)
        ->orWhere('description', 'like', $like);
    });
  }
}
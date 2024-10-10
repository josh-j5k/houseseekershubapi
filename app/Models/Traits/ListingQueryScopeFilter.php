<?php

namespace App\Models\Traits;
use Illuminate\Database\Eloquent\Builder;

trait ListingQueryScopeFilter
{
    public static function scopeFilter(Builder $builder, array $filters): Builder
    {
        $builder->latest()->when(filled($filters['location']), function (Builder $builder) use ($filters): void {
            $builder->where('location', 'like', '%' . $filters['location'] . '%');
        })
            // filter if status
            ->when($filters['status'] !== 'any', function (Builder $builder) use ($filters): void {
                $builder->where('property_status', $filters['status']);
            })
            // filter if property type
            ->when(filled($filters['property_type']), function (Builder $builder) use ($filters): void {
                $builder->whereIn('property_type', $filters['property_type']);
            })
            // filter if price
            ->when(count($filters['price']) > 0, function (Builder $builder) use ($filters): void {
                $builder->when($filters['price']['min'] && is_null($filters['price']['max']), function (Builder $builder) use ($filters): void {
                    $builder->where('price', '>=', $filters['price']['min']);
                })->when(is_null($filters['price']['min']) && $filters['price']['max'], function (Builder $builder) use ($filters): void {
                    $builder->where('price', '<=', $filters['price']['max']);
                })->when($filters['price']['min'] && $filters['price']['max'], function (Builder $builder) use ($filters): void {
                    $builder->whereBetween('price', [$filters['price']['min'], $filters['price']['max']]);
                });
            });
        return $builder;
    }
}
<?php

use App\Models\Product;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::get('/orders', function () {
    DB::enableQueryLog();
    try {
        validator(request()->all(), [
            'products' =>  ['required', 'array'],
            'products.*.id' =>  ['required']
        ])
            ->after(function (Validator $validator) {
                //custom validation logic  
                $requestProductIds = collect(request('products'))->pluck('id');
                $realProductsIds = Product::whereIn('id', $requestProductIds)->pluck('id');
                $requestProductIds->each(function ($id, $index) use ($realProductsIds, $validator) {
                    if (!$realProductsIds->contains($id)) {
                        //add error into the error bags
                        $validator->errors()->add('products.' . $index . ".id", "Selected Products." . $index . ".id is invalid");
                    }
                });
            })
            ->validate();
        return DB::getQueryLog();
    } catch (Exception $e) {
        return $e->getMessage();
    }
});

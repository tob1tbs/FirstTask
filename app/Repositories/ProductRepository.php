<?php

namespace App\Repositories;

use App\Models\Product;
use Carbon\Carbon;

class ProductRepository implements Interfaces\ProductRepositoryInterface
{

    protected $productModel;
    protected $product;

    public function __construct(
        Product $product,
    )
    {
        $this->productModel = $product;
    }

    public function getProductByIds($product_ids) {
        return Product::whereIn('id', $product_ids)->get();
    }
}

?>
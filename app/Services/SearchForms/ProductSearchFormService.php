<?php

namespace App\Services\SearchForms;

use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use App\Services\SearchForms\Base\SearchFormBase;
use App\Services\SearchForms\Base\SearchFormInterface;

class ProductSearchFormService extends SearchFormBase implements SearchFormInterface
{
    public function search(array $validated, string $responseName = 'products', ?string $redirect = null, ?string $notFoundMessage = null) : JsonResponse|RedirectResponse
    {
        $loadWithArray = null;
        if (array_key_exists('loadWith', $validated))
        {
            if (!empty($validated['loadWith']))
            {
                $loadWithArray = explode(', ', $validated['loadWith']);
                for ($i = 0; $i < count($loadWithArray); $i++)
                    $loadWithArray[$i] = 'products.' . $loadWithArray[$i];
            }
        }
        if (!array_key_exists('sellerId', $validated))
        {
            $validated['sellerId'] = null;
        }

        $sellers = null;

        if ($loadWithArray != null)
        {
            $sellers = Seller::with(array_merge(['products'], $loadWithArray));
        }
        if (isset($sellers))
        {
            $sellers = $sellers->where('nickname', 'like', '%' . $validated['sellerName'] . '%')->orWhere('id', $validated['sellerId']);
        }
        else
        {
            $sellers = Seller::where('nickname', 'like', '%' . $validated['sellerName'] . '%')->orWhere('id', $validated['sellerId']);
        }

        $sellers = $sellers->get() ?? null;

        if ($sellers !== null)
        {
            $products = [];
            foreach ($sellers as $seller)
            {
                foreach ($seller->products as $product)
                {
                    $result = stripos($product->name, $validated['productName']) !== false;
                    if ($result != false)
                    {
                        array_push($products, $product);
                    }
                }
            }
        }
        else
        {
            $products = Product::where('name', 'like', "%{$validated['productName']}%")->get()->toArray();
        }

        return parent::validate($products, $responseName, $redirect, $notFoundMessage);
    }
}

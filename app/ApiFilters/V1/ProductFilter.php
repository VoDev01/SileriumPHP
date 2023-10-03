<?php 
namespace App\ApiFilters\V1;

use App\ApiFilters\ApiFilter;
use Illuminate\Http\Request;

class ProductFilter extends ApiFilter
{
    protected $safeParams = [
        'name' => ['eq'],
        'description' => ['eq'],
        'priceRub' => ['eq', 'gt', 'lt', 'gte', 'lte', 'ne'],
        'stockAmount' => ['eq', 'gt', 'lt', 'gte', 'lte', 'ne'],
        'available' => ['eq'],
        'subcategory_id' => ['eq'],
        'timesPurchased' => ['eq', 'gt', 'lt', 'gte', 'lte', 'ne']
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'ne' => '!='
    ];

    public function transform(Request $request)
    {
        $eloQuery = [];

        foreach($this->safeParams as $parm => $operators)
        {
            $query = $request->query($parm);

            if(!isset($query))
            {
                continue;
            }

            foreach($operators as $operator)
            {
                if(isset($query[$operator]))
                {
                    $eloQuery = [$parm, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloQuery;
    }
}
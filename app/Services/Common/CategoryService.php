<?php

namespace App\Services\Common;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

/**
 * Class CategoryService.
 */
class CategoryService
{

    public function getAll()
    {
        $data = Category::query();
        $data->active();
        $data->where('name', '!=', 'Uncategorized');

        $data->when(request()->get('name'), function ($query) {
            $name = request()->get('name');
            $query->where('name', "LIKE", "%{$name}%");
        });

        $data->when(request()->get('slug'), function ($query) {
            $query->where('slug', request()->get('slug'));
        });

        $data->when(request()->get('is_mega'), function ($query) {
            $query->where('is_mega', request()->get('is_mega'));
        });

        if (!empty(authAgentInfo()) && authAgentInfo()['user_type'] === "Agent") {
            $data->select('id', 'name', 'slug', 'thumbnail', 'original', 'is_mega', 'active_status', 'vat_type');
        } else {
            $data->select('id', 'name', 'slug', 'thumbnail', 'original', 'is_mega', 'active_status');
        }

        $data->with(['subcategories' => function ($query) {
            $query->where('name', '!=', 'Unsubcategorized');
            $query->select('category_id', 'name', 'slug', 'thumbnail', 'original', 'active_status');
            $query->with([
                'category' => function ($category) {
                    if (!empty(authAgentInfo()) && authAgentInfo()['user_type'] === "Agent") {
                        $category->select('id', 'name', 'slug', 'thumbnail', 'original', 'is_mega', 'active_status', 'vat_type');
                    } else {
                        $category->select('id', 'name', 'slug', 'thumbnail', 'original', 'is_mega', 'active_status');
                    }
                }
            ]);
        }]);

        $data->orderBy('name');
        $categories = $data->get();

        return apiResponse('success', 200, $categories);
    }


}

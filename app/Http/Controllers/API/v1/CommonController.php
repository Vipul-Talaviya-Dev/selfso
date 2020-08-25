<?php

namespace App\Http\Controllers\Api\V1;

use Validator;
use App\Library\Helper;
use App\Models\Category;
use App\Models\AppVersion;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Controller;

class CommonController extends Controller
{
    public function appVersion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'user_type' => 'required|numeric|in:1',
            'version' => 'required'
        ]);

        if ($validator->fails())  {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => false,
                'message' => $error[0],
            ], Helper::ERROR_CODE);
        } else {
            if($appVersion = AppVersion::where('version', $request->get('version'))->where('type', $request->get('type'))->where('user_type', $request->get('user_type'))->first()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Please Update Your App'
                ], Helper::SUCCESS_CODE);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => '',
                ], Helper::ERROR_CODE);
            }
        }
    }

    public function categories()
    {
        $categories = Category::active()->get()->map(function (Category $category){
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });

        return response()->json([
            'status' => true,
            'categories' => $categories
        ], Helper::SUCCESS_CODE);
    }
}

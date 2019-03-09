<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Product;
use App\Model\Review;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\PorductResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api')->except(["index","show"]);
    }
    public function index()
    {
        return ProductCollection::collection(Product::orderBy('created_at')->paginate(10));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product();
        $user = auth('api')->user();
        $userId = $user->id;
        $product->name = $request->name;
        $product->details = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->discount = $request->discount;
        $product->user_id = $userId;
        if($product->save())
            return response([
                "msg"=>"New product added",
                "ok"=>true
            ],Response::HTTP_CREATED);
        else
            return response([
                "msg"=>"Cannot Save you product",
                "ok"=>false
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if(isset($product))
            return new PorductResource($product);
        else
            return response([
                "msg"=>"Product not found"
            ],Response::HTTP_NOT_FOUND);  
        
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Product $product)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        if($userId===$product->user_id){
            $product->name = $request->name;
            $product->details = $request->description;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->discount = $request->discount;
            if($product->update())
                return response([
                    "msg"=>"Product updated"
                ],Response::HTTP_CREATED);
            else return response([
                "msg"=>"Product not updated"
            ],Response::HTTP_NOT_FOUND);
        }else{
            return response([
                "msg"=>"Product doesn't belogns to you"
            ],Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $user = auth('api')->user();
        $userId = $user->id;
        if($userId===$product->user_id){
            if($product->delete()){
                // delete reviews for the product
                Review::where("product_id",$product->id)->delete();
                return response([
                    "msg"=>"Product has been deleted",
                    "status"=>1
                ],Response::HTTP_OK);
            }
            else{
                return response([
                    "msg"=>"Product has been deleted",
                    "status"=>0
                ],Response::HTTP_NOT_FOUND);
            }
        }else{
            return response([
                "msg"=>"Unauthorized action",
                "status"=>0
            ],Response::HTTP_UNAUTHORIZED);
        }
    }
}

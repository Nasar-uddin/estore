<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Product;
use App\Http\Resources\ReviewResource;
use Symfony\Component\HttpFoundation\Response;
use App\Model\Review;

class ReviewController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api')->except(["index","show"]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $product = Product::find($id);
        if(isset($product)){
            if(count($product->reviews)>0)
                return ReviewResource::collection($product->reviews);
            else
                return "No review";
        }
        else{
            return response([
                "msg"=>"Product not found"
            ],Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        $request->validate([
            "review"=>"bail|required",
            "star"=>"required|digits_between:1,5"
        ]);
        if($product=Product::find($id)){
            $review = new Review();
            $review->user_id = auth("api")->user()->id;
            $review->product_id = $id;
            $review->review = $request->review;
            $review->star = $request->star;
            $review->save();
            return $review;
        }else{
            return response([
                "msg"=>"Product does not exist"
            ],Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($product_id,$review_id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$product, $id)
    {
        // validate
        $request->validate([
            "review"=>"bail|required",
            "star"=>"required|digits:1|regex:/[1-5]/"
        ]);
        $userId = auth('api')->user()->id;

            // check for product existance

        if($product=Product::find($product)){

            if($review=Review::find($id)){

                // check if the review is for the product

                if($review->product_id===$product->id){

                    // check the person is correct or not

                    if($review->user_id===$userId){
                        $review->review = $request->review;
                        $review->star = $request->star;
                        if($review->update()){
                            return response([
                                "msg"=>"Review update successfully"
                            ],Response::HTTP_OK);
                        }else{
                            return response([
                                "msg"=>"Review not update. Please try again"
                            ],Response::HTTP_NOT_FOUND);
                        }
                    }else{
                        return response([
                            "msg"=>"You can not update this review"
                        ],Response::HTTP_BAD_REQUEST);
                    }

                }else{
                    return response([
                        "msg"=>"Invalud request"
                    ],Response::HTTP_BAD_REQUEST);
                }
            }
        }else{
            return response([
                "msg"=>"Porduct not found"
            ],Response::HTTP_NOT_FOUND);
        }
        return $product." ".$id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$review_id)
    {
        $product = Product::find($id);
        if(isset($product)){
            $userId = auth("api")->user()->id;
            $review = Review::find($review_id);
            if($review->user_id===$userId){
                if($review->delete()){
                    return response([
                        "msg"=>"Review deleted successfully"
                    ],Response::HTTP_ACCEPTED);
                }else{
                    return response([
                        "msg"=>"Server error. Please try later"
                    ],Response::HTTP_NOT_FOUND);
                }
            }else{
                return response([
                    "msg"=>"You can't delete the review"
                ],Response::HTTP_NOT_ACCEPTABLE);
            }
            return $product->reviews;
        }else{
            return response([
                "msg"=>"Product not found"
            ],Response::HTTP_NOT_FOUND);
        }
        return $product;
    }
}

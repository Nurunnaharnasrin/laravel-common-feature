<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    //  function invoiceCreate(Request $request){
    //     DB::beginTransaction();

    //     try {
    //         $user_id=$request->header('id');
    //         $total=$request->input('total');
    //         $discount=$request->input('discount');
    //         $vat=$request->input('vat');
    //         $customer_id=$request->input('customer_id');

    //         $invoice=Invoice::create([
    //             'user_id'=>$user_id,
    //             'total'=>$total,
    //             'discount'=>$discount,
    //             'vat'=>$vat,
    //             'customer_id'=>$customer_id
    //         ]);

    //         $invoiceID=$invoice->id;
    //         $products=$request->input('products');
    //         foreach ($products as $Eachproduct) {
    //             Invoice::create([
    //                 'invoice_id'=>$invoiceID,
    //                 'product_id'=>$Eachproduct['product_id'],
    //                 'quantity'=>$Eachproduct['quantity'],
    //                 'price'=>$Eachproduct['price']
    //             ]);
    //         }



    //          DB::commit();

    //          return 1;
    //     }
    //     catch (Exception $e) {
    //          DB::rollBack();
    //          return 0;
    //     }

    //  }


    function invoiceCreate(Request $request){

        DB::beginTransaction();
        try {

            $user_id=$request->header('id');
            $total=$request->input('total');
            $discount=$request->input('discount');
            $vat=$request->input('vat');
            $customer_id=$request->input('customer_id');

            $invoice= Invoice::create([
                'total'=>$total,
                'discount'=>$discount,
                'vat'=>$vat,
                'user_id'=>$user_id,
                'customer_id'=>$customer_id,
            ]);

            $invoiceID=$invoice->id;

            $products= $request->input('products');

            foreach ($products as $EachProduct) {
                InvoiceProduct::create([
                    'invoice_id' => $invoiceID,
                    'product_id' => $EachProduct['product_id'],
                    'qty' => $EachProduct['qty'],
                    'sale_price' => $EachProduct['sale_price'],
                ]);
            }

            DB::commit();
            return 1;
        }
        catch (Exception $e) {
            DB::rollBack();
            return  0;
        }

    }


    function invoiceSelect(Request $request){
        $user_id=$request->header('id');
        $invoice=Invoice::where('user_id',$user_id)->with('customer')->get();
        return $invoice;
    }

    function InvoiceDetails(Request $request){

        $user_id=$request->header('id');
        $customerDetails=Customer::where('user_id',$user_id)->where('id',$request->input('cus_id'))->first();
        $invoiceTotal=Invoice::where('user_id',$user_id)->where('id',$request->input('inv_id'))->first();
        $invoiceProduct=InvoiceProduct::where('invoice_id',$request->input('inv_id'))->get();

        return array(
            'customer'=>$customerDetails,
            'invoice'=>$invoiceTotal,
            'product'=>$invoiceProduct,
        );
    }

    function invoiceDelete(Request $request){
        DB::beginTransaction();
        try {
            InvoiceProduct::where('invoice_id',$request->input('inv_id'))->delete();
            Invoice::where('id',$request->input('inv_id'))->delete();
            DB::commit();
            return 1;
        }
        catch (Exception $e){
        DB::rollBack();
        return 0;
        }

    }
}

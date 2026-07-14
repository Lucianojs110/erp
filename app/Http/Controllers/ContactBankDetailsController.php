<?php

namespace App\Http\Controllers;
use App\Contact;
use App\BankDetails;
use Illuminate\Http\Request;

class ContactBankDetailsController extends Controller
{
    public function save(Request $request)
    {
        if (!auth()->user()->can('supplier.update') && !auth()->user()->can('customer.update')) {
            abort(403, 'Unauthorized action.');
        }  
        
        try {
            $input = $request->only([
            'contact_id',
            'cbu',
            'bank',
            'bank_account_number',
            'alias']);

             
            $bank = $input['bank'];
            $cbu = $input['cbu'];
            $bank_account_number = $input['bank_account_number'];
            $alias = $input['alias'];
                
            //Check Contact id
            if (empty($input['contact_id'])) {
             
                abort(400, '');   
            }
            $contact = Contact::find($input['contact_id']);  
            if ($contact) {
              $bankDetails = BankDetails::where('bank',$bank)->where('bank_account_number',$bank_account_number)->where('contacts_id',$contact->id)->first();
              if($bankDetails)
                throw new \Exception("Error Processing Request", 1); 
              $bankDetails = BankDetails::where('cbu',$cbu)->orWhere('alias',$alias)->where('contacts_id',$contact->id)->first();
              if($bankDetails)
                throw new \Exception("Error Processing Request", 1);
              $bankDetails = new BankDetails(
                [
                   'contacts_id' => $contact->id,
                   'bank'=> $bank,
                   'cbu' => $cbu,
                   'bank_account_number' => $bank_account_number,
                   'alias' => $alias
                ]
                );
              $bankDetails->save();
            } else {
                throw new \Exception("Error Processing Request", 1);
            }
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        
        return redirect("/contacts");
    }

    public function edit(Request $request)
    {
        if (!auth()->user()->can('supplier.update') && !auth()->user()->can('customer.update')) {
            abort(403, 'Unauthorized action.');
        }  
        try {
            $input = $request->only([
            'id',
            'contact_id',
            'cbu',
            'bank',
            'bank_account_number',
            'alias']);

            $id = $input['id']; 
            $bank = $input['bank'];
            $cbu = $input['cbu'];
            $bank_account_number = $input['bank_account_number'];
            $alias = $input['alias'];
                
            //Check bank details id
            if (empty($input['id']) || (empty($input['contact_id']))) {
             
                abort(400, '');   
            }
            $contact = Contact::find($input['contact_id']); 
          
            if ($contact) {
               
              foreach($contact->bankDetails as $bankDetails)
              {
                if($bankDetails->id == $id)
                {
                    $bankDetails->cbu = $cbu;
                    $bankDetails->bank = $bank;
                    $bankDetails->bank_account_number = $bank_account_number;
                    $bankDetails->alias = $alias;
                    $bankDetails->save();
                }
              }
              
            } else {
                throw new \Exception("Error Processing Request", 1);
            }
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        
        return redirect("/contacts");
    }

    public function delete(Request $request)
    {
        if (!auth()->user()->can('supplier.update') && !auth()->user()->can('customer.update')) {
            abort(403, 'Unauthorized action.');
        }  
        try {
            $input = $request->only([
            'id',
            'contact_id',]);

            $id = $input['id']; 

            //Check bank details id
            if (empty($input['id']) || (empty($input['contact_id']))) {
             
                abort(400, '');   
            }
            $contact = Contact::find($input['contact_id']); 
          
            if ($contact) {
               
              foreach($contact->bankDetails as $bankDetails)
              {
                if($bankDetails->id == $id)
                {
                    $bankDetails->delete();
                }
              }
              
            } else {
                throw new \Exception("Error Processing Request", 1);
            }
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }
        
        return redirect("/contacts");
    }
}

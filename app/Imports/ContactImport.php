<?php

namespace App\Imports;

use App\Contact;
use App\Utils\Util;
use App\Utils\TransactionUtil;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ContactImport implements ToCollection, WithStartRow
{
    private $commonUtil;
    private $transactionUtil;


    public function collection(Collection $rows)
    {
        $this->commonUtil = new Util();
        $this->transactionUtil = new TransactionUtil();

        $user_id = request()->session()->get('user.id');

        if (count($rows->first()) !== 22) {
            throw new \Exception("Number of columns mismatch");
        }

        foreach ($rows as $row_no => $row) {
            $contactInput = [];
            $contact_type = $this->getContactType($row[0], $row_no);
            $contactInput['type'] = $contact_type;
            $contactInput['name'] = $this->getContactName($row[1], $row_no);
            if (in_array($contact_type, ['supplier', 'both'])) {
                $contactInput['supplier_business_name'] =$this->getBusinessName($row[2], $row_no);
                $contactInput['pay_term_number'] = $this->getPayTerm($row[6], $row_no);
                $contactInput['pay_term_type'] = $this->getPayPeriod($row[7], $row_no);
            } elseif(in_array($contact_type, ['customer', 'both'])) {
                $contactInput['credit_limit'] = $this->getCreditLimit($row[8]);
            }
            $contactInput['contact_id'] = $this->getContactId($row[3], $row_no);
            $contactInput['tax_number'] = $this->getTaxNumber($row[4]);
            $contactInput['email'] = $this->getEmail($row[9], $row_no);
            $contactInput['mobile'] = $this->getMobile($row[10], $row_no);
            $contactInput['alternate_number'] = $this->getAlternateNumber($row[11]);
            $contactInput['landline'] = $this->getLandline($row[12]);
            $contactInput['city'] = $this->getCity($row[13]);
            $contactInput['state'] = $this->getState($row[14]);
            $contactInput['country'] = $this->getCountry($row[15]);
            $contactInput['landmark'] = $this->getLandMark($row[16]);
            $contactInput['custom_field1'] = $this->getCustomField1($row[17]);
            $contactInput['custom_field2'] = $this->getCustomField2($row[18]);
            $contactInput['custom_field3'] = $this->getCustomField3($row[19]);
            $contactInput['custom_field4'] = $this->getCustomField4($row[20]);
            $contactInput['business_id'] = request()->session()->get('user.business_id');
            $contactInput['created_by'] = $user_id;
            $contactInput['iva'] = $this->getIva(trim($row[21]), $row_no);
            $contact = Contact::create($contactInput);

            $this->saveOpeningBalance($contact, $row[5]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    private function getIva($iva, $row_no)
    {
        if (empty($iva) || !in_array($iva, ['CONSUMIDOR FINAL', 'RESPONSABLE INSCRIPTO'])) {
            throw new \Exception("IVA error: in row no. $row_no");
        }
        return $iva;
    }

    private function getContactType($contactType, $row_no)
    {
        $contactType = strtolower(trim($contactType));
        if (empty($contactType) && !in_array($contactType, ['supplier', 'customer', 'both'])) {
            throw new \Exception("Invalid contact type in row no. $row_no");
        }
        return $contactType;
    }

    private function getContactName($contactName, $row_no)
    {
        $contactName = trim($contactName);
        if (empty($contactName)){
            throw new \Exception("Contact name is required in row no. $row_no");
        }
        return $contactName;
    }

    private function getBusinessName($businessName,$row_no)
    {
        $businessName = trim($businessName);
        if (empty($businessName)){
            throw new \Exception("Business name is required in row no. $row_no");
        }
        return $businessName;
    }

    private function getPayTerm($payTerm,$row_no)
    {
        $payTerm = trim($payTerm);
        if (empty($payTerm)) {
            throw new \Exception("Pay term is required in row no. $row_no");
        }
        return $payTerm;
    }

    private function getPayPeriod($payPeriod,$row_no)
    {
        $payPeriod = strtolower(trim($payPeriod));
        if (empty($payPeriod) || !in_array($payPeriod, ['days', 'months'])) {
            throw new \Exception("Pay term period is required in row no. $row_no");
        }
        return $payPeriod;
    }

    private function getContactId($contactId,$row_no)
    {
        $contactId = trim($contactId);
        if (empty($contactId)) {
            $ref_count = $this->commonUtil->setAndGetReferenceCount('contacts');
            return $this->commonUtil->generateReferenceNumber('contacts', $ref_count);
        }
        $exist = Contact::where('business_id', request()->session()->get('user.business_id'))
            ->where('contact_id', $contactId)
            ->count();
        if($exist){
            throw new \Exception("Contact ID already exists in row no. $row_no");
            dd('Error');
        }
        return $contactId;
    }

    private function getTaxNumber($taxNumber)
    {
        return trim($taxNumber) ?? null;
    }

    private function saveOpeningBalance($contact, $openingBalance)
    {
        $openingBalance = trim($openingBalance);
        if(!empty($openingBalance) || is_numeric($openingBalance)){
            $business_id = request()->session()->get('user.business_id');
            $this->transactionUtil->createOpeningBalanceTransaction($business_id, $contact->id, $openingBalance);
        }
    }

    private function getCreditLimit($creditLimit)
    {
        return trim($creditLimit) ?? null;
    }

    private function getEmail($email,$row_no)
    {
        $email = trim($email);
        if (empty($email)) {
            return null;
        }
        if(filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
            return $email;
        }
        throw new \Exception("Invalid email id in row no. $row_no");
    }

    private function getMobile($mobile,$row_no)
    {
        $mobile = trim($mobile);
        if (empty($mobile)) {
            return '-';
        }
        return $mobile;
    }

    private function getAlternateNumber($alternateNumber)
    {
        return trim($alternateNumber) ?? null;
    }

    private function getLandline($landline)
    {
        return trim($landline) ?? null;
    }

    private function getCity($city)
    {
        return trim($city) ?? null;
    }

    private function getState($state)
    {
        return trim($state) ?? null;
    }

    private function getCountry($country)
    {
        return trim($country) ?? null;
    }

    private function getLandMark($landMark)
    {
        return trim($landMark) ?? null;
    }

    private function getCustomField1($customField1)
    {
        return trim($customField1) ?? null;
    }

    private function getCustomField2($customField2)
    {
        return trim($customField2) ?? null;
    }

    private function getCustomField3($customField3)
    {
        return trim($customField3) ?? null;
    }

    private function getCustomField4($customField4)
    {
        return trim($customField4) ?? null;
    }

}
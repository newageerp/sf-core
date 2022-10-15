<?php
namespace Newageerp\SfSepa\Service;

use Digitick\Sepa\TransferFile\Factory\TransferFileFacadeFactory;

class SepaService
{
    public function generateCreditTransfer(
        string $messageId,
        string $companyName,
        string $companyCode,
        string $companyIBan,
        int $amount,
        string $customerName,
        string $customerCode,
        string $customerIban,
        string $purpose,
    ): string {
        $customerCredit = TransferFileFacadeFactory::createCustomerCredit($messageId . '-' . time(), $companyName);

        $paymentName = $messageId;

        $customerCredit->addPaymentInfo($paymentName, array(
            'id'                      => $paymentName,
            'debtorName'              => $companyName,
            'debtorAccountIBAN'       => $companyIBan,
            'debtorAgentBIC'          => $companyCode,
            // Add/Set batch booking option, you can pass boolean value as per your requirement, optional
            'batchBooking'            => true,
        ));

        // Add a Single Transaction to the named payment
        $customerCredit->addTransfer($paymentName, array(
            'amount'                  => $amount, // `amount` should be in cents
            'creditorIban'            => $customerIban,
            'creditorBic'             => $customerCode,
            'creditorName'            => $customerName,
            'remittanceInformation'   => $purpose,
        ));
        // Retrieve the resulting XML
        return $customerCredit->asXML();
    }
}
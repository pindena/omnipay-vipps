<?php

namespace Pindena\Omnipay\Vipps\Message\Response;

class DetailsResponse extends Response
{
    public function isSuccessful()
    {
        return isset($this->data['transactionSummary']) && isset($this->data['transactionLogHistory']);
    }

    public function isCancelled()
    {
        return in_array($this->latestSuccessfulOperation(), ['CANCEL', 'VOID']);
    }

    public function isReserved()
    {
        return $this->latestSuccessfulOperation() == 'RESERVE';
    }

    public function latestSuccessfulOperation()
    {
        if (is_null($transaction = $this->latestSuccessfulTransaction())) {
            return;
        }

        return $transaction['operation'];
    }

    public function latestSuccessfulTransaction()
    {
        if (empty($this->data['transactionLogHistory'] ||
            ! is_array($this->data['transactionLogHistory']))
        ) {
            return;
        }

        foreach ($this->data['transactionLogHistory'] as $transaction) {
            if (isset($transaction['operationSuccess']) && $transaction['operationSuccess']) {
                return $transaction;
            }
        }
    }
}

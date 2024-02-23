<?php

namespace Simpl\Checkout\Model;

use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Simpl\Checkout\Api\CreditMemoDetailsInterface;
use Simpl\Checkout\Model\Data\GetCreditmemoResponse;
use Simpl\Checkout\Helper\Alert;

class GetCreditMemoDetails implements CreditMemoDetailsInterface
{

    /**
     * @var CreditmemoRepositoryInterface
     */
    protected $creditmemoRepository;

    /**
     * @var GetCreditmemoResponse
     */
    protected $getCreditmemoResponse;

    protected $alert;

    /**
     * @param CreditmemoRepositoryInterface $creditmemoRepository
     * @param GetCreditmemoResponse $getCreditmemoResponse
     * @param Alert $alert
     */
    public function __construct(
        CreditmemoRepositoryInterface $creditmemoRepository,
        GetCreditmemoResponse $getCreditmemoResponse,
        Alert $alert
    ) {
        $this->creditmemoRepository = $creditmemoRepository;
        $this->getCreditmemoResponse = $getCreditmemoResponse;
        $this->alert = $alert;
    }

    /**
     * @inheritDoc
     */
    public function getCreditMemo($orderId, $creditMemoId)
    {
        try {
            $creditMemo = $this->creditmemoRepository->get($creditMemoId);
            return $this->getCreditmemoResponse->setCreditMemo($creditMemo);
        } catch (\Exception $e) {
            $this->alert->alert($e->getMessage(), 'ERROR', $e->getTraceAsString());
            return $this->getCreditmemoResponse->creditMemoNotFoundError();
        }
    }
}

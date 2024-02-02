<?php

namespace Simpl\Checkout\Model;

use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Simpl\Checkout\Api\CreditMemoDetailsInterface;
use Simpl\Checkout\Model\Data\GetCreditmemoResponse;

class CreditMemoDetails implements CreditMemoDetailsInterface {

    /**
     * @var CreditmemoRepositoryInterface
     */
    protected $creditmemoRepository;

    /**
     * @var GetCreditmemoResponse
     */
    protected $response;

    /**
     * @param CreditmemoRepositoryInterface $creditmemoRepository
     * @param GetCreditmemoResponse $getCreditmemoResponse
     */
    public function __construct(
        CreditmemoRepositoryInterface $creditmemoRepository,
        GetCreditmemoResponse $getCreditmemoResponse
    ) {
        $this->creditmemoRepository = $creditmemoRepository;
        $this->getCreditmemoResponse = $getCreditmemoResponse;
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

            return $this->getCreditmemoResponse->creditMemoNotFoundError();
        }
    }
}

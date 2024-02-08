<?php

namespace Simpl\Checkout\Model;

use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Simpl\Checkout\Api\CreditMemoDetailsInterface;
use Simpl\Checkout\Model\Data\GetCreditmemoResponse;
use Simpl\Checkout\Helper\SimplApi;

class GetCreditMemoDetails implements CreditMemoDetailsInterface {

    /**
     * @var CreditmemoRepositoryInterface
     */
    protected $creditmemoRepository;

    /**
     * @var GetCreditmemoResponse
     */
    protected $getCreditmemoResponse;

    /**
     * @var SimplApi
     */
    protected $simplApi;

    /**
     * @param CreditmemoRepositoryInterface $creditmemoRepository
     * @param GetCreditmemoResponse $getCreditmemoResponse
     */
    public function __construct(
        CreditmemoRepositoryInterface $creditmemoRepository,
        GetCreditmemoResponse $getCreditmemoResponse,
        SimplApi $simplApi,
    ) {
        $this->creditmemoRepository = $creditmemoRepository;
        $this->getCreditmemoResponse = $getCreditmemoResponse;
        $this->simplApi = $simplApi;
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
            $this->simplApi->alert($e->getMessage(), 'INFO', $e->getTraceAsString());
            return $this->getCreditmemoResponse->creditMemoNotFoundError();
        }
    }
}

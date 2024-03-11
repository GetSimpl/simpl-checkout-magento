<?php

namespace Simpl\Checkout\Model;

use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Simpl\Checkout\Api\CreditMemoDetailsInterface;
use Simpl\Checkout\Model\Data\GetCreditmemoResponse;
use Simpl\Checkout\Logger\Logger;

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

    protected $logger;

    /**
     * @param CreditmemoRepositoryInterface $creditmemoRepository
     * @param GetCreditmemoResponse $getCreditmemoResponse
     * @param Logger $logger
     */
    public function __construct(
        CreditmemoRepositoryInterface $creditmemoRepository,
        GetCreditmemoResponse $getCreditmemoResponse,
        Logger $logger
    ) {
        $this->creditmemoRepository = $creditmemoRepository;
        $this->getCreditmemoResponse = $getCreditmemoResponse;
        $this->logger = $logger;
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
            $this->logger->error($e->getMessage(),['stacktrace' => $e->getTraceAsString()]);
            return $this->getCreditmemoResponse->creditMemoNotFoundError();
        }
    }
}

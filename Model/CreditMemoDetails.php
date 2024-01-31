<?php

namespace Simpl\Checkout\Model;

use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Simpl\Checkout\Api\CreditMemoDetailsInterface;
use Simpl\Checkout\Model\Data\CreditmemoResponse as Response;

class CreditMemoDetails implements CreditMemoDetailsInterface {

    /**
     * @var CreditmemoRepositoryInterface
     */
    protected $creditmemoRepository;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param CreditmemoRepositoryInterface $creditmemoRepository
     * @param Response $response
     */
    public function __construct(
        CreditmemoRepositoryInterface $creditmemoRepository,
        Response $response
    ) {
        $this->creditmemoRepository = $creditmemoRepository;
        $this->response = $response;
    }

    /**
     * @inheritDoc
     */
    public function getCreditMemo($orderId, $creditMemoId)
    {
        try {

            $creditMemo = $this->creditmemoRepository->get($creditMemoId);
            return $this->response->setCreditMemo($creditMemo);
        } catch (\Exception $e) {

            return $this->response->creditMemoNotFoundError();
        }
    }
}

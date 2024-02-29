<?php
/**
 * Copyright © Kellton, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kellton\ChatGptAI\Controller\Adminhtml\Generate;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Kellton\ChatGptAI\Helper\Config;
use Psr\Log\LoggerInterface;

/**
 * Class for Ajax request
 *
 */
class Index extends Action
{
    private const HTTP_OK = 200;
    private const HTTP_INTERNAL_ERROR = 500;
    /**
     * @var JsonFactory
     */
    protected $resultJson;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Config
     */
    protected $helper;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param JsonFactory       $resultJson
     * @param Context           $context
     * @param PageFactory       $resultPageFactory
     * @param Config            $helper
     * @param LoggerInterface   $logger
     */
    public function __construct(
        JsonFactory $resultJson,
        Context $context,
        PageFactory $resultPageFactory,
        Config $helper,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->resultJson = $resultJson;
        $this->resultPageFactory = $resultPageFactory;
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        try {
            $title = $this->getRequest()->getParam('form_title');
            $color = $this->getRequest()->getParam('form_color');
            $category = $this->getRequest()->getParam('form_cat');
            $formType = $this->getRequest()->getParam('form_type');
            $section = $this->getRequest()->getParam('section');
            $formPrefix = strstr($section, '_', true);
            $response = $this->helper->setOpenAIRequest($title, $color, $category, $formPrefix, $formType);
            $result = $this->helper->getJsonHelper()->jsonDecode($response);
            $responseCode = self::HTTP_OK;
            $responseContent = [
                'success' => true,
                'result' => $result
            ];
        } catch (LocalizedException $exception) {
            $responseCode = self::HTTP_INTERNAL_ERROR;
            $this->logger->critical($exception);
            $responseContent = [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        } catch (\Exception $exception) {
            $this->logger->critical($exception);
            $responseCode = self::HTTP_INTERNAL_ERROR;
            $responseContent = [
                'success' => false,
                'message' => __('Something went wrong!'),
            ];
        }
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setHttpResponseCode($responseCode);
        $resultJson->setData($responseContent);

        return $resultJson;
    }
}

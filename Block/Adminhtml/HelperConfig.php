<?php
/**
 * Copyright © Kellton, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kellton\ChatGptAI\Block\Adminhtml;

use Kellton\ChatGptAI\Helper\Config;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class HelperConfig extends Template
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Config
     */
    protected $configHelper;

    /**
     * Constructor
     *
     * @param Context   $context
     * @param Config    $configHelper
     */

    public function __construct(
        Context $context,
        Config $configHelper
    ) {
        parent::__construct($context);
        $this->helper = $configHelper;
    }

    /**
     * Check extension status enabled/disabled
     *
     * @return int
     */
    public function isEnabled()
    {
        return $this->helper->getEnableConfig();
    }

    /**
     * Get applied area
     *
     * @return string
     */
    public function getAppliedArea()
    {
        return $this->helper->getChatGptAppliedArea();
    }
}

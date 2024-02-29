<?php
/**
 * Copyright © Kellton, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Kellton\ChatGptAI\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ListModel implements OptionSourceInterface
{
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
        ['value' => 'gpt-3.5-turbo', 'label' => __('gpt-3.5-turbo')]
        ];
    }
}

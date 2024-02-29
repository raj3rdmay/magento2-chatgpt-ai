<?php
/**
 * Copyright © Kellton, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Kellton\ChatGptAI\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ListArea implements OptionSourceInterface
{
    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        return [
        ['value' => 'catalog-category', 'label' => __('Catalog Category')],
        ['value' => 'catalog-product', 'label' => __('Catalog Product')],
        ['value' => 'cms-pages', 'label' => __('CMS Page')]

        ];
    }
}

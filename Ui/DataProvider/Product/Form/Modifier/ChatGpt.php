<?php
/**
 * Copyright © Kellton, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kellton\ChatGptAI\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Container;
use Kellton\ChatGptAI\Helper\Config;

/**
 * Class for Product Modifier Attributes
 *
 */
class ChatGpt extends AbstractModifier
{
    protected const CODE_META_TITLE = 'meta_title';
    protected const CODE_META_KEYWORD = 'meta_keyword';
    protected const CODE_META_DESCRIPTION = 'meta_description';
    protected const CODE_SHORT_DESCRIPTION = 'short_description';

    /**
     * @var  ArrayManager
     */
    protected $arrayManager;

    /**
     * @var  Config
     */
    protected $helper;

    /**
     * @param ArrayManager  $arrayManager
     * @param Config        $helper
     */
    public function __construct(
        ArrayManager $arrayManager,
        Config $helper
    ) {
        $this->arrayManager = $arrayManager;
        $this->helper = $helper;
    }

    /**
     * @inheritdoc
     */
    public function modifyMeta(array $meta): array
    {
        if (!$this->helper->getEnableConfig()) {
            return $meta;
        }
        $appliedArea = $this->helper->getChatGptAppliedArea();
        $appliedArea = explode(',', $appliedArea ? $appliedArea : "");
        $containerPrefix = "container_";
        $buttonSuffix = "_button";
        $fieldCode = self::CODE_META_TITLE;
        $containerMetaTitlePath = $this->arrayManager->findPath($containerPrefix . $fieldCode, $meta, null, 'children');
        $metaTitle['children'][$fieldCode.$buttonSuffix] = [
        'arguments' => [
            'data' => [
                'config' => [
                    'title' => __('Generate With AI'),
                    'formElement' => Container::NAME,
                    'buttonClasses' => 'chatgpt_button_wrapper',
                    'additionalClasses' => 'kell_addditional_chatgptai',
                    'elementTmpl' => 'Kellton_ChatGptAI/form/element/chatgpt',
                    'componentType' => Container::NAME,
                    'disabled' => false,
                    'component' => 'Kellton_ChatGptAI/js/productchatgpt',
                    'template' => 'ui/form/components/button/container',
                    'additionalForGroup' => true,
                    'provider' => false,
                    'source' => 'product_details',
                    'displayArea' => 'insideGroup',
                    'sortOrder' => 55,
                    'dataScope' => $fieldCode,
                ],
            ],
        ]
        ];
        $fieldCode = self::CODE_META_KEYWORD;
        $buttonSuffix = "s_button";
        $containerMetaKeywordPath = $this->arrayManager->findPath($containerPrefix.$fieldCode, $meta, null, 'children');
        $metaKeyword['children'][$fieldCode.$buttonSuffix] = [
        'arguments' => [
            'data' => [
                'config' => [
                    'title' => __('Generate With AI'),
                    'formElement' => Container::NAME,
                    'buttonClasses' => 'chatgpt_button_wrapper',
                    'additionalClasses' => 'kell_addditional_chatgptai',
                    'elementTmpl' => 'Kellton_ChatGptAI/form/element/chatgpt',
                    'componentType' => Container::NAME,
                    'disabled' => false,
                    'component' => 'Kellton_ChatGptAI/js/productchatgpt',
                    'template' => 'ui/form/components/button/container',
                    'additionalForGroup' => true,
                    'provider' => false,
                    'source' => 'product_details',
                    'displayArea' => 'insideGroup',
                    'sortOrder' => 55,
                    'dataScope' => $fieldCode,
                ],
            ],
        ]
        ];
        $fieldCode = self::CODE_META_DESCRIPTION;
        $buttonSuffix = "_button";
        $containerMetaDescPath = $this->arrayManager->findPath($containerPrefix . $fieldCode, $meta, null, 'children');

        $metaDescription['children'][$fieldCode.$buttonSuffix] = [
        'arguments' => [
            'data' => [
                'config' => [
                    'title' => __('Generate With AI'),
                    'formElement' => Container::NAME,
                    'buttonClasses' => 'chatgpt_button_wrapper',
                    'additionalClasses' => 'kell_addditional_chatgptai',
                    'elementTmpl' => 'Kellton_ChatGptAI/form/element/chatgpt',
                    'componentType' => Container::NAME,
                    'disabled' => false,
                    'component' => 'Kellton_ChatGptAI/js/productchatgpt',
                    'template' => 'ui/form/components/button/container',
                    'additionalForGroup' => true,
                    'provider' => false,
                    'source' => 'product_details',
                    'displayArea' => 'insideGroup',
                    'sortOrder' => 55,
                    'dataScope' => $fieldCode,
                ],
            ],
        ]
        ];
        $fieldCode = self::CODE_SHORT_DESCRIPTION;
        $containerShortDescPath = $this->arrayManager->findPath($containerPrefix . $fieldCode, $meta, null, 'children');
        $shortDescription['children'][$fieldCode.$buttonSuffix] = [
        'arguments' => [
            'data' => [
                'config' => [
                    'title' => __('Generate With AI'),
                    'formElement' => Container::NAME,
                    'buttonClasses' => 'chatgpt_button_wrapper',
                    'additionalClasses' => 'kell_addditional_chatgptai_short_desc',
                    'elementTmpl' => 'Kellton_ChatGptAI/form/element/chatgpt',
                    'componentType' => Container::NAME,
                    'disabled' => false,
                    'component' => 'Kellton_ChatGptAI/js/productchatgpt',
                    'template' => 'ui/form/components/button/container',
                    'additionalForGroup' => true,
                    'provider' => false,
                    'source' => 'product_details',
                    'displayArea' => 'insideGroup',
                    'sortOrder' => 55,
                    'dataScope' => $fieldCode,
                ],
            ],
        ]
        ];
        $meta = $this->arrayManager->merge($containerMetaTitlePath, $meta, $metaTitle);
        $meta = $this->arrayManager->merge($containerMetaKeywordPath, $meta, $metaKeyword);
        $meta = $this->arrayManager->merge($containerMetaDescPath, $meta, $metaDescription);
        if (in_array('catalog-product', $appliedArea)) {
            $meta = $this->arrayManager->merge($containerShortDescPath, $meta, $shortDescription);
        }
        return $meta;
    }

    /**
     * @inheritdoc
     */
    public function modifyData(array $data)
    {
        return $data;
    }
}

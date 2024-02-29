<?php
/**
 * Copyright Â© Kellton, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Kellton\ChatGptAI\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\json\Helper\Data as JsonHelper;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\InputException;

/**
 *  Helper Data.
 */
class Config extends AbstractHelper
{
    /**
     * Config XML paths
     */
    public const ENABLE_MODULE = 'kelltonconfig/general/enable';
    public const SECRET_KEY    = 'kelltonconfig/general/kell_openai_general_secret_key';
    public const ENABLE_PRODUCT_CONFIG  = 'kelltonconfig/product_configuration/enable_product';
    public const ENABLE_CATEGORY_CONFIG = 'kelltonconfig/category_configuration/enable_category';
    public const ENABLE_CMS_PAGE_CONFIG = 'kelltonconfig/cmspage_configuration/enable_cmspage';
    public const PRODUCT_ATTR  = 'kelltonconfig/product_configuration/kell_product_attributes';
    public const PRODUCT_MODEL_NAME  = 'kelltonconfig/product_configuration/pro_model_name';
    public const PRODUCT_TEMPERATURE = 'kelltonconfig/product_configuration/kell_pro_temperature';
    public const PRODUCT_SHORT_DESC_MAX_TOKENS  = 'kelltonconfig/product_configuration/kell_pro_short_desc_max_tokens';
    public const PRODUCT_DESC_MAX_TOKENS  = 'kelltonconfig/product_configuration/kell_pro_desc_tokens';
    public const PRODUCT_META_TITLE_MAX_TOKENS  = 'kelltonconfig/product_configuration/kell_pro_meta_title_max_tokens';
    public const PRODUCT_META_KEYWORDS_MAX  = 'kelltonconfig/product_configuration/kell_pro_meta_keywords_max_tokens';
    public const PRODUCT_META_DESC_MAX_TOKENS  = 'kelltonconfig/product_configuration/kell_pro_meta_desc_max_tokens';
    public const CATEGORY_MODEL_NAME  = 'kelltonconfig/category_configuration/cat_model_name';
    public const CATEGORY_TEMPERATURE = 'kelltonconfig/category_configuration/kell_cat_temperature';
    public const CATEGORY_SHORT_DESC_MAX  = 'kelltonconfig/category_configuration/kell_cat_short_desc_max_tokens';
    public const CATEGORY_DESC_MAX_TOKENS  = 'kelltonconfig/category_configuration/kell_cat_desc_tokens';
    public const CATEGORY_META_TITLE_MAX  = 'kelltonconfig/category_configuration/kell_cat_meta_title_max_tokens';
    public const CATEGORY_META_KEYWORDS_MAX  = 'kelltonconfig/category_configuration/kell_cat_meta_keywords_max_tokens';
    public const CATEGORY_META_DESC_MAX_TOKENS  = 'kelltonconfig/category_configuration/kell_cat_meta_desc_max_tokens';

    public const CMS_MODEL_NAME  = 'kelltonconfig/cmspage_configuration/cmspage_model_name';
    public const CMS_TEMPERATURE = 'kelltonconfig/cmspage_configuration/kell_temperature';
    public const CMS_DESC_MAX_TOKENS  = 'kelltonconfig/cmspage_configuration/kell_desc_tokens';
    public const CMS_META_TITLE_MAX  = 'kelltonconfig/cmspage_configuration/kell_meta_title_max_tokens';
    public const CMS_META_KEYWORDS_MAX  = 'kelltonconfig/cmspage_configuration/kell_meta_keywords_max_tokens';
    public const CMS_META_DESC_MAX_TOKENS  = 'kelltonconfig/cmspage_configuration/kell_meta_desc_max_tokens';

    public const PRODUCT_SECTION       =  'kelltonconfig/product_configuration/';
    public const CATEGORY_SECTION       =  'kelltonconfig/category_configuration/';
    public const CMS_SECTION            =  'kelltonconfig/cmspage_configuration/';
    public const SHORT_DESCRIPTION_PROMPT      =  'kell_short_desc_prompt';
    public const DESCRIPTION_PROMPT      =  'kell_desc_prompt';
    public const META_TITLE_PROMPT      =  'kell_meta_title_prompt';
    public const META_KEYWORDS_PROMPT      =  'kell_meta_keywords_prompt';
    public const META_DESCRIPTION_PROMPT      =  'kell_meta_description_prompt';
    public const OPENAI_END_POINT  = 'https://api.openai.com/v1/chat/completions';
    
    /**
     * Store Data
     *
     * @var StoreManagerInterface
     */
    protected $storeManagerInterface;

    /**
     * Json Serializer
     *
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * curl request
     *
     * @var Curl
     */
    protected $curl;

    /**
     * Json serializer
     *
     * @var Json
     */
    protected $json;

    /**
     * json data helper
     *
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManagerInterface
     * @param SerializerInterface $serializer
     * @param Curl $curl
     * @param Json $json
     * @param JsonHelper $jsonHelper
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManagerInterface,
        SerializerInterface $serializer,
        Curl $curl,
        Json $json,
        JsonHelper $jsonHelper
    ) {
        parent::__construct($context);
        $this->storeManagerInterface = $storeManagerInterface;
        $this->serializer = $serializer;
        $this->curl = $curl;
        $this->json = $json;
        $this->jsonHelper = $jsonHelper;
    }
    /**
     * Get config value
     *
     * @param string $path
     * @return string|int
     */
    public function getConfigValue($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Check extension status enabled/disabled
     *
     * @return int
     */
    public function getEnableConfig()
    {
         $configModalEnable = $this->getConfigValue(
             self::ENABLE_MODULE
         );
        return $configModalEnable;
    }

    /**
     * Check products configuration status enabled/disabled
     *
     * @return int
     */
    public function getEnableProductConfig()
    {
         $configEnable = $this->getConfigValue(
             self::ENABLE_PRODUCT_CONFIG
         );
        return $configEnable;
    }

    /**
     * Check category configuration status enabled/disabled
     *
     * @return int
     */
    public function getEnableCategoryConfig()
    {
         $configEnable = $this->getConfigValue(
             self::ENABLE_CATEGORY_CONFIG
         );
        return $configEnable;
    }

    /**
     * Check CMS pages configuration status enabled/disabled
     *
     * @return int
     */
    public function getEnableCmsPagesConfig()
    {
         $configEnable = $this->getConfigValue(
             self::ENABLE_CMS_PAGE_CONFIG
         );
        return $configEnable;
    }
   
    /**
     * Get API secret key
     *
     * @return string
     */
    private function getSecretKey()
    {
          $configSecretKey = $this->scopeConfig->getValue(
              self::SECRET_KEY
          );
        return $configSecretKey;
    }

    /**
     * Get applied area
     *
     * @return string
     */
    public function getChatGptAppliedArea()
    {
        $configAppliedArea = "";
        if ($this->getEnableProductConfig()) {
            $configAppliedArea .= 'catalog-product,';
        }
        if ($this->getEnableCategoryConfig()) {
            $configAppliedArea .= 'catalog-category,';
        }
        if ($this->getEnableCmsPagesConfig()) {
            $configAppliedArea .= 'cms-pages';
        }
        return $configAppliedArea;
    }

    /**
     * Get product attribute
     *
     * @return string
     */
    public function getProductAttribute()
    {
        $configProductAttr = $this->getConfigValue(
            self::PRODUCT_ATTR
        );
        return $configProductAttr;
    }
    
    /**
     * Get product model name
     *
     * @return string
     */
    public function getProductModelName()
    {
        $configProductModelName = $this->getConfigValue(
            self::PRODUCT_MODEL_NAME
        );
        return $configProductModelName;
    }

    /**
     * Get product content temprature
     *
     * @return string
     */
    public function getProductTemprature()
    {
        $configProductTemperature = $this->getConfigValue(
            self::PRODUCT_TEMPERATURE
        );
        return $configProductTemperature;
    }

    /**
     * Get product short description max length token
     *
     * @return string
     */
    public function getProductShortDescMaxTokens()
    {
        $configProShortDescMaxTokens = $this->getConfigValue(
            self::PRODUCT_SHORT_DESC_MAX_TOKENS
        );
        return $configProShortDescMaxTokens;
    }

    /**
     * Get product description max length token
     *
     * @return string
     */
    public function getProductDescMaxTokens()
    {
        $configProductDescMaxTokens = $this->getConfigValue(
            self::PRODUCT_DESC_MAX_TOKENS
        );
        return $configProductDescMaxTokens;
    }
    /**
     * Get product meta title max length token
     *
     * @return string
     */
    public function getProductMetaTitleMaxTokens()
    {
        $configProductMetaTitleMaxTokens = $this->getConfigValue(
            self::PRODUCT_META_TITLE_MAX_TOKENS
        );
        return $configProductMetaTitleMaxTokens;
    }
    /**
     * Get product keyword max length token
     *
     * @return string
     */
    public function getProductKeywordMaxTokens()
    {
        $configProductKeywordsMaxTokens = $this->getConfigValue(
            self::PRODUCT_META_KEYWORDS_MAX
        );
        return $configProductKeywordsMaxTokens;
    }

    /**
     * Get product meta description max length token
     *
     * @return string
     */
    public function getProductMetaDescMaxTokens()
    {
        $configProductMetaDescMaxTokens = $this->getConfigValue(
            self::PRODUCT_META_DESC_MAX_TOKENS
        );
        return $configProductMetaDescMaxTokens;
    }

    /**
     * Get category model name
     *
     * @return string
     */
    public function getCategoryModelName()
    {
         $configCatModelName = $this->getConfigValue(
             self::CATEGORY_MODEL_NAME
         );
        return $configCatModelName;
    }
    /**
     * Get category content temprature
     *
     * @return string
     */
    public function getCategoryTemprature()
    {
        $configCatTempratute = $this->getConfigValue(
            self::CATEGORY_TEMPERATURE
        );
        return $configCatTempratute;
    }
    /**
     * Get category short description max length token
     *
     * @return string
     */
    public function getCategoryShortDescMaxTokens()
    {
        $configCatShortDescMaxTokens = $this->getConfigValue(
            self::CATEGORY_SHORT_DESC_MAX
        );
        return $configCatShortDescMaxTokens;
    }
    /**
     * Get category description max length token
     *
     * @return string
     */
    public function getCategoryDescMaxTokens()
    {
        $configCatDescMaxToken = $this->getConfigValue(
            self::CATEGORY_DESC_MAX_TOKENS
        );
        return $configCatDescMaxToken;
    }
    /**
     * Get category meta title  max length token
     *
     * @return string
     */
    public function getCategoryMetaTitleMaxTokens()
    {
        $configCatMetaTitleMaxTokens = $this->getConfigValue(
            self::CATEGORY_META_TITLE_MAX
        );
        return $configCatMetaTitleMaxTokens;
    }
    /**
     * Get category meta keywords  max length token
     *
     * @return string
     */
    public function getCategoryKeywordMaxTokens()
    {
        $configCatKeywordMaxToken = $this->getConfigValue(
            self::CATEGORY_META_KEYWORDS_MAX
        );
        return $configCatKeywordMaxToken;
    }

    /**
     * Get category meta description  max length token
     *
     * @return string
     */
    public function getCategoryMetaDescMaxTokens()
    {
        $configCatMetaDescMaxToken = $this->getConfigValue(
            self::CATEGORY_META_DESC_MAX_TOKENS
        );
        return $configCatMetaDescMaxToken;
    }

    /**
     * Get cms model name
     *
     * @return string
     */
    public function getCmsModelName()
    {
         $configCmsModelName = $this->getConfigValue(
             self::CMS_MODEL_NAME
         );
        return $configCmsModelName;
    }

    /**
     * Get cms content temprature
     *
     * @return string
     */
    public function getCmsTemprature()
    {
        $configCmsTempratute = $this->getConfigValue(
            self::CMS_TEMPERATURE
        );
        return $configCmsTempratute;
    }

    /**
     * Get cms description max length token
     *
     * @return string
     */
    public function getCmsDescMaxTokens()
    {
        $configCmsDescMaxToken = $this->getConfigValue(
            self::CMS_DESC_MAX_TOKENS
        );
        return $configCmsDescMaxToken;
    }

    /**
     * Get cms meta title  max length token
     *
     * @return string
     */
    public function getCmsMetaTitleMaxTokens()
    {
        $configCmsMetaTitleMaxTokens = $this->getConfigValue(
            self::CMS_META_TITLE_MAX
        );
        return $configCmsMetaTitleMaxTokens;
    }

    /**
     * Get cms meta keywords  max length token
     *
     * @return string
     */
    public function getCmsKeywordMaxTokens()
    {
        $configCmsKeywordMaxToken = $this->getConfigValue(
            self::CMS_META_KEYWORDS_MAX
        );
        return $configCmsKeywordMaxToken;
    }

    /**
     * Get cms meta description  max length token
     *
     * @return string
     */
    public function getCmsMetaDescMaxTokens()
    {
        $configCmsMetaDescMaxToken = $this->getConfigValue(
            self::CMS_META_DESC_MAX_TOKENS
        );
        return $configCmsMetaDescMaxToken;
    }

    /**
     * Get short description prompt by section
     *
     * @param string $section
     * @return string
     */
    public function getShortDescriptionPrompt($section)
    {
        $configShortDescription = $this->getConfigValue(
            $section.self::SHORT_DESCRIPTION_PROMPT
        );
        return $configShortDescription;
    }
    /**
     * Get description prompt by section
     *
     * @param string $section
     * @return string
     */
    public function getDescriptionPrompt($section)
    {
        $configDescription = $this->getConfigValue(
            $section.self::DESCRIPTION_PROMPT
        );
        return $configDescription;
    }
    /**
     * Get meta title prompt by section
     *
     * @param string $section
     * @return string
     */
    public function getMetaTitlePrompt($section)
    {
        $configMetaTitle = $this->getConfigValue(
            $section.self::META_TITLE_PROMPT
        );
        return $configMetaTitle;
    }
    /**
     * Get meta keywords prompt by section
     *
     * @param string $section
     * @return string
     */
    public function getMetaKeywordsPrompt($section)
    {
        $configMetaKeywords = $this->getConfigValue(
            $section.self::META_KEYWORDS_PROMPT
        );
        return $configMetaKeywords;
    }

    /**
     * Get meta description prompt by section
     *
     * @param string $section
     * @return string
     */
    public function getMetaDescriptionPrompt($section)
    {
        $configMetaDescription = $this->getConfigValue(
            $section.self::META_DESCRIPTION_PROMPT
        );
        return $configMetaDescription;
    }

    /**
     * Get all tokens length
     *
     * @param string $section
     * @param string $type
     * @return array
     */
    public function getAllMaxTokens($section, $type)
    {
        $fieldMaxToken = [];
        $fieldMaxToken['product']['short_description_button'] = $this->getProductShortDescMaxTokens() ?? "";
        $fieldMaxToken['product']['description_button'] = $this->getProductDescMaxTokens() ?? "";
        $fieldMaxToken['product']['meta_title_button'] = $this->getProductMetaTitleMaxTokens() ?? "";
        $fieldMaxToken['product']['meta_keywords_button'] =  $this->getProductKeywordMaxTokens() ?? "";
        $fieldMaxToken['product']['meta_description_button'] = $this->getProductMetaDescMaxTokens() ?? "";
        $fieldMaxToken['category']['description_button'] = $this->getCategoryDescMaxTokens() ?? "";
        $fieldMaxToken['category']['meta_title_button'] = $this->getCategoryMetaTitleMaxTokens() ?? "";
        $fieldMaxToken['category']['meta_keywords_button'] =  $this->getCategoryKeywordMaxTokens() ?? "";
        $fieldMaxToken['category']['meta_description_button'] = $this->getCategoryMetaDescMaxTokens() ?? "";
        $fieldMaxToken['cms']['description_button'] = $this->getCmsDescMaxTokens() ?? "";
        $fieldMaxToken['cms']['meta_title_button'] = $this->getCmsMetaTitleMaxTokens() ?? "";
        $fieldMaxToken['cms']['meta_keywords_button'] =  $this->getCmsKeywordMaxTokens() ?? "";
        $fieldMaxToken['cms']['meta_description_button'] = $this->getCmsMetaDescMaxTokens() ?? "";
        if (isset($fieldMaxToken[$section][$type])) {
            return $fieldMaxToken[$section][$type];
        }
        return $fieldMaxToken;
    }
    /**
     * Get all prompt content
     *
     * @param string $section
     * @param string $type
     * @return array
     */
    public function getAllPromptContent($section, $type)
    {
        $fieldPrompt = [];
        $productSection     = self::PRODUCT_SECTION;
        $categorySection    = self::CATEGORY_SECTION;
        $cmsSection         = self::CMS_SECTION;
        $fieldPrompt['product']['short_description_button'] = $this->getShortDescriptionPrompt($productSection) ?? "";
        $fieldPrompt['product']['description_button'] = $this->getDescriptionPrompt($productSection) ?? "";
        $fieldPrompt['product']['meta_title_button'] = $this->getMetaTitlePrompt($productSection) ?? "";
        $fieldPrompt['product']['meta_keywords_button'] =  $this->getMetaKeywordsPrompt($productSection) ?? "";
        $fieldPrompt['product']['meta_description_button'] = $this->getMetaDescriptionPrompt($productSection) ?? "";
        $fieldPrompt['category']['description_button'] = $this->getDescriptionPrompt($categorySection) ?? "";
        $fieldPrompt['category']['meta_title_button'] = $this->getMetaTitlePrompt($categorySection) ?? "";
        $fieldPrompt['category']['meta_keywords_button'] =  $this->getMetaKeywordsPrompt($categorySection) ?? "";
        $fieldPrompt['category']['meta_description_button'] = $this->getMetaDescriptionPrompt($categorySection) ?? "";
        $fieldPrompt['cms']['description_button'] = $this->getDescriptionPrompt($cmsSection) ?? "";
        $fieldPrompt['cms']['meta_title_button'] = $this->getMetaTitlePrompt($cmsSection) ?? "";
        $fieldPrompt['cms']['meta_keywords_button'] =  $this->getMetaKeywordsPrompt($cmsSection) ?? "";
        $fieldPrompt['cms']['meta_description_button'] = $this->getMetaDescriptionPrompt($cmsSection) ?? "";
        if (isset($fieldPrompt[$section][$type])) {
            return $fieldPrompt[$section][$type];
        }
        return $fieldPrompt;
    }

    /**
     * Send APi request
     *
     * @param string $title
     * @param string $color
     * @param string $category
     * @param string $section
     * @param string $type
     * @return json
     */
    public function setOpenAIRequest($title, $color, $category, $section, $type)
    {
        $apiKey = $this->getSecretKey();
        $headers = $this->getHeader($apiKey);
        $openaiEndpoint = self::OPENAI_END_POINT;
        $payloadConfig = $this->getPayloadConfig($title, $color, $category, $section, $type);
        $payloads = $this->getPayload($payloadConfig);
        return $this->performRequest($openaiEndpoint, $headers, $payloads);
    }

    /**
     * Get payload cobig
     *
     * @param string $title
     * @param string $color
     * @param string $category
     * @param string $section
     * @param string $type
     * @return array
     */
    public function getPayloadConfig($title, $color, $category, $section, $type)
    {
        $payloadData = [];
        switch ($section) {
            case 'product':
                $payloadData['model'] = $this->getProductModelName();
                $payloadData['temperature'] = $this->getProductTemprature();
                $payloadData['max_tokens'] = $this->getAllMaxTokens($section, $type);
                $payloadData['prompt'] = $this->getAllPromptContent($section, $type);
                $payloadData['title'] = $title;
                $payloadData['color'] = $color;
                $payloadData['category'] = $category;
                break;
            case 'category':
                $payloadData['model'] = $this->getCategoryModelName();
                $payloadData['temperature'] = $this->getCategoryTemprature();
                $payloadData['max_tokens'] = $this->getAllMaxTokens($section, $type);
                $payloadData['prompt'] = $this->getAllPromptContent($section, $type);
                $payloadData['title'] = $title;
                break;
            case 'cms':
                $payloadData['model'] = $this->getCmsModelName();
                $payloadData['temperature'] = $this->getCmsTemprature();
                $payloadData['max_tokens'] = $this->getAllMaxTokens($section, $type);
                $payloadData['prompt'] = $this->getAllPromptContent($section, $type);
                $payloadData['title'] = $title;
                break;
            default:
                $payloadData['model'] = '';
                $payloadData['temperature'] = '';
                $payloadData['max_tokens'] = '';
                $payloadData['prompt'] = '';
                $payloadData['title'] = '';
                break;
        }
   
        return $payloadData;
    }

    /**
     * Perform request
     *
     * @param string $url
     * @param array $headers
     * @param array $postData
     * @return mixed
     */
    public function performRequest($url, $headers = [], $postData = [])
    {
        $data = $this->json->serialize($postData);
        try {
            $this->curl->setHeaders($headers);
            $this->curl->post($url, $data);
            $response = $this->curl->getBody();
            if ($this->curl->getStatus() === 200) {
                return $response;
            } else {
                $errorMessage = "Error: HTTP Status ".$this->curl->getStatus();
            }
        } catch (\Exception $e) {
            $errorMessage = "Exception: ".$e->getMessage();
            throw new Exception($errorMessage);
        }
    }

    /**
     * Get headers
     *
     * @param string $apiKey
     * @return array
     */
    protected function getHeader($apiKey)
    {
        $token = $apiKey;
        if (!$token) {
            throw new InputException(__('Please set API secret key configuration'));
        }
        $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $apiKey
        ];
        return $headers;
    }

    /**
     * Get payload
     *
     * @param array $postData
     * @return array
     */
    protected function getPayload($postData)
    {
        $variables = [
            'name' => $postData['title'],
            'color' => $postData['color'] ?? null,
            'category' => $postData['category'] ?? null
        ];
        $dynamicPrompt = $this->replaceDynamicVar($postData['prompt'], $variables);
        $dynamicPrompt = trim($dynamicPrompt);
        if (!$dynamicPrompt || !$postData['temperature'] || !$postData['max_tokens']) {
            throw new InputException(__('Please set configuration properly'));
        }
        $payload =  [
        "model" => $postData['model'],
        "n" => 1,
        "temperature" => (float) $postData['temperature'],
        "max_tokens" => (int) $postData['max_tokens'],
        "frequency_penalty" => 0,
        "presence_penalty" => 0
        ];
        $payload['messages'] = [
        [
            'role' => 'system',
            'content' => 'You are a helpful assistant.',
        ],
        [
            'role' => 'user',
            'content' => $dynamicPrompt,
        ],
        ];
        return $payload;
    }

    /**
     * Get curl errors
     *
     * @param int $httpCode
     * @return string
     */
    private function curlErrorType($httpCode)
    {
        $responseText = '';
        if ($httpCode==200) {
            return $responseText;
        }
        switch ($httpCode) {
            case 400:
                $responseText = '400: Bad Request';
                break;
            case 401:
                $responseText = '401: Unauthorized';
                break;
            case 403:
                $responseText = '403: Forbidden';
                break;
            case 404:
                $responseText = '404: Request Not Found';
                break;
            case 405:
                $responseText = '405: Method Not Allowed';
                break;
            case 408:
                $responseText = '408: Request Time-out';
                break;
            case 413:
                $responseText = '413: Request Entity Too Large';
                break;
            case 414:
                $responseText = '414: Request-URI Too Large';
                break;
            case 422:
                $responseText = '422: Unprocessable Content';
                break;
            case 500:
                $responseText = '500: Internal Server Error';
                break;
            case 501:
                $responseText = '501: Not Implemented';
                break;
            case 502:
                $responseText = '502: Bad Gateway';
                break;
            case 503:
                $responseText = '503: Service Unavailable';
                break;
            case 504:
                $responseText = '504: Gateway Time-out';
                break;
            default:
                $responseText = $httpCode.': Unknown http status code';
                break;
        }
        return $responseText;
    }

    /**
     * Get json helper
     *
     * @return string
     */
    public function getJsonHelper()
    {
        return $this->jsonHelper;
    }

    /**
     * Replace dynamic variable from string
     *
     * @param string $string
     * @param array $variable
     * @return strung
     */
    public function replaceDynamicVar($string, $variable)
    {
        $placeholders = array_map(function ($placeholder) {
            return strtoupper("{{$placeholder}}");
        }, array_keys($variable));

        return strtr($string, array_combine($placeholders, $variable));
    }
}

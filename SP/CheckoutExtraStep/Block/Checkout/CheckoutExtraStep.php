<?php
declare(strict_types=1);

namespace SP\CheckoutExtrastep\Block\Checkout;


use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Customer\Model\Url;
use Magento\Framework\App\Http\Context;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\Url\Helper\Data;

class CheckoutExtraStep implements LayoutProcessorInterface
{
    /**
     * @var ArrayManager
     */
    private ArrayManager $arrayManager;

    /**
     * @var Context
     */
    private Context $httpContext;

    /**
     * @var string
     */
    protected string $childrenStepsPath = 'components/checkout/children/steps';
    /**
     * @var Url
     */
    private Url $customerUrl;

    /**
     * @var Data
     */
    private Data $coreUrl;
    /**
     * @param ArrayManager $arrayManager
     * @param Context $httpContext
     * @param Url $customerUrl
     * @param Data $coreUrl
     * @param Helper $helper
     */
    public function __construct(
        ArrayManager $arrayManager,
        Context $httpContext,
        Url $customerUrl,
        Data $coreUrl,
      
    ) {
        $this->arrayManager = $arrayManager;
        $this->httpContext = $httpContext;
        $this->customerUrl = $customerUrl;
        $this->coreUrl = $coreUrl;
       
    }

    /**
     * @inheritdoc
     */
    public function process($jsLayout): array
    {
       
        if (!$this->arrayManager->exists($this->childrenStepsPath, $jsLayout)) {
            return $jsLayout;
        }        
        $jsLayout = $this->createLoginStepComponents($jsLayout);


        return $jsLayout;
    }

   

    /**
     * Create login step components
     *
     * @param array $jsLayout
     * @return array
     */
    protected function createLoginStepComponents(array $jsLayout): array
    {       
        return $this->arrayManager->merge(
            $this->childrenStepsPath . '/children',
            $jsLayout,
            [
                'additionalinfo-step' => [
                    'component' => 'uiComponent',
                    'sortOrder' => 0,
                    'children' => [
                        'additionalinfo' => [
                          'component' => 'SP_CheckoutExtraStep/js/view/additionalinfo',
                            'template' => 'SP_CheckoutExtraStep/additionalinfo',                   'sortOrder' => 0,
                            'config' => [
                                'deps' => [
                                    'checkout.sidebar.summary'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
    }

}

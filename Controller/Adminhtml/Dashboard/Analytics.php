<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Controller\Adminhtml\Dashboard;

 use Magento\Backend\App\Action as BackendAction;
 use Magento\Backend\App\Action\Context;
 use Magento\Framework\View\Result\PageFactory;

 /**
  * Class Analytics
  * @package DCKAP\AdvancedSampleOrders\Controller\Adminhtml\Dashboard
  */
class Analytics extends BackendAction
{

    /**
     * ADMIN_RESOURCE
     */
    const ADMIN_RESOURCE = 'DCKAP_AdvancedSampleOrders::dashboard';

    /**
     * PAGE_TITLE
     */
    private $pageTitle = 'Advanced Sample Orders - Sales Analytics (Dashboard)';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context
     * @param PageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Analytics
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page  */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
        $resultPage->addBreadcrumb(__($this->pageTitle), __($this->pageTitle));
        $resultPage->getConfig()->getTitle()->prepend(__($this->pageTitle));

        return $resultPage;
    }

    /**
     * Is the user allowed to view the dashboard
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}

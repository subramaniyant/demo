<?php
/**
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace DCKAP\AdvancedSampleOrders\Block\Adminhtml;

use DCKAP\AdvancedSampleOrders\Model\Analytics as AnalyticsModel;
use DCKAP\AdvancedSampleOrders\Model\ResourceModel\Analytics as AnalyticsResourceModel;

class Analytics extends \Magento\Backend\Block\Template
{
    /**
     * @var AnalyticsResourceModel\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var AnalyticsModel
     */
    private $analyticsModel;

    /**
     * @var AnalyticsResourceModel
     */
    private $resourceModel;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timeZone;

    /**
     * Analytics constructor.
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        AnalyticsModel $analyticsModel,
        AnalyticsResourceModel $resourceModel,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \DCKAP\AdvancedSampleOrders\Model\ResourceModel\Analytics\CollectionFactory $collectionFactory
    ) {
        $this->analyticsModel = $analyticsModel;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->dateTime = $dateTime;
        $this->timeZone = $context->getLocaleDate();
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }

    /**
     * @return array
     */
    public function getTotals()
    {
        $returnVal = [];
        $isamples = $this->getCollection();
        $returnVal[] = $isamples->count();
        $_todate = explode("-", $this->dateTime->date('Y-m-d H:i:s', $this->timeZone->formatDate()));
        $_todate[2] = 1;
        $_today = implode("-", $_todate)." 00:00:00";
        $tempDate = explode("-", explode(" ", $isamples->getFirstItem()->getSampleOrderedDate())[0]);
        $tempDate[2] = 1;
        $oldDate = implode("-", $tempDate)." 00:00:00";
        $totalMonths = (int)abs((strtotime($oldDate) - strtotime($_today))/(60*60*24*30));
        $returnVal[] = $totalMonths ? "from last ".$totalMonths." month(s)" : "from this month";
        return $returnVal;
    }

    /**
     * @return array
     */
    public function getMonthsData()
    {
        $months = [];
        $samplesdata = [];
        $counts = 0;
        for ($i = 0; $i < 12; $i++) {
            $month = date("Y-m-01", strtotime(date('Y-m-01')." -$i months"));
            $months[] = $month;
            $ik = 0;
            $mk = 0;
            $samples = $this->getCollection();
            $samples->addfieldtofilter('sample_ordered_date', ['gteq' => $month]);
            $samples->addfieldtofilter(
                'sample_ordered_date',
                ['to' => date("Y-m-01", strtotime($month." +1 months"))]
            );
            foreach ($samples as $sample) {
                if (in_array(
                    $sample->getOrderStatus(),
                    explode(",", $this->_scopeConfig->getValue("advancedsampleorders/analytics/status"))
                )) {
                    $ik++;
                    if ($sample->getConverted()) {
                        $mk++;
                    }
                }
            }
            $counts = $samples->count();
            $samplesdata[$i] = ["y"=>$month,"a"=>$counts,"b"=>$ik,"c"=>$mk];
        }

        return $samplesdata;
    }

    /**
     * @param string $group
     * @return string
     */
    public function getConversionRates($group)
    {
        $ik=0;
        $mk=0;
        $isamples = $this->getCollection();
        $isamples->addFieldToFilter('order_status', ['neq' => 'Refunded']);

        if ($group == "customer") {
            $isamples->addFieldtoFilter("customer_id", ['neq'=>0]);
        } elseif ($group == "guest") {
            $isamples->addFieldtoFilter("customer_id", 0);
        }

        foreach ($isamples as $sample) {
            $sample->getConverted() ? $ik++ : $mk++;
        }

        $total = $ik + $mk;
        $finalrate = $total ? number_format(($ik / $total) * 100, 2) : "0.00";
        $retval = "<div class='huge-left'>".$ik." <span>/</span> ".$total."</div>
                    <div class='huge'>".$finalrate."<span>%</span></div>";
        return $retval;
    }

    /**
     * @return mixed
     */
    public function getStatusDonut()
    {
        $_statuses = $this->analyticsModel->getStatuses();
        $is=0;
        foreach ($_statuses as $status) {
            $isamples = $this->getCollection();
            $isamples->addFieldtoFilter("order_status", $status);
            $samplesdata[$is]["value"] = $isamples->count() ? $isamples->count() : 0;
            $samplesdata[$is]["label"] = strtoupper($status);
            $is++;
        }

        return $samplesdata;
    }

    /**
     * @return mixed
     */
    public function getGroupDonut()
    {
        $groups = ["CUSTOMER","GUEST"];
        $is=0;
        foreach ($groups as $group) {
            $isamples = $this->getCollection();
            $isamples->addFieldToFilter('order_status', ['neq' => 'Refunded']);
            !$is ? $isamples->addFieldtoFilter("customer_id", ['neq'=>0])
                 : $isamples->addFieldtoFilter("customer_id", 0);
            $samplesdata[$is]["value"] = $isamples->count();
            $samplesdata[$is]["label"] = $group;
            $is++;
        }

        return $samplesdata;
    }

    /**
     * @param string $group
     * @return mixed
     */
    public function getConvertedDonut($group)
    {
        $counters = ["CONVERTED","NOT CONVERTED"];
        $is=0;
        foreach ($counters as $counter) {
            $isamples = $this->getCollection();
            $isamples->addFieldToFilter('order_status', ['neq' => 'Refunded']);
            if ($group == "customer") {
                $isamples->addFieldtoFilter("customer_id", ['neq'=>0]);
            } elseif ($group == "guest") {
                $isamples->addFieldtoFilter("customer_id", 0);
            }

            !$is ? $isamples->addFieldtoFilter("converted", 1) : $isamples->addFieldtoFilter("converted", 0);

            $samplesdata[$is]["value"] = $isamples->count();
            $samplesdata[$is]["label"] = $counter;
            $is++;
        }

        return $samplesdata;
    }
}

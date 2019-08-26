/*
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require([
    "jquery"
], function($){
//<![CDATA[
    $(document).ready(function() {
        $( ".item .item-info .product-item-name a:contains(' ( SAMPLE )')" ).closest(".item-info").find(".col.qty").html("<span style='color: rgb(102, 102, 102); font-weight: 800;'>1</span>");
    });
//]]>
});


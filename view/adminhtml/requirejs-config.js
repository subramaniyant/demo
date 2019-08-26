/*
 * @author     DCKAP
 * @package    DCKAP_AdvancedSampleOrders
 * @copyright  Copyright (c) 2016 DCKAP Inc (http://www.dckap.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

var config = {
    map: {
        '*': {
            prettify: 'DCKAP_AdvancedSampleOrders/js/prettify.min',
            raphael: 'DCKAP_AdvancedSampleOrders/js/raphael-min',
            morris: 'DCKAP_AdvancedSampleOrders/js/morris'
        }
    },
    shim: {
            'morris': {
                deps: ['jquery'] //gives your parent dependencies name here
            }
    }
};
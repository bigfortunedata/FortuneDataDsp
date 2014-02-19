<?php
/**
* OWATracker class file
* 
* @author Shihao Wang
* @version 1.0.0
* @license BSD
* @created 02.18.2014
*/

/**
* =====Yii OWA Tracker widget=====
* 
* ===Usage:===
* Just add the following to the layout:
* <?$this->widget('application.components.widgets..OWATrackerWidget',
* 		array('baseUrl'=>'http://www.www.com','siteId'=>'abcdefg')
* );
* ?>
* 
*/
class OWATrackerWidget extends CWidget {
	
    /**The base url of the owa
    * 
    * @var string
    */
    public $baseUrl;
    
    /** The site id
     * @var string
     */
    public $siteId;

    /** The run function
     */
    public function run() {
        Yii::app()->clientScript->registerScript('OWATracker',
            "
                //<![CDATA[
                var owa_baseUrl = '$this->baseUrl';
                var owa_cmds = owa_cmds || [];
                owa_cmds.push(['setSiteId', '$this->siteId']);
                owa_cmds.push(['trackPageView']);
                owa_cmds.push(['trackClicks']);
                owa_cmds.push(['trackDomStream']);

                (function() {
                    var _owa = document.createElement('script'); _owa.type = 'text/javascript'; _owa.async = true;
                    owa_baseUrl = ('https:' == document.location.protocol ? window.owa_baseSecUrl || owa_baseUrl.replace(/http:/, 'https:') : owa_baseUrl );
                    _owa.src = owa_baseUrl + 'modules/base/js/owa.tracker-combined-min.js';
                    var _owa_s = document.getElementsByTagName('script')[0]; _owa_s.parentNode.insertBefore(_owa, _owa_s);
                }());
                //]]>
            "    
            ,CClientScript::POS_END
        );   
        
    }
}
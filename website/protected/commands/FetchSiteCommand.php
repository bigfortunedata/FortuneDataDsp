<?php

class FetchSiteCommand extends CConsoleCommand {

    private $_siteScoutApi;

    public function run($args) {
        
        //fetch Site information daily
        //run once day, when system time is after 01 AM
        if (date("YmdH") == date("Ymd") . '01') {
            $error_msg = null;
            $batchjoblog = new BatchJobLog;
            $batchjoblog->start_datetime = date("Y-m-d H:i:s");
            $batchjoblog->batch_job_name = 'FetchSiteCommand[Daily]';
            $batchjoblog->save();

            if ($this->_siteScoutApi == null) {
                $this->_siteScoutApi = new CronSiteScoutAPI();
            }
      
  
            $response = $this->_siteScoutApi->getSite();

            $batchjoblog->end_datetime = date("Y-m-d H:i:s");
             if ($response == 'success') {
                $batchjoblog->status = 'success';
             } else {
                 $batchjoblog->status = 'fail';
                $batchjoblog->log = $response . ' batch job failed FetchSiteCommand[Daily], please view error message at fd_cron_error_log table ';
             };

            $batchjoblog->save();
        }
    }

}

?>

<?php

class FetchStatusCommand extends CConsoleCommand {

    private $_siteScoutApi;

    public function run($args) {
        //fetch campaign status 
        $error_msg = null;
        $batchjoblog = new BatchJobLog;
        $batchjoblog->start_datetime = date("Y-m-d H:i:s");
        $batchjoblog->batch_job_name = 'fetchCampaignStatus';
        $batchjoblog->save();

        if ($this->_siteScoutApi == null) {
            $this->_siteScoutApi = new CronSiteScoutAPI();
        }

        $response = $this->_siteScoutApi->fetchCampaignStatus();

        $batchjoblog->end_datetime = date("Y-m-d H:i:s");
        if ($response == 0) {
            $batchjoblog->status = 'success';
        } else {
            $batchjoblog->status = 'fail';
            $batchjoblog->log = $response.' Campaigns failed to get the status, please view error message at fd_cron_error_log table ';
        };

        $batchjoblog->save();

        //fetching creative status

        $error_msg = null;
        $batchjoblog = new BatchJobLog;
        $batchjoblog->start_datetime = date("Y-m-d H:i:s");
        $batchjoblog->batch_job_name = 'fetchCreativeStatus';
        $batchjoblog->save();

        if ($this->_siteScoutApi == null) {
            $this->_siteScoutApi = new CronSiteScoutAPI();
        }

        $response = $this->_siteScoutApi->fetchCreativeStatus();

        $batchjoblog->end_datetime = date("Y-m-d H:i:s");
        if ($response == 0) {
            $batchjoblog->status = 'success';
        } else {
            $batchjoblog->status = 'fail';
            $batchjoblog->log = $response.' Creatives failed to get the status, please view error at fd_cron_error_log table ';
        };

        $batchjoblog->save();

    }

}

?>

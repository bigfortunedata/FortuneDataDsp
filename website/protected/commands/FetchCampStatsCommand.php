<?php

class FetchCampStatsCommand extends CConsoleCommand {

    private $_siteScoutApi;

    public function run($args) {
        //fetch campaign stats hourly 
        $error_msg = null;
        $batchjoblog = new BatchJobLog;
        $batchjoblog->start_datetime = date("Y-m-d H:i:s");
        $batchjoblog->batch_job_name = 'fetchCampStatsCommand[Hourly]';
        $batchjoblog->save();

        if ($this->_siteScoutApi == null) {
            $this->_siteScoutApi = new CronSiteScoutAPI();
        }

        $response = $this->_siteScoutApi->retrieveStatCampSite();

        $batchjoblog->end_datetime = date("Y-m-d H:i:s");
        if ($response == 0) {
            $batchjoblog->status = 'success';
        } else {
            $batchjoblog->status = 'fail';
            $batchjoblog->log = $response . ' batch job failed to get the campaign stats data [Hourly], please view error message at fd_cron_error_log table ';
        };

        $batchjoblog->save();

        //fetch campaign stats daily
        //run once day, when system time is in 01 AM
        if (date("YmdH") == date("Ymd") . '01') {
            $error_msg = null;
            $batchjoblog = new BatchJobLog;
            $batchjoblog->start_datetime = date("Y-m-d H:i:s");
            $batchjoblog->batch_job_name = 'fetchCampStatsCommand[Daily]';
            $batchjoblog->save();

            if ($this->_siteScoutApi == null) {
                $this->_siteScoutApi = new CronSiteScoutAPI();
            }

            $response = $this->_siteScoutApi->retrieveStatCampSite();

            $batchjoblog->end_datetime = date("Y-m-d H:i:s");
            if ($response == 0) {
                $batchjoblog->status = 'success';
            } else {
                $batchjoblog->status = 'fail';
                $batchjoblog->log = $response . ' batch job failed to get the campaign stats data [Daily], please view error message at fd_cron_error_log table ';
            };

            $batchjoblog->save();
        }
    }

}

?>

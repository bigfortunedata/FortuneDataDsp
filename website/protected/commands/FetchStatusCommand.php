<?php

class FetchStatusCommand extends CConsoleCommand {

    private $_siteScoutApi;

    public function run($args) {
        $error_msg = null;
        $batchjoblog = new BatchJobLog;
        $batchjoblog->start_datetime = date("Y-m-d H:i:s");
        $batchjoblog->batch_job_name = 'FetchStatusCommand';
        $batchjoblog->save();

        if ($this->_siteScoutApi == null) {
            $this->_siteScoutApi = new CronSiteScoutAPI();
        }

        $response = $this->_siteScoutApi->fetchCampaignStatus();

        if (isset($response->errorCode)) {
            $error_msg = 'fetchCampaignStatus failed, error message : ' . $response->errorCode . '  -  ' . $response->message;
        }


        $response = $this->_siteScoutApi->fetchCreativeStatus();

        if (isset($response->errorCode)) {
            $error_msg = $error_msg . ' fetchCreativeStatus failed, error message : ' . $response->errorCode . '  -  ' . $response->message;
        }

        $batchjoblog->end_datetime = date("Y-m-d H:i:s");
        if ($error_msg == null) {
            $batchjoblog->status = 'success';
        } else {
            $batchjoblog->status = 'fail';
        };
        $batchjoblog->log = $error_msg;
        $batchjoblog->save();

        //var_dump($response);
    }

}

?>

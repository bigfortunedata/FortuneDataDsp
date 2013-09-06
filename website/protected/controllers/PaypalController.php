<?php

class PaypalController extends Controller
{
    
        public $layout='//layouts/column2';
        
	public function actionBuy( ){
                 
		// set
		$paymentInfo['Order']['theTotal'] = Yii::app()->user->getState('orderamt');
		$paymentInfo['Order']['description'] = Yii::app()->user->getState('ordernote');
		$paymentInfo['Order']['quantity'] = 1;

		// call paypal
		$result = Yii::app()->Paypal->SetExpressCheckout($paymentInfo);
		//Detect Errors
		if(!Yii::app()->Paypal->isCallSucceeded($result)){
			if(Yii::app()->Paypal->apiLive === true){
				//Live mode basic error message
				$error = 'We were unable to process your request. Please try again later';
			}else{
				//Sandbox output the actual error message to dive in.
				$error = $result['L_LONGMESSAGE0'];
			}
			echo $error;
			Yii::app()->end();

		}else {
			// send user to paypal
			$token = urldecode($result["TOKEN"]);

			$payPalURL = Yii::app()->Paypal->paypalUrl.$token;
			$this->redirect($payPalURL);
		}
	}

	public function actionConfirm()
	{
		$token = trim($_GET['token']);
		$payerId = trim($_GET['PayerID']);
                



		$result = Yii::app()->Paypal->GetExpressCheckoutDetails($token);

		$result['PAYERID'] = $payerId;
		$result['TOKEN'] = $token;
		$result['ORDERTOTAL'] = Yii::app()->user->getState('orderamt');
             
                
		//Detect errors
		if(!Yii::app()->Paypal->isCallSucceeded($result)){
			if(Yii::app()->Paypal->apiLive === true){
				//Live mode basic error message
				$error = 'We were unable to process your request. Please try again later';
			}else{
				//Sandbox output the actual error message to dive in.
				$error = $result['L_LONGMESSAGE0'];
			}
			echo $error;
			Yii::app()->end();
		}else{

			$paymentResult = Yii::app()->Paypal->DoExpressCheckoutPayment($result);
			//Detect errors
			if(!Yii::app()->Paypal->isCallSucceeded($paymentResult)){
				if(Yii::app()->Paypal->apiLive === true){
					//Live mode basic error message
					$error = 'We were unable to process your request. Please try again later';
				}else{
					//Sandbox output the actual error message to dive in.
					$error = $paymentResult['L_LONGMESSAGE0'];
				}
				echo $error;
				Yii::app()->end();
			}else{
				//payment was completed successfully


                    ClientPayment::model()->updateByPk( Yii::app()->user->getState('paymentid'),array('status'=>'SUCCESS') );
                    Yii::app()->user->setState('orderamt',null);
                    Yii::app()->user->setState('ordernote',null);
                    Yii::app()->user->setState('paymentid',null);

                    $this->render('confirm');
			}

		}
	}

    public function actionCancel()
	{
		//The token of the cancelled payment typically used to cancel the payment within your application
		$token = $_GET['token'];

                ClientPayment::model()->updateByPk ( Yii::app()->user->getState('paymentid'),array('status'=>'FAIL') );
                Yii::app()->user->setState('orderamt',null);
                Yii::app()->user->setState('ordernote',null);
                Yii::app()->user->setState('paymentid',null);

	 	$this->render('cancel');
	}

	public function actionDirectPayment(){
		$paymentInfo = array('Member'=>
			array(
				'first_name'=>'name_here',
				'last_name'=>'lastName_here',
				'billing_address'=>'address_here',
				'billing_address2'=>'address2_here',
				'billing_country'=>'country_here',
				'billing_city'=>'city_here',
				'billing_state'=>'state_here',
				'billing_zip'=>'zip_here'
			),
			'CreditCard'=>
			array(
				'card_number'=>'number_here',
				'expiration_month'=>'month_here',
				'expiration_year'=>'year_here',
				'cv_code'=>'code_here'
			),
			'Order'=>
			array('theTotal'=>1.00)
		);

	   /*
		* On Success, $result contains [AMT] [CURRENCYCODE] [AVSCODE] [CVV2MATCH]
		* [TRANSACTIONID] [TIMESTAMP] [CORRELATIONID] [ACK] [VERSION] [BUILD]
		*
		* On Fail, $ result contains [AMT] [CURRENCYCODE] [TIMESTAMP] [CORRELATIONID]
		* [ACK] [VERSION] [BUILD] [L_ERRORCODE0] [L_SHORTMESSAGE0] [L_LONGMESSAGE0]
		* [L_SEVERITYCODE0]
		*/

		$result = Yii::app()->Paypal->DoDirectPayment($paymentInfo);

		//Detect Errors
		if(!Yii::app()->Paypal->isCallSucceeded($result)){
			if(Yii::app()->Paypal->apiLive === true){
				//Live mode basic error message
				$error = 'We were unable to process your request. Please try again later';
			}else{
				//Sandbox output the actual error message to dive in.
				$error = $result['L_LONGMESSAGE0'];
			}
			echo $error;

		}else {
			//Payment was completed successfully, do the rest of your stuff
		}

		Yii::app()->end();
	}
}
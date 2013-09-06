<?php

$this->breadcrumbs = array(
    'Client Payments' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List ClientPayment', 'url' => array('index')),
    array('label' => 'Manage ClientPayment', 'url' => array('admin')),
);
?>


<table border="0" >
    <tr>
        <td ><h3>Purchase Credit</h3></td>
        <td><a href="#" onclick="javascript:window.open('https://www.paypal.com/ca/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside', 'olcwhatispaypal', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=350');"><img  src="https://www.paypal.com/en_US/i/bnr/horizontal_solution_PPeCheck.gif" border="0" alt="Solution Graphics"></a>
        </td>
    </tr>

</table>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
var base_url = $("#paypal-js").data("base-url");
var payment_type = $("#paypal-js").data("payment-type");

if(payment_type != "proposal" && payment_type != "cart"){
    var btn_height = 33;
}else{
    var btn_height = 38;
}

if(typeof formData == 'undefined'){ 
    var formData = new FormData();
}

formData.append('paypal', 1);
formData.append('type',payment_type);

// Render the PayPal button into #paypal-button-container
paypal.Buttons({

    style: {
        color:  'blue',
        shape:  'rect',
        label:  'pay',
        height: btn_height
    },

    // Call your server to set up the transaction
    createOrder: function(data, actions) {
        return fetch(base_url+'/paypal_charge', {
            method: 'post',
            body:formData,
        }).then(function(res) {
            return res.json();
        }).then(function(orderData) {
            return orderData.id;
        });

        // $.ajax({
        //     method: "POST",
        //     url: base_url+"/paypal_charge",
        //     data: {paypal:1,type : 'paypal_charge'}
        // }).done(function(res){
        //   return res.json();
        // });

    },


    // Show a cancel page, or return to cart
    onCancel: function (data) {

        console.log("Cancel Order Id: "+data.orderID);

        return fetch(base_url+'/cancel_payment?reference_no='+data.orderID,{
            method: 'post'
        }).then(function(orderData) {
            console.log("successfully canceled.");
        });

    },

    // Call your server to finalize the transaction
    onApprove: function(data, actions) {
        
        $('#wait').addClass("loader");

        return fetch(base_url+'/paypal_capture?order_id='+data.orderID,{
            method: 'post'
        }).then(function(res) {
            return res.json();
        }).then(function(orderData) {
            
            $('#wait').removeClass("loader");

            // Three cases to handle:
            //   (1) Recoverable INSTRUMENT_DECLINED -> call actions.restart()
            //   (2) Other non-recoverable errors -> Show a failure message
            //   (3) Successful transaction -> Show a success / thank you message

            // Your server defines the structure of 'orderData', which may differ
            var errorDetail = Array.isArray(orderData.details) && orderData.details[0];

            if (errorDetail && errorDetail.issue === 'INSTRUMENT_DECLINED') {
                // Recoverable state, see: "Handle Funding Failures"
                // https://developer.paypal.com/docs/checkout/integration-features/funding-failure/
                return actions.restart();
            }

            if (errorDetail) {
                var msg = 'Sorry, your transaction could not be processed.';
                if (errorDetail.description) msg += '\n\n' + errorDetail.description;
                if (orderData.debug_id) msg += ' (' + orderData.debug_id + ')';
                // Show a failure message
                return alert(msg);
            }

            // Show a success message to the buyer

            alert('Transaction Successfully Completed Now Redirecting.');
            
            if(payment_type == 'orderTip'){
                window.open(base_url+'/orderIncludes/charge/order/paypal?reference_no='+orderData.id+'&orderTip=1','_self');
            }else if(payment_type == 'orderExtendTime'){
                window.open(base_url+'/plugins/videoPlugin/extendTime/charge/order/paypal?reference_no='+orderData.id+'&extendTime=1','_blank');
            }else{
                window.open(base_url+'/paypal_order?reference_no='+orderData.id,'_self');
            }

        });
    }

}).render('.paypal-button-container');
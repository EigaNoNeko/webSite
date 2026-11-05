const CustomPaypal = (shoppingCart, aproveCallback, cancelCallback, errCallback, buttonID) => {
	paypal.Buttons({
		createOrder: function(data, actions) {
			return fetch('/api/modulPaypal?action=checkout&shoppingCart='+shoppingCart, {
				method: 'post'
			}).then(function(res) {
				return res.json();
			}).then(function(orderData) {
				return orderData.id;
			});
		},
		onApprove: function(data, actions) {
			return fetch("/api/modulPaypal?action=capture&orderId=" + data.orderID, {
				method: "post"
			}).then(function(res) {
				return res.json();
			}).then(function(details) {
				console.log(details)
				aproveCallback(details)
			});
		},
		onCancel: function(data) {
			alert("Paiement PayPal annulé ❌");
			cancelCallback(data)
		},
		onError: function(err) {
			console.error(err);
			errCallback(err)
		}
	}).render(buttonID);
}
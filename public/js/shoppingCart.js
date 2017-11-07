var Cart = function (order_type, rentalArray, pudArray) 
{
	this.items = [];
	this.itemCounter = 1;
	this.total = 0.00;
	this.monthly_total = 0.00;
	this.total_before_tax = 0.00;
	this.tax = 0.00;
	this.tax_rate = 0.00;
	this.postList = [];
	this.tax_rate_updated = false;
	this.order_type = order_type;
	this.delivery_cost = 0.00;
	this.total_delivery_cost = 0.00;
	this.rentalArray = rentalArray;
	this.pudArray = pudArray;

	this.addItem = function (product, quantity)
	{   

		if(this.tax_rate_updated == false)
		{
			alert("Please enter a tax rate.");
		} else {
			// Add the product to the items array.
			product.qty = quantity;
			
			this.items.push([product, quantity, this.itemCounter]);

			// Add product cost to the total. If in rental array, add it to monthly instead.
			if($.inArray(product.msn, this.pudArray) == -1)
			{
				if(this.order_type == 'rental' || this.order_type == 'Rental')
				{
					if($.inArray(product.msn, this.rentalArray) != -1){
	
						this.monthly_total = round(this.monthly_total + (product.cost * quantity), 2);
						this.total_before_tax = round(this.total_before_tax + (product.cost * quantity), 2);
					} else {
						this.total_before_tax = round(this.total_before_tax + (product.cost * quantity), 2);
					}
				} else {
					this.total_before_tax = round(this.total_before_tax + (product.cost * quantity), 2);
				}
			} else {
				this.delivery_cost = round(this.delivery_cost + (product.cost * quantity), 2);
			}

			
			// Calculate tax.
			this.calculateTax();

			// Add delivery costs to total.
			this.calculateDelivery();

			// Update the cart view.
			this.updateCart();

			if(product.showAlert == true){
				// Alert the user an item has been added.
				this.alert(product, 'added');
			}
		
			// Update the item counter.
			this.itemCounter++;
		}

	}

	this.calculateTax = function ()
	{
		this.tax = 0.00;
		this.tax = round((this.total_before_tax * this.tax_rate), 2);
		this.addTax();
	}

	this.addTax = function ()
	{	
		this.total = 0.00;
		this.total += round((this.tax + this.total_before_tax), 2);
	}

	this.calculateDelivery = function ()
	{
		this.total_delivery_cost = 0.00;
		this.total_delivery_cost += this.delivery_cost;
		this.total += this.total_delivery_cost;
	}

	this.removeItem = function (itemNumber)
	{
		// Iterate through the items in the items array.
		for(var i = 0; i < this.items.length; i++)
		{	
			// Find out which item it is that we are deleting and delete it.
			if(this.items[i][2] == itemNumber)
			{	
				// Remove item cost from total.
				for(var j = 0; j < this.items[i][1]; j++)
				{
					if($.inArray(this.items[i][0].msn, this.pudArray) == -1)
					{
						if($.inArray(this.items[i][0].msn, this.rentalArray) != -1)
						{
							this.monthly_total = round((this.monthly_total - this.items[i][0].cost), 2);
							this.total_before_tax = round((this.total_before_tax - this.items[i][0].cost), 2);
						} else {
							this.total_before_tax = round((this.total_before_tax - this.items[i][0].cost), 2);
						}
					} else {
						this.delivery_cost = round((this.delivery_cost - this.items[i][0].cost), 2);
					}
				}
				this.calculateTax();
				this.calculateDelivery();
				this.alert(this.items[i][0], 'removed');
				this.items.splice(i,1);
			}
		}
		this.itemCounter -= 1;
		this.updateCart();
	}

	this.updateCart = function ()
	{
		var cartTable = '<table class="table table-hover">';
		cartTable+= '<thead><th>Item Name</th><th>Cost</th><th>Monthly Cost</th><th>Quantity</th><th>Total Cost</th><th></th></tr></thead>';
		cartTable+= '<tbody>';

		for(var i = 0; i < this.items.length; i++)
		{
			cartTable+= '<tr><td width="250" id="prod">'+cart.items[i][0].mod_name+'</td>';
			if(this.order_type == 'rental' || this.order_type == 'Rental') {
				console.log(cart.items[i][0].msn);
				if($.inArray(cart.items[i][0].msn, this.rentalArray) != -1)	{
					cartTable+= '<td width="250" id="prodCost">$0</td>';
					cartTable+= '<td width="250" id="prodMonthlyCost">$'+cart.items[i][0].cost+'</td>';
				} else {
					cartTable+= '<td width="250" id="prodCost">$'+round(cart.items[i][0].cost, 2)+'</td>';
					cartTable+= '<td width="250" id="prodMonthlyCost">$0</td>';
				}
			} else {
				cartTable+= '<td width="250" id="prodCost">$'+round(cart.items[i][0].cost, 2)+'</td>';
				cartTable+= '<td width="250" id="prodMonthlyCost">$0</td>';
			}
			cartTable+= '<td width="250" id="prodCost">'+cart.items[i][1]+'</td>';
			cartTable+= '<td width="250" id="itemTotal">$'+round(cart.items[i][0].cost*cart.items[i][1], 2)+'</td>';
			cartTable+= '<td width="50"><button type="button" class="btn btn-gbr" onclick="cart.removeItem('+cart.items[i][2]+');"><span class="glyphicon glyphicon-trash"></span></button></td></tr>';
		}
		cartTable+= '<tr><td width="250"></td><td width="250"></td></td><td width="250"></td><td width="250">Container Cost:</td><td width="250"><strong>$'+cart.total_before_tax+'</strong></td><td></td>';
		cartTable+='<tr><td width="250"></td><td width="250"></td></td><td width="250"></td><td width="250">Delivery Cost:</td><td width="250"><strong>$'+cart.delivery_cost+'</strong></td><td></td>';
		cartTable+='<tr><td width="250"></td><td width="250"></td></td><td width="250"></td><td width="250">Monthly Cost:</td><td width="250"><strong>$'+cart.monthly_total+'</strong></td><td></td>';
		cartTable+='<tr><td width="250"></td><td width="250"></td></td><td width="250"></td><td width="250">Total tax:</td><td width="250"><strong>$'+cart.tax+'</strong></td><td></td>';
		cartTable+='<tr><td width="250"></td><td width="250"></td></td><td width="250"></td><td width="250">Total after taxes:</td><td width="250"><strong>$'+cart.total+'</strong></td><td></td>';
		cartTable+= '</tr></tbody></table>';
		$('#cart').html(cartTable);                  
	}

	this.alert = function (product, action)
	{
		modalHtml = '<div class="modal fade" id="alertModal" tabindex="-1" role="dialog">';
		modalHtml += '<div class="modal-dialog" role="document">';
		modalHtml += '<div class="modal-content">';
		modalHtml += '<div class="modal-header">';
		modalHtml += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		modalHtml += '<h4 class="modal-title" style="text-align:center;">Item '+ action +'!</h4>';
		modalHtml += '</div>';
		modalHtml += '<div class="modal-body"  style="text-align:center;">';
		modalHtml += '<p>' + product.mod_name + ' has been '+ action +' to the cart.</p>';
		modalHtml += '</div>';
		modalHtml += '<div class="modal-footer">';
		modalHtml += '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
		modalHtml += '</div>';
		modalHtml += '</div><!-- /.modal-content -->';
		modalHtml += 	'</div><!-- /.modal-dialog -->';
		modalHtml += '</div><!-- /.modal -->';
		$('#insertAlert').html(modalHtml);
		$('#alertModal').modal();
	}

	this.postData = function ()
	{
		// var cartData = '<select name="cartData[]" style="display:none;" multiple="multiple" tabindex="1">';
		
		if(1 > this.items.length){
			alert('You have no items in the cart!');
		} else {
			var cartData = '<input type="hidden" name="cartTotalCost" value="'+ this.total +'">';
			cartData += '<input type="hidden" name="cartMonthlyTotal" value="'+ this.monthly_total +'">';
			cartData += '<input type="hidden" name="cartDeliveryTotal" value="'+ this.delivery_cost +'">';
			cartData += '<input type="hidden" name="cartTax" value="'+ this.tax +'">';
			cartData += '<input type="hidden" name="cartBeforeTaxCost" value="'+ this.total_before_tax +'">';
			cartData += '<input type="hidden" name="itemCount" value="'+ this.items.length +'">';
			for(var i = 0; i < this.items.length; i++)
			{
				// cartData += '<option data-value="{&quotid&quot:' + this.items[i][0].id + ',&quotmod_name&quot:&quot'+ this.items[i][0].mod_name +'&quot,&quotmsn&quot:&quot'+ this.items[i][0].msn +'&quot,&quotcost&quot:'+ this.items[i][0].cost +',&quotrental_type&quot:&quot'+ this.items[i][0].status +'&quot}" selected>' + this.items[i][0].mod_name + '</option>';
				// cartData += '<option data-value="' + this.items[i][0] + '</option>';
				var prod = JSON.stringify(this.items[i][0]);
				cartData += "<input type='hidden' name='product"+i+"' value='" + prod + "'>";
			}
			$('#insertCartData').html(cartData);
			console.log('Cart Data inserted.');

			document.getElementById("orderForm").submit();
		}

		
	}

	this.getTaxRate = function (value)
	{
		if(value == 0)
		{
			alert("Please enter a tax rate above!");
			return false;
		} else
		{
			this.tax_rate = value;
			this.tax_rate_updated = true;
			return true;
		}
	}

}

var Product = function (id, mod_name, msn, cost, status, showAlert)
{
	this.id = id;
	this.mod_name = mod_name;
	this.msn = msn;
	this.cost = cost;
	// Rental or Sales?
	this.status = status;
	this.qty = 0;
	this.showAlert = showAlert || true;
}

function round(value, decimals) 
{
	return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}


cart = new Cart(cart_order_type, rentalArray, pudArray);
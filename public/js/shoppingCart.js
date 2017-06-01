// This will be the shopping cart module for the order pages.
var Cart = function () 
{
	this.items = [];
	this.itemCounter = 1;
	this.total = 0.00;
	this.total_before_tax = 0.00;
	this.tax = 0.00;
	this.tax_rate = 0.00;
	this.postList = [];
	this.tax_rate_updated = false;

	this.addItem = function (product, quantity)
	{   
		
		if(this.tax_rate_updated == false)
		{
			alert("Please enter a tax rate.");
		} else {
			// Add the product to the items array.
			this.items.push([product, quantity, this.itemCounter]);

			// Add product cost to the total.
			this.total_before_tax += (product.cost * quantity);

			// Calculate tax.
			this.calculateTax();

			// Update the cart view.
			this.updateCart();

			// Alert the user an item has been added.
			this.alert(product, 'added');

			// Update the item counter.
			this.itemCounter++;
		}

	}

	this.calculateTax = function ()
	{
		this.tax = 0.00;
		this.tax = this.total_before_tax * this.tax_rate;
		this.addTax();
	}

	this.addTax = function ()
	{	
		this.total = 0.00;
		this.total += (this.tax + this.total_before_tax);
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
					this.total_before_tax -= this.items[i][0].cost;
				}
				this.calculateTax();
				this.alert(this.items[i][0], 'removed');
				this.items.splice(i,1);
				
			} else {
				console.log('No itemNumbers matched in ShoppingCart.js in removeItem function.');
			}
		}
		this.itemCounter -= 1;
		this.updateCart();
	}

	this.updateCart = function ()
	{
		var cartTable = '<table class="table table-hover">';
		cartTable+= '<thead><th>Item Name</th><th>Cost</th><th>Quantity</th><th>Total Cost</th><th></th></tr></thead>';
		cartTable+= '<tbody>';

		for(var i = 0; i < this.items.length; i++)
		{
			cartTable+= '<tr><td width="250" id="prod">'+cart.items[i][0].mod_name+'</td>';
			cartTable+= '<td width="250" id="prodCost">$'+cart.items[i][0].cost+'</td>';
			cartTable+= '<td width="250" id="prodCost">'+cart.items[i][1]+'</td>';
			cartTable+= '<td width="250" id="itemTotal">$'+cart.items[i][0].cost*cart.items[i][1]+'</td>';
			cartTable+= '<td width="50"><button type="button" class="btn btn-gbr" onclick="cart.removeItem('+cart.items[i][2]+');"><span class="glyphicon glyphicon-trash"></span></button></td></tr>';
		}
		cartTable+= '<tr><td width="250"></td><td width="250"></td><td width="250">Total cost before tax:</td><td width="250"><strong>$'+cart.total_before_tax+'</strong></td><td></td>';
		cartTable+='<tr><td width="250"></td><td width="250"></td><td width="250">Total tax:</td><td width="250"><strong>$'+cart.tax+'</strong></td><td></td>';
		cartTable+='<tr><td width="250"></td><td width="250"></td><td width="250">Total after taxes::</td><td width="250"><strong>$'+cart.total+'</strong></td><td></td>';
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
		var cartData = '<select name="cartData[]" style="display:none;" multiple="multiple" tabindex="1">';
		
		for(var i = 0; i < this.items.length; i++)
		{
			cartData += '<option value="' + this.items[i][0].id + '" selected>' + this.items[i][0].mod_name + '</option>';
		}

		cartData += '</select>';
		cartData += '<input type="text" type="hidden" name="cartTotalCost" value="'+ this.total +'">';
		cartData += '<input type="text" type="hidden" name="cartTax" value="'+ this.tax +'">';
		cartData += '<input type="text" type="hidden" name="cartBeforeTaxCost" value="'+ this.total_before_tax +'">';
		$('#insertCartData').html(cartData);
		console.log('Cart Data inserted.');

		document.getElementById("orderForm").submit();
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

var Product = function (id, mod_name, msn, cost, status)
{
	this.id = id;
	this.mod_name = mod_name;
	this.msn = msn;
	this.cost = cost;
	// Rental or Sales?
	this.status = status;
}

cart = new Cart();
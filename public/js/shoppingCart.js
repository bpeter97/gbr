// This will be the shopping cart module for the order pages.
var Cart = function () 
{
    this.items = [];
    this.total = 0.00;
    this.total_before_tax = 0;
    this.tax = 0;

    this.addItem = function (product, quantity)
    {   
        this.items.push([product, quantity]);
        this.total = this.total + (product.cost * quantity);
        console.log('Item has been added!');
        this.updateCart();
    }

    this.updateCart = function ()
    {
        var cartTable = '<table class="table table-hover">';
        cartTable+= '<thead><th>Item Name</th><th>Cost</th><th>Quantity</th><th>Total Cost</th></tr></thead>';
        cartTable+= '<tbody>';

        for(var i = 0; i < cart.items.length; i++)
        {
            cartTable+= '<tr><td width="250" id="prod">'+cart.items[i][0].mod_name+'</td>';
            cartTable+= '<td width="250" id="prodCost">$'+cart.items[i][0].cost+'</td>';
            cartTable+= '<td width="250" id="prodCost">'+cart.items[i][1]+'</td>';
            cartTable+= '<td width="250" id="itemTotal">$'+cart.items[i][0].cost*cart.items[i][1]+'</td></tr>';
        }                     
        cartTable+= '<tr><td width="250"></td><td width="250"></td><td width="250"></td><td width="250"><strong>$'+cart.total+'</strong></td>';
        cartTable+= '</tr></tbody></table>';
        $('#cart').html(cartTable);                  
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
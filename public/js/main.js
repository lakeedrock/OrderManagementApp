$(document).ready(function(){

  $("#data_table").DataTable();

  $("#products").change(function(){
    var obj = getProductJSON();
    //Setting Available Quantity
    $("#qty_available").html("Available : "+obj.qty);
    //Set Unit Price  € 0.00
    $("#unit_price").html('Unit Price € : '+parseFloat(obj.unit_price).toFixed(2));
    //Update Stock acording to product id
    $("#qty").attr("max",obj.qty);
    //Setting Quantity to 0 for new Product
    $("#qty").val(0);
    //Setting Price to 0.00
    $("#total_amount").html('0.00');
    //Setting Discount to 0%
    $("#discount").html('Discount : 0 %');
    //Setting Discount to € 0.00
    $("#discounted").html('Saved € : 0.00');

  });

  $("#qty").change(function() {
    quantityChnage();
  });


  $("#qty_update").change(function() {
    updateQtyChange();
  });

  function getProductJSON() {
    var vals = $("#products").val();
    var obj = JSON.parse(vals);
    return obj;
  }

  $("#order_form").submit(function(e){
    var qty = $("#qty").val();
    if (qty <= 0) {
      e.preventDefault();
      alert("No order quantity selected!");
    }

  });

  $("#order_form_edit").submit(function(e){
    var qty = $("#qty_update").val();
    if (qty <= 0) {
      e.preventDefault();
      alert("No order quantity selected!");
    }

  });

  $("#data_table #delete").click(function(e){
    if(!confirm('Do you want to delete this order?')){
        e.preventDefault();
        return false;
    }
    return true;
  });

  function quantityChnage() {
    var qty = $("#qty").val();
    var productObj = getProductJSON();
    var availableQty = productObj.qty;
    var balanceQty = parseInt(availableQty) - parseInt(qty);
    $("#qty_available").html("Available : "+balanceQty);

    //Update The Price
    var totalPrice = parseFloat(productObj.unit_price)*parseInt(qty);
    var discount = parseInt(productObj.discount);

    //Discount condition
    var discountApplied = 0;
    if (productObj.product_id == 3  && qty < 3) {
      discountApplied = 0;
      console.log('Pepsi Cola No Discount');
    }else{
      discountApplied = productObj.discount;
      console.log('Pepsi Cola Discount Applied');
    }

    var discountAmount = (totalPrice / 100) * parseInt(discountApplied);
    var discountedAmount = totalPrice - [(totalPrice / 100) * parseInt(discountApplied)];
    $("#total_amount").html(discountedAmount.toFixed(2));
    var appliedDiscount = (discountApplied !=0) ? discountApplied : " 0 ";
    console.log(appliedDiscount);
    $("#discount").html('Discount : '+appliedDiscount+' %');
    $("#discounted").html('Saved € : '+discountAmount.toFixed(2));

    //Binding values to the hidden field
    var jsonString = '{"total_amount":'+discountedAmount.toFixed(2)+',"discount":'+discountApplied+',"discount_amount":'+discountAmount.toFixed(2)+',"available_qty":'+balanceQty+'}';
    //console.log(jsonString);
    $("#order_data_array").val(jsonString);
  }

  function updateQtyChange() {
    var qty = $("#qty_update").val();
    var productObj = getProductJSON();
    var availableQty = productObj.qty;
    var balanceQty = parseInt(availableQty) - parseInt(qty);
    $("#qty_available").html("Available : "+balanceQty);

    //Update The Price
    var totalPrice = parseFloat(productObj.unit_price)*parseInt(qty);
    var discount = parseInt(productObj.discount);

    //Discount condition
    var discountApplied = 0;
    if (productObj.product_id == 3  && qty < 3) {
      discountApplied = 0;
    }else{
      discountApplied = productObj.discount;
    }

    var discountAmount = (totalPrice / 100) * parseInt(discountApplied);
    var discountedAmount = totalPrice - [(totalPrice / 100) * parseInt(discountApplied)];
    $("#total_amount").html(discountedAmount.toFixed(2));
    var appliedDiscount = (discountApplied !=0) ? discountApplied : " 0 ";
    $("#discount").html('Discount : '+appliedDiscount+' %');
    $("#discounted").html('Saved € : '+discountAmount.toFixed(2));

    //Binding values to the hidden field
    var jsonString = '{"total_amount":'+discountedAmount.toFixed(2)+',"discount":'+discountApplied+',"discount_amount":'+discountAmount.toFixed(2)+',"available_qty":'+balanceQty+'}';
    //console.log(jsonString);
    $("#order_data_array").val(jsonString);
  }

});

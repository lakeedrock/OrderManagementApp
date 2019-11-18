<?php
  require APP_URL.'/views/inc/header.php';
  $updateAvailable = array();
?>
<div class="jumbotron jumbotron-fluid mt-5">
  <div class="container"><h4 class="display-5 ml-5">Edit order</h4>
  <hr class="my-4">
  <form id="order_form_edit" action="<?php echo BASE_URL; ?>orders/eidtorderupdate" method="post">
    <div class="container-fluid text-center">
      <div class="form-group row">
        <label for="user" class="col-2">User</label>
        <div class="col-6">
          <select id="users" name="user" class="form-control">
            <?php

            foreach ($data['users'] as $users) {
              if ($users->user_id == $data['order_data']->user_id) {
                $updateAvailable['user_name'] = $users->name;
              }
            }
            ?>
            <option value="<?php echo $data['order_data']->user_id; ?>" selected ><?php echo $updateAvailable['user_name']; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="product" class="col-2">Products</label>
        <div class="col-6">
          <select id="products" name="product" class="form-control">
            <?php

            foreach ($data['products'] as $products) {

              if ($products->product_id == $data['order_data']->product_id) {
                $myJObj = new stdClass();
                $myJObj->order_id = $data['order_data']->order_id;
                $myJObj->product_id = $products->product_id;
                $myJObj->qty = $products->qty + $data['order_data']->qty;
                $myJObj->unit_price = $products->unit_price;
                $myJObj->discount = $products->discount;
                $updateAvailable['product_json'] = json_encode($myJObj);

                $updateAvailable['unit_price'] = $products->unit_price;
                $updateAvailable['qty'] = $products->qty + $data['order_data']->qty;
                $updateAvailable['available_qty'] =  $products->qty;
                $updateAvailable['product_name'] = $products->product_name;
              }
            }?>
            <option value='<?php echo $updateAvailable['product_json']; ?>' selected ><?php echo $updateAvailable['product_name']; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="quantity" class="col-2">Quantity</label>
        <div class="col-6">
          <div class="input-group mb-3">
            <input type="number" id="qty_update" name="qty" value="<?php echo $data['order_data']->qty; ?>" class="form-control" onkeydown="return false" min="0"  max="<?php echo $data['products'][0]->qty; ?>">
            <div class="input-group-append">
              <span id="unit_price" class="input-group-text">Unit Price € : <?php echo number_format($updateAvailable['unit_price'],2); ?></span>
              <span id="qty_available" class="input-group-text">Available : <?php echo $updateAvailable['qty']; ?></span>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label for="quantity" class="col-2">Amount</label>
        <div class="col-6 text-left">
          <div class="input-group mb-3">
            <input id="amount" type="text" class="form-control" aria-describedby="basic-addon2" readonly>
            <div class="input-group-prepend">
              <span id="discounted" class="input-group-text">Saved € : <?php echo number_format($data['order_data']->discounted_amount,2);?></span>
              <span id="discount" class="input-group-text">Discount : <?php echo $data['order_data']->discount;?> %</span>
              <span class="input-group-text">€</span>
              <span id="total_amount" class="input-group-text"><?php echo number_format($data['order_data']->total,2); ?></span>
            </div>
            <?php
            $myJObj = new stdClass();
            $myJObj->order_id = $data['order_data']->order_id;
            $myJObj->total_amount = $data['order_data']->total;
            $myJObj->discount = $data['order_data']->discount;
            $myJObj->discount_amount = $data['order_data']->discounted_amount;
            $myJObj->available_qty = $updateAvailable['available_qty'];
            $jsonSring = json_encode($myJObj);
            ?>

             <input type="hidden" id="order_data_array" name="order_data_array" value='<?php echo $jsonSring; ?>'>
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <hr class="my-4">
  <?php flash('order_error'); ?>
  <?php flash('order_success'); ?>
  </div>
</div>
<?php
  require APP_URL.'/views/inc/footer.php';
?>

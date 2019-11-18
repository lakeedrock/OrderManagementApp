<?php
  require APP_URL.'/views/inc/header.php';

?>
<div class="jumbotron jumbotron-fluid mt-5">
  <div class="container"><h4 class="display-5 ml-5">Add new order</h4>
  <hr class="my-4">
  <form id="order_form" action="<?php echo BASE_URL; ?>orders/addorder" method="post">
    <div class="container-fluid text-center">
      <div class="form-group row">
        <label for="user" class="col-2">User</label>
        <div class="col-6">
          <select id="users" name="user" class="form-control">
            <?php  foreach ($data['users'] as $users) { ?>
            <option value="<?php echo $users->user_id; ?>"><?php echo $users->name; ?></option>
            <?php }?>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="product" class="col-2">Products</label>
        <div class="col-6">
          <select id="products" name="product" class="form-control">
            <?php
            foreach ($data['products'] as $products) {
              $myJObj = new stdClass();
              $myJObj->product_id = $products->product_id;
              $myJObj->qty = $products->qty;
              $myJObj->unit_price = $products->unit_price;
              $myJObj->discount = $products->discount;
              $jString = json_encode($myJObj);
            ?>
            <option value='<?php echo $jString; ?>'><?php echo $products->product_name; ?></option>
            <?php }?>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="quantity" class="col-2">Quantity</label>
        <div class="col-6">
          <div class="input-group mb-3">
            <input type="number" id="qty" name="qty" value="0" class="form-control" onkeydown="return false" min="0"  max="<?php echo $data['products'][0]->qty; ?>">
            <div class="input-group-append">
              <span id="unit_price" class="input-group-text">Unit Price € : <?php echo number_format($data['products'][0]->unit_price,2); ?></span>
              <span id="qty_available" class="input-group-text">Available : <?php echo $data['products'][0]->qty; ?></span>
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
              <span id="discounted" class="input-group-text">Saved € : 0.00</span>
              <span id="discount" class="input-group-text">Discount : 0 %</span>
              <span class="input-group-text">€</span>
              <span id="total_amount" class="input-group-text">0.00</span>
            </div>
             <input type="hidden" id="order_data_array" name="order_data_array">
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary">Order Now!</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <hr class="my-4">
  <?php flash('order_error'); ?>
  <?php flash('order_success'); ?>
<form action="<?php echo BASE_URL; ?>orders/sortOrder" method="post">
  <div class="input-group mb-3">
    <input type="text" class="form-control" aria-describedby="basic-addon2" value="Search Records By : <?php echo $data['sort_by']; ?>" readonly />
    <div class="input-group-prepend">
    <div class="input-group-append">
      <select class="form-control" name="sort">
        <option value="all_the_time">All The Time</option>
        <option value="last_7_days">Last 7 Days</option>
        <option value="last_month">Last Month</option>
        <option value="today">Today</option>
      </select>
    </div>
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
  <hr class="my-4">
  <table id="data_table" class="table table-bordered table-hovered">
    <thead>
      <tr>
        <td>User</td>
        <td>product</td>
        <td>Price</td>
        <td>Quantity</td>
        <td>Total</td>
        <td>Date</td>
        <td>Action</td>
      </tr>
    </thead>
    <tbody>
      <?php
        foreach ($data['table_data'] as $table_data) {
          ?>
          <tr>
            <td><?php echo $table_data->name; ?></td>
            <td><?php echo $table_data->product_name; ?></td>
            <td><?php echo $table_data->unit_price; ?></td>
            <td><?php echo $table_data->qty; ?></td>
            <td><?php echo $table_data->total; ?></td>
            <td><?php echo $table_data->created; ?></td>
            <td class="text-center">
              <a id="edit" href="<?php echo BASE_URL.'orders/editorder/'.$table_data->order_id; ?>" class="btn btn-primary btn-sm" role="button">Edit</a>
              <a id="delete" href="<?php echo BASE_URL.'orders/deleteorder/'.$table_data->order_id; ?>" class="btn btn-danger btn-sm" role="button">Delete</a>
            </td>
          </tr>
          <?php
        }
      ?>
    </tbody>
  </table>
  </div>
</div>
<?php
  require APP_URL.'/views/inc/footer.php';
?>

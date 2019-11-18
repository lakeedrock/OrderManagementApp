<?php

  /**
   *
   */
  class Orders extends Controller{

    public function __construct()  {
      if (!(isset($_SESSION['user_id']) && isset($_SESSION['user_email']) && isset($_SESSION['user_name']))) {
        header('location: '. BASE_URL.'users/login');
      }
      $this->orderModel = $this->model('Order');

    }

    public function index(){

      $this->users = $this->orderModel->getUsers();
      $this->products = $this->orderModel->getProducts();
      $this->tableData = $this->orderModel->getTableData();
      $data = array(
        'users' => $this->users,
        'products' => $this->products,
        'table_data' => $this->tableData,
        'sort_by' => 'All The Time'
      );
      $this->view('orders/orders',$data);
    }

    public function addOrder(){
       if ($_SERVER['REQUEST_METHOD'] == 'POST') {

         //Process Order Form
         $data = [
           'user_id' => trim($_POST['user']),
           'product_data' => trim($_POST['product']),
           'qty' => trim($_POST['qty']),
           'order_data_array' => trim($_POST['order_data_array']),
           'qty_error' => ''
         ];

         // Quantity validation
         if ($data['qty'] > 0) {

           $product_data = json_decode($data['product_data']);
           $order_data_array = json_decode($data['order_data_array']);

           $order_data = array(
             'user_id' => $data['user_id'],
             'product_id' => $product_data->product_id,
             'qty' => $data['qty'],
             'unit_price' => $product_data->unit_price,
             'total' => $order_data_array->total_amount,
             'discount' => $order_data_array->discount,
             'discounted_amount' => $order_data_array->discount_amount,
             'available_qty' => $order_data_array->available_qty
           );

           //Create Order
           if ($this->orderModel->addOrder($order_data)) {
             //Init Data for new order

             flash('order_success','Your order has been created.');
             redirect('orders/index');
           }else{
             //trigger error
           }
         }else{

           //Optional remove e.preventDefault(); in main js line# 68
           flash('order_error','No order quantity selected!','alert alert-danger');
           redirect('orders/index');
         }
       }
    }

    public function sortOrder(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $sortMethod = trim($_POST['sort']);

        $this->users = $this->orderModel->getUsers();
        $this->products = $this->orderModel->getProducts();
        $this->tableData = $this->orderModel->getTableData($sortMethod);

        $sortText = '';
        switch ($sortMethod) {
          case 'all_the_time':
            $sortText = 'All The Time';
            break;
          case 'last_7_days':
            $sortText = 'Last 7 Days';
            break;
          case 'last_month':
            $sortText = 'Last Month';
            break;
          case 'today':
            $sortText = 'Today';
            break;
          default:
            $sortText = 'All the Time';
            break;
        }

        $data = array(
          'users' => $this->users,
          'products' => $this->products,
          'table_data' => $this->tableData,
          'sort_by' => $sortText
        );
        $this->view('orders/orders',$data);
      }else{
        redirect('orders/index');
      }
    }

    public function deleteOrder($id){
      if ($id > 0) {
        if ($this->orderModel->deleteOrder($id)) {
          flash('order_success','Order Deleted Successfully','alert alert-danger');
          redirect('orders/index');
        }else{
          $this->orderError();
        }
      }else{
        $this->orderError();
      }
    }

    public function orderError(){
      flash('order_error','Error Occured While Deleting  The Order','alert alert-danger');
      redirect('orders/index');
    }

    public function editOrder($id){
      //getting order data
      $orderData = $this->orderModel->editOrderData($id);
      $this->users = $this->orderModel->getUsers();
      $this->products = $this->orderModel->getProducts();

      $data = array(
        'users' => $this->users,
        'products' => $this->products,
        'order_data' => $orderData
      );
      $this->view('orders/editorder',$data);
    }

    public function eidtOrderUpdate(){

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $product_data = json_decode(trim($_POST['product']));
        $order_data_array = json_decode(trim($_POST['order_data_array']));

        $order_data = array(
          'order_id' => $product_data->order_id,
          'product_id' => $product_data->product_id,
          'qty' => trim($_POST['qty']),
          'total' => $order_data_array->total_amount,
          'discount' => $order_data_array->discount,
          'discount_amount' => $order_data_array->discount_amount,
          'available_qty' => $order_data_array->available_qty
        );

        if ($order_data['qty'] > 0) {
          if ($this->orderModel->updateOrder($order_data)) {
            //Success
            flash('order_success','Order Successfully Updated','alert alert-primary');
            redirect('orders/index');

          }else {
            //Fatal Error
            flash('order_error','Fatal Error Occured','alert alert-danger');
            redirect('orders/index');
          }
        }
     }else{
       redirect('orders/index');
    }
  }
}

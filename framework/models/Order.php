<?php

  /**
   *
   */
  class Order{

    public function __construct()  {
      $this->db = new Database;
    }

    public function getUsers(){
      $sql = 'SELECT user_id,name FROM user';
      $this->db->query($sql);
      return $resultset = $this->db->resultSet();
    }

    public function getProducts(){
      $sql = 'SELECT * FROM products';
      $this->db->query($sql);
      return $resultSet = $this->db->resultSet();
    }

    public function addOrder($order_data){
      $sql = 'INSERT INTO orders (user_id, product_id, qty, unit_price, total, discount, discounted_amount) VALUES (:user_id, :product_id, :qty, :unit_price, :total, :discount, :discounted_amount)';
      $this->db->query($sql);
      //Bind Values
      $this->db->bind(':user_id', $order_data['user_id']);
      $this->db->bind(':product_id', $order_data['product_id']);
      $this->db->bind(':qty', $order_data['qty']);
      $this->db->bind(':unit_price', $order_data['unit_price']);
      $this->db->bind(':total', $order_data['total']);
      $this->db->bind(':discount', $order_data['discount']);
      $this->db->bind(':discounted_amount', $order_data['discounted_amount']);




      //Execute Query
      if ($this->db->execute() && $this->updateStock($order_data['product_id'],$order_data['available_qty'])) {
        return true;
      }else{
        return false;
      }
    }

    public function updateStock($product_id,$qty){
      $sql = 'UPDATE products SET qty =:qty WHERE product_id = :product_id';
      $this->db->query($sql);
      //Bind Values
      $this->db->bind(':qty', $qty);
      $this->db->bind(':product_id', $product_id);
      //Execute Query
      if ($this->db->execute()) {
        return true;
      }else{
        return false;
      }
    }

    public function getTableData($params=''){

      $lastWeekSql = "WHERE \norders.created >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY AND orders.created < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY\n";
      $lastMonthSql = "WHERE \nYEAR(orders.created) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(orders.created) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)\n";
      $todaySql = "WHERE \nDATE(orders.created) = CURDATE() \n";
      $defaultSQL = '';

      $sortSql = '';
      switch ($params) {
        case 'all_the_time':
          $sortSql = $defaultSQL;
          break;
        case 'last_7_days':
          $sortSql = $lastWeekSql;
          break;
        case 'last_month':
          $sortSql = $lastMonthSql;
          break;
        case 'today':
          $sortSql = $todaySql;
          break;
        default:
          $sortSql = null;
          break;
      }

      $sql = "SELECT\n".
      "orders.order_id,\n".
      "`user`.`name`,\n".
      "products.product_name,\n".
      "orders.unit_price,\n".
      "orders.qty,\n".
      "orders.total,\n".
      "orders.created\n".
      "FROM\n".
      "orders\n".
      "INNER JOIN `user` ON orders.user_id = `user`.user_id\n".
      "INNER JOIN products ON orders.product_id = products.product_id\n".$sortSql.
      "ORDER BY\n".
      "orders.order_id ASC";
      $this->db->query($sql);
      $data = $this->db->resultSet();
      return $data;

    }

    public function editOrderData($id){
      $sql = 'SELECT * FROM orders WHERE order_id = :id';
      $this->db->query($sql);
      $this->db->bind(':id', $id);
      $row = $this->db->singleResult();

      if ($this->db->rowCount() > 0) {
        return $row;
      }else{
        return false;
      }
    }

    public function updateOrder($data){
      $sql = 'UPDATE orders SET qty = :qty, total = :total, discount = :discount, discounted_amount = :discounted_amount, created = CURRENT_TIMESTAMP()  WHERE order_id = :order_id';
      $this->db->query($sql);
      //Bind Values
      $this->db->bind(':qty', $data['qty']);
      $this->db->bind(':total', $data['total']);
      $this->db->bind(':discount', $data['discount']);
      $this->db->bind(':discounted_amount', $data['discount_amount']);
      $this->db->bind(':order_id', $data['order_id']);
      //Execute Query
      if ($this->db->execute() && $this->updateStock($data['product_id'],$data['available_qty'])) {
        return true;
      }else{
        return false;
      }
    }

    public function deleteOrder($id){

      if ($this->updateDeletedOrderQty($id)) {
        $sql = "DELETE FROM orders WHERE order_id = :order_id";
        $this->db->query($sql);
        //Bind Values
        $this->db->bind(':order_id', $id);
        if ($this->db->execute()) {
          return true;
        }else{
          return false;
        }
      }
    }

    public function updateDeletedOrderQty($id){
      $sql = "SELECT product_id,qty FROM orders WHERE order_id = :order_id";
      $this->db->query($sql);
      //Bind Values
      $this->db->bind(':order_id', $id);
      $order = $this->db->singleResult();
      if ($this->db->rowCount() > 0) {
        $sql = "SELECT qty FROM products WHERE product_id = :product_id";
        $this->db->query($sql);
        $this->db->bind(':product_id', $order->product_id);
        $product = $this->db->singleResult();
        if ($this->db->rowCount() > 0) {
          $updatedQty = $order->qty + $product->qty;
          if ($this->updateStock($order->product_id,$updatedQty)) {
            return true;
          }else{
            return false;
          }
        }else{
          return false;
        }
      }else{
        return false;
      }
    }
  }

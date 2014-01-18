<?php
/**
 * Plugin Name: Stato sede PoUL
 * Plugin URI: http://santoro.tk/blog/?p=45
 * Description: Visualizza lo stato della sede del PoUL
 * Version: 0.1
 * Author: Emanuele Santoro
 * Author URI: http://santoro.tk
 * License: GPLv3
 */



class BITS_Status extends WP_Widget {

  

  /**
   * Sets up the widgets name etc
   */
  public function __construct() {
    // widget actual processes

    $params = array(
	   "description" => "Visualizza lo stato della sede del PoUL",
	   "name"        => "BITS Status"
    ) ;
    

    parent::__construct("BITS_Status", "", $params) ;
  }
  
  /**
   * Outputs the content of the widget
   *
   * @param array $args
   * @param array $instance
   */
  public function widget( $args, $instance ) {
    // outputs the content of the widget
    
    // public function getStatusSede($datasource) {
    
    echo "<h1>Stato della sede:</h1>" ;


    $status = $this->getStatusSede( $instance["dataSource"]) ;

    ?>
    
    <p style="color: <?php echo $status == 'open' ? 'green' : 'red';   ?> ;">
        <?php echo $status ?>   
    <?php
  }
  
  /**
   * Ouputs the options form on admin
   *
   * @param array $instance The widget options
   */
  public function form( $instance ) {
    // outputs the options form on admin

    
    // Imposto un valore di default
    if ( !isset($instance["dataSource"]) ) {
      $instance["dataSource"] = "http://bits.poul.org/data" ;
    }

    extract($instance) ;
    
    ?>

    <p>
      <label for="<?php echo $this->get_field_id('dataSource'); ?>">Sorgente dati:</label>
      <input class="widefat" 
             id="<?php echo $this->get_field_id('dataSource'); ?>" 
	     name="<?php echo $this->get_field_name('dataSource'); ?>" 
	     value="<?php if (isset($dataSource)) echo esc_attr($dataSource); ?>"
             type="text"/>

    </p>
    <?php

  }
  
  /**
   * Processing widget options on save
   *
   * @param array $new_instance The new options
   * @param array $old_instance The previous options
   */
  

  public function getStatusSede($datasource) {

     // step1: get data
    $client = curl_init($datasource) ;

    
    // I want data to be returned, not echoed:
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true) ;
    // I don't need HTTP headers
    curl_setopt($client, CURLOPT_HEADER, false) ;

    $json_data = curl_exec($client) ;

    
    
    // step 2: decode data
    

    $data = json_decode($json_data) ;

    // echo $data->status->value ;
    return $data->status->value ;
  }
  
}

add_action("widgets_init", "register_bits_status") ;

function register_bits_status() {
  register_widget("BITS_Status") ;
}
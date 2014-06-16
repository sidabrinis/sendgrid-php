<?php

class SendGrid {
  const VERSION = "2.0.5";

  protected $namespace  = "SendGrid",
            $url        = "https://api.sendgrid.com/api/mail.send.json",
            $headers    = array('Content-Type' => 'application/json'),
            $username,
            $password,
            $options,
            $web;
  
  public function __construct($username, $password, $options=array("turn_off_ssl_verification" => false)) {
    $this->username = $username;
    $this->password = $password;
    $this->options  = $options;
    $this->web      = new SendGrid\Web($username, $password, $options);
  }

  public function send(SendGrid\Email $email) {
    $form             = $email->toWebFormat();
    $form['api_user'] = $this->username; 
    $form['api_key']  = $this->password; 

    // option to ignore verification of ssl certificate
    if (isset($this->options['turn_off_ssl_verification']) && $this->options['turn_off_ssl_verification'] == true) {
      \Unirest::verifyPeer(false);
    }

    $response = \Unirest::post($this->url, array(), $form);

    return $response->body;
  }

  public function __get($api) {
      $name = $api;

      if($this->$name != null) {
          return $this->$name;
      }

      $api = $this->namespace . "\\" . ucwords($api);
      $class_name = str_replace('\\', '/', "$api.php");
      $file = __dir__ . DIRECTORY_SEPARATOR . $class_name;

      if (!file_exists($file)) {
          throw new Exception("Api '$class_name' not found.");
      }
      require_once $file;

      $this->$name = new $api($this->username, $this->password, $this->options);
      return $this->$name;
  }

    public static function register_autoloader() {
    spl_autoload_register(array('SendGrid', 'autoloader'));
  }

  public static function autoloader($class) {
    // Check that the class starts with "SendGrid"
    if ($class == 'SendGrid' || stripos($class, 'SendGrid\\') === 0) {
      $file = str_replace('\\', '/', $class);

      if (file_exists(dirname(__FILE__) . '/' . $file . '.php')) {
        require_once(dirname(__FILE__) . '/' . $file . '.php');
      }
    }
  }
}

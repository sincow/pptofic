<?php
class Session {

   //********************************************************************************
   public function __construct() {
      if (session_status() == PHP_SESSION_NONE) {
         session_start();
      }
   }


   //********************************************************************************
   public function set($key, $value) {
      $_SESSION[$key] = $value;
   }


   //********************************************************************************
   public function get($key) {
      return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
   }


   //********************************************************************************
   public function remove($key) {
      if (isset($_SESSION[$key])) {
         unset($_SESSION[$key]);
      }
   }


   //********************************************************************************
   public function destroy() {
      session_destroy();
   }


   //********************************************************************************
   public function isLoggedIn() {
      return $this->get('user_id') !== null;
   }


   //********************************************************************************
   public function getUser($key = null) {
      $user = $this->get('user');
      if ($key && $user) {
         return isset($user[$key]) ? $user[$key] : null;
      }
      return $user;
   }
}
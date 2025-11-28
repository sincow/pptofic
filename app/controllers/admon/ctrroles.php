<?php
if (!isset($_POST["option"])) {
	require_once APP_PATH . '/models/admin/mdlroles.php';
}

class RolesController {

   //*****************************************************************************************
   static public function index(){
      $roles = RolesModel::getAll(null);
      return $roles;
   }


   //*****************************************************************************************
   static public function getOne($id) {
      $role = RolesModel::getOne("id", $id, null);
      return $role;
   }

}
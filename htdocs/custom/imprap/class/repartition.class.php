﻿<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year>  <name of author>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    class/myclass.class.php
 * \ingroup mymodule
 * \brief   Example CRUD (Create/Read/Update/Delete) class.
 *
 * Put detailed description here.
 */

/** Includes */
//require_once DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php";
//require_once DOL_DOCUMENT_ROOT."/societe/class/societe.class.php";
//require_once DOL_DOCUMENT_ROOT."/product/class/product.class.php";

/**
 * Put your class' description here
 */
class repartition // extends CommonObject
{

    /** @var DoliDb Database handler */
	private $db;
    /** @var string Error code or message */
	public $error;
    /** @var array Several error codes or messages */
	public $errors = array();
    /** @var string Id to identify managed object */
	//public $element='myelement';
    /** @var string Name of table without prefix where object is stored */
	//public $table_element='mytable';
    /** @var int An example ID */
	public $id;
	public $courant;
    /** @var mixed An example property */
	public $date_d_operation;
    /** @var mixed An example property */
	public $date_de_valeur;
	public $debut;
	public $credit;
	public $libelle;
	public $solde;
	public $datee;
	public $oper;
	public $label;
	public $amount = 0;
	public $num_chq;
	public $categorie;
	public $accountid;

	/**
	 * Constructor
	 *
	 * @param DoliDb $db Database handler
	 */
	public function __construct($db)
	{
		$this->db = $db;

		return 1;
	}

	/**
	 * Create object into database
	 *
	 * @param User $user User that create
	 * @param int $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int <0 if KO, Id of created object if OK
	 */
	public function createOperation()
	{	
		global $conf, $langs;
		$error = 0;
		
		// Clean parameters
		if (isset($this->date_d_operation)) {
			$this->date_d_operation = trim($this->date_d_operation);
		}
		if (isset($this->date_de_valeur)) {
			$this->date_de_valeur = trim($this->date_de_valeur);
		}
		if (isset($this->debut)) {
			$this->debut = (float)$this->debut;
		}else{ $this->debut = 0; }
		if (isset($this->credit)) {
			$this->credit = (float)$this->credit;
		}else{ $this->credit = 0; }
		if (isset($this->libelle)) {
			$this->libelle = trim($this->libelle);
		}
		if (isset($this->solde)) {
			$this->solde = trim($this->solde);
		}
		
		if (isset($this->amount)) {
			$this->amount = $this->debut + $this->credit;
			// echo $this->credit."<br>".$this->debut."<br>".$this->amount."<br>";
		}
		

		// Check parameters
		// Put here code to add control on parameters values
		// Insert request
		$sql = "INSERT INTO " . MAIN_DB_PREFIX . "bank_rapprofile(";
		$sql.= " date_d_operation,";
		$sql.= " date_de_valeur,";
		$sql.= " debut,";
		$sql.= " credit,";
		$sql.= " libelle,";
		$sql.= " solde, ";
		$sql.= " fk_account ";


		$sql.= ") VALUES (";
		$sql.= " '" . $this->date_de_valeur . "',";
		$sql.= " '" . $this->date_d_operation . "',";
		$sql.= " " . $this->debut . ",";
		$sql.= " " . $this->credit . ",";
		$sql.= " '" . $this->libelle . "',";
		$sql.= " " . $this->solde . ", ";
		$sql.= " " . $this->accountid . " ";

		$sql.= ")";		
		
		$this->db->begin();

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if (! $resql) {
			$error ++;
			$this->errors[] = "Error " . $this->db->lasterror();
		}

		if (! $error) {
			$this->id = $this->db->last_insert_id(MAIN_DB_PREFIX . "bank_rapprofile");
			
			if (! $notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action call a trigger.
				//// Call triggers
				//include_once DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php";
				//$interface=new Interfaces($this->db);
				//$result=$interface->run_triggers('MYOBJECT_CREATE',$this,$user,$langs,$conf);
				//if ($result < 0) { $error++; $this->errors=$interface->errors; }
				//// End call triggers
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

			return -1 * $error;
		} else {
			$this->db->commit();

			return $this->id;
		}
	}
	
	public function create()
	{
		global $conf, $langs;
		$error = 0;

		// Clean parameters
		if (isset($this->date_d_operation)) {
			$this->date_d_operation = trim($this->date_d_operation);
		}
		if (isset($this->date_de_valeur)) {
			$this->date_de_valeur = trim($this->date_de_valeur);
		}
		if (isset($this->debut)) {
			$this->debut = trim($this->debut);
		}
		if (isset($this->credit)) {
			$this->credit = trim($this->credit);
		}
		if (isset($this->libelle)) {
			$this->libelle = trim($this->libelle);
		}
		if (isset($this->solde)) {
			$this->solde = trim($this->solde);
		}

		// Check parameters
		// Put here code to add control on parameters values
		// Insert request
		$sql = "INSERT INTO " . MAIN_DB_PREFIX . "bank(";
		$sql.= " date_d_operation,";
		$sql.= " date_de_valeur,";
		$sql.= " debut,";
		$sql.= " credit,";
		$sql.= " libelle,";
		$sql.= " solde";



		$sql.= ") VALUES (";
		$sql.= " '" . $this->date_d_operation . "',";
		$sql.= " '" . $this->date_de_valeur . "',";
		$sql.= " '" . $this->debut . "',";
		$sql.= " '" . $this->credit . "',";
		$sql.= " '" . $this->libelle . "',";
		$sql.= " '" . $this->solde . "'";

		$sql.= ")";
		

		$this->db->begin();

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if (! $resql) {
			$error ++;
			$this->errors[] = "Error " . $this->db->lasterror();
		}

		if (! $error) {
			$this->id = $this->db->last_insert_id(MAIN_DB_PREFIX . "mytable");

			if (! $notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action call a trigger.
				//// Call triggers
				//include_once DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php";
				//$interface=new Interfaces($this->db);
				//$result=$interface->run_triggers('MYOBJECT_CREATE',$this,$user,$langs,$conf);
				//if ($result < 0) { $error++; $this->errors=$interface->errors; }
				//// End call triggers
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

			return -1 * $error;
		} else {
			$this->db->commit();

			return $this->id;
		}
	}
	
	
	/**
	 * Load object in memory from database
	 *
	 * @param int $id Id object
	 * @return int <0 if KO, >0 if OK
	 */
	public function getOperation($id = null,$dated = null,$datef = null)
	{
		global $langs;

		$sql = "SELECT * FROM " . MAIN_DB_PREFIX . "bank ";
		
		if(!empty($dated) AND empty($datef)){
			$sql .= " Where fk_account = " .$id." and dateo >= '".$dated."'" ;
		}elseif(empty($dated) AND !empty($datef)){
			$sql .= " Where fk_account = " .$id." and dateo <= '".$datef."'";
		}elseif(!empty($dated) AND !empty($datef)){
			$sql .= " Where fk_account = " . $id ." and dateo BETWEEN '".$dated."' and '".$datef."'" ;
		}else{
			$sql .= " Where fk_account = " . $id ;
		}
		
		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {

			$arrayOperation = array();
			
			if ($this->db->num_rows($resql)) {
				while($obj = $this->db->fetch_object($resql)){
					$arrayOperation[] = $obj;
				}				
			}
			$this->db->free($resql);				

			return $arrayOperation;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);

			return -1;
		}
	}
	
	public function getaccount()
	{
		global $langs;

		$sql = "SELECT * FROM " . MAIN_DB_PREFIX . "bank_account Where clos = 0";

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {

			$arrayAccount = array();

			if ($this->db->num_rows($resql)) {
				while($obj = $this->db->fetch_object($resql)){
					$arrayAccount[] = $obj;
				}
				
			}
			$this->db->free($resql);				

			return $arrayAccount;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);

			return -1;
		}
	}
	
	public function fetchALl()
	{
		global $langs;
		$sql = "SELECT";
		$sql.= " date_d_operation,";
		$sql.= " date_de_valeur,";
		$sql.= " debut,";
		$sql.= " credit,";
		$sql.= " libelle,";
		$sql.= " solde";
		//...
		$sql.= " FROM " . MAIN_DB_PREFIX . "fichcsv";

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if ($resql) {
			if ($this->db->num_rows($resql)) {
				$obj = $this->db->fetch_object($resql);

				// $this->prop1 = $obj->field1;
				// $this->prop2 = $obj->field2;
				
				// $this->date_d_operation = $obj->date_d_operation;
				// $this->date_de_valeur = $obj->date_d_operation;
				// $this->debut = $obj->date_d_operation;
				// $this->credit = $obj->credit;
				// $this->libelle = $obj->libelle;
				// $this->solde = $obj->solde;
				// ...
			}
			$this->db->free($resql);

			return 1;
		} else {
			$this->error = "Error " . $this->db->lasterror();
			dol_syslog(__METHOD__ . " " . $this->error, LOG_ERR);

			return -1;
		}
	}

	/**
	 * Update object into database
	 *
	 * @param User $user User that modify
	 * @param int $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int <0 if KO, >0 if OK
	 */
	public function update($user = 0, $notrigger = 0)
	{
		global $conf, $langs;
		$error = 0;

		// Clean parameters
		if (isset($this->prop1)) {
			$this->prop1 = trim($this->prop1);
		}
		if (isset($this->prop2)) {
			$this->prop2 = trim($this->prop2);
		}

		// Check parameters
		// Put here code to add control on parameters values
		// Update request
		$sql = "UPDATE " . MAIN_DB_PREFIX . "mytable SET";
		$sql.= " field1=" . (isset($this->field1) ? "'" . $this->db->escape($this->field1) . "'" : "null") . ",";
		$sql.= " field2=" . (isset($this->field2) ? "'" . $this->db->escape($this->field2) . "'" : "null") . "";

		$sql.= " WHERE rowid=" . $this->id;

		$this->db->begin();

		dol_syslog(__METHOD__ . " sql=" . $sql, LOG_DEBUG);
		$resql = $this->db->query($sql);
		if (! $resql) {
			$error ++;
			$this->errors[] = "Error " . $this->db->lasterror();
		}

		if (! $error) {
			if (! $notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action call a trigger.
				//// Call triggers
				//include_once DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php";
				//$interface=new Interfaces($this->db);
				//$result=$interface->run_triggers('MYOBJECT_MODIFY',$this,$user,$langs,$conf);
				//if ($result < 0) { $error++; $this->errors=$interface->errors; }
				//// End call triggers
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

			return -1 * $error;
		} else {
			$this->db->commit();

			return 1;
		}
	}

	/**
	 * Delete object in database
	 *
	 * @param User $user User that delete
	 * @param int $notrigger 0=launch triggers after, 1=disable triggers
	 * @return int <0 if KO, >0 if OK
	 */
	public function delete($user, $notrigger = 0)
	{
		global $conf, $langs;
		$error = 0;

		$this->db->begin();

		if (! $error) {
			if (! $notrigger) {
				// Uncomment this and change MYOBJECT to your own tag if you
				// want this action call a trigger.
				//// Call triggers
				//include_once DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php";
				//$interface=new Interfaces($this->db);
				//$result=$interface->run_triggers('MYOBJECT_DELETE',$this,$user,$langs,$conf);
				//if ($result < 0) { $error++; $this->errors=$interface->errors; }
				//// End call triggers
			}
		}

		if (! $error) {
			$sql = "DELETE FROM " . MAIN_DB_PREFIX . "mytable";
			$sql.= " WHERE rowid=" . $this->id;

			dol_syslog(__METHOD__ . " sql=" . $sql);
			$resql = $this->db->query($sql);
			if (! $resql) {
				$error ++;
				$this->errors[] = "Error " . $this->db->lasterror();
			}
		}

		// Commit or rollback
		if ($error) {
			foreach ($this->errors as $errmsg) {
				dol_syslog(__METHOD__ . " " . $errmsg, LOG_ERR);
				$this->error.=($this->error ? ', ' . $errmsg : $errmsg);
			}
			$this->db->rollback();

			return -1 * $error;
		} else {
			$this->db->commit();

			return 1;
		}
	}
	

	/**
	 * Load an object from its id and create a new one in database
	 *
	 * @param int $fromid Id of object to clone
	 * @return int New id of clone
	 */
	public function createFromClone($fromid)
	{
		global $user, $langs;

		$error = 0;

		$object = new SkeletonClass($this->db);

		$this->db->begin();

		// Load source object
		$object->fetch($fromid);
		$object->id = 0;
		$object->statut = 0;

		// Clear fields
		// ...
		// Create clone
		$result = $object->create($user);

		// Other options
		if ($result < 0) {
			$this->error = $object->error;
			$error ++;
		}

		if (! $error) {
			// Do something
		}

		// End
		if (! $error) {
			$this->db->commit();

			return $object->id;
		} else {
			$this->db->rollback();

			return -1;
		}
	}

	/**
	 * Initialise object with example values
	 * Id must be 0 if object instance is a specimen
	 *
	 * @return void
	 */
	public function initAsSpecimen()
	{
		$this->id = 0;
		$this->prop1 = 'prop1';
		$this->prop2 = 'prop2';
	}
}

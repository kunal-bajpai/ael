<?php
	class Employee extends User {
		protected static $tableName = 'employee';
		
		const EMPLOYEE = -1;
		const MASTER_ADMIN = 0;

	}

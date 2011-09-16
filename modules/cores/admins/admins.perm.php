<?php
class admins_perm {
	function getAdminPermission() {
		global $vsLang;

		$permission = array($vsLang->getWords('admins_perm','Admin manager permission'),
		array(// admin permission
										'deleteadmin'		=> $vsLang->getWords('admin_perm_delete'		,'Delete an Admin'),
										'editadmin'			=> $vsLang->getWords('admin_perm_edit'			,'Edit an Admin'),
										'displayadmintable'	=> $vsLang->getWords('admin_perm_displaytable'	,'View Admin list'),
										'addeditadmin'		=> $vsLang->getWords('admin_perm_addedit'		,'Process add/edit Admin'),
										'displayadmin'		=> $vsLang->getWords('admin_perm_displayadmin'	,'View Admin users tab'),
		// group admin permission
										'deletegroup'		=> $vsLang->getWords('admingroup_perm_delete'		,'Delete an Admin Group'),
										'editgroup'			=> $vsLang->getWords('admingroup_perm_editgroup'	,'Edit an Admin Group'),
										'displaygrouptable'	=> $vsLang->getWords('admingroup_perm_displaytable'	,'View Admin Group list'),
										'addeditgroup'		=> $vsLang->getWords('admingroup_perm_addedit'		,'Process add/edit Group'),
										'displaygroup'		=> $vsLang->getWords('admingroup_perm_displaygroup' ,'View Admin Group tabs'),
										'permission'		=> $vsLang->getWords('admingroup_perm_permission'		,'Truy cập vùng phân quyền'),
		// access module
										'default' 			=> $vsLang->getWords('admin_perm_global', 'Access admin module')
		)
		);
		return $permission;
	}
}
?>
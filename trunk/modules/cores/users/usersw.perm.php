<?php
class users_perm {
	function getAdminPermission() {
		global $vsLang;

		$permission = array($vsLang->getWords('user_perms','Danh sách quyền của người dùng'),
		array(
		// group admin permission
										'delete-user-group'		=> $vsLang->getWords('delete-user-group','Xóa nhóm người dùng'),
										'edit-group'			=> $vsLang->getWords('edit-group','Sửa nhóm người dùng'),
										'display-list-group'	=> $vsLang->getWords('display-list-group','Xem danh sách nhóm'),
										'add-group'				=> $vsLang->getWords('add-group','Thêm nhóm mới'),
										'display-group-tab'		=> $vsLang->getWords('display-group-tab','Vùng Quản lý nhóm'),
										'savepermission'		=> $vsLang->getWords('savepermission','Lưu quyền'),
										'getpermission'			=> $vsLang->getWords('getpermission','Kiểm tra quyền'),
										'permission'			=> $vsLang->getWords('permission','Xem quyền'),
		// admin permission
										'edit-obj-form'			=> $vsLang->getWords('edit-obj-form','Sửa tài khoản'),
										'add-obj-form'			=> $vsLang->getWords('add-obj-form','Thêm tài khoản'),
										'hide-checked-obj'		=> $vsLang->getWords('hide-checked-obj','Khóa tài khoản'),
										'visible-checked-obj'	=> $vsLang->getWords('visible-checked-obj','Kích hoạt tài khoản'),
										'add-edit-obj'			=> $vsLang->getWords('add-edit-obj','Chức năng thêm/sửa tài khoản'),
										'display-obj-list'		=> $vsLang->getWords('display-obj-list','Xem danh sách tài khoản'),
										'delete-checked-obj'	=> $vsLang->getWords('delete-checked-obj','Xóa tài khoản'),
										'display-obj-tab'		=> $vsLang->getWords('display-obj-tab','Vùng quản lý tài khoản'),
		// access module
										'default' 				=> $vsLang->getWords('default', 'Truy cập module')
		)
		);
		return $permission;
	}
}
?>
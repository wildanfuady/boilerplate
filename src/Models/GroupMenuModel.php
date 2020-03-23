<?php

namespace agungsugiarto\boilerplate\Models;

use CodeIgniter\Model;

class GroupMenuModel extends Model
{
    protected $table = 'groups_menu';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['group_id', 'menu_id'];
    protected $skipValidation = true;

    /**
     * Menu has role to check specific user can
     * accsess to the menu.
     *
     * @return array
     */
    public function menuHasRole()
    {
        return $this->db->table('menu')
            ->select('menu.id, menu.parent_id, menu.active, menu.title, menu.icon, menu.route')
            ->join('groups_menu', 'menu.id = groups_menu.menu_id', 'left')
            ->join('auth_groups', 'groups_menu.group_id = auth_groups.id', 'left')
            ->join('auth_groups_users', 'auth_groups.id = auth_groups_users.group_id', 'left')
            ->join('users', 'auth_groups_users.user_id = users.id', 'left')
            ->where(['users.id' => user()->id, 'menu.active' => 1])
            ->groupBy('menu.id')
            ->get()
            ->getResultObject();
    }
}

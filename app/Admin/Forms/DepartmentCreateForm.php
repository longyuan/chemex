<?php

namespace App\Admin\Forms;

use App\Models\Department;
use App\Models\Role;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;
use Exception;


class DepartmentCreateForm extends Form implements LazyRenderable
{
    use LazyWidget;
    /**
     * 处理表单提交逻辑.
     *
     * @param array $input
     *
     * @return JsonResponse
     */
    public function handle(array $input): JsonResponse
    {
        $p_id = $this->payload['p_id'] ?? null;
        $role_id = $input['role_id'] ?? null;
        $parent_id = $input['parent_id'] ?? null;
        $description = $input['description'] ?? null;
        $name = $input['name'] ?? null;

        if (!$p_id || !$name || !$description) {
            return $this->response()
                ->error(trans('main.parameter_missing'));
        }

        try {
            $department = new Department();
            $department->name = $name;
            $department->description = $description;
            $department->parent_id = $parent_id;
            $department->role_id = $role_id;
            $department->save();
            return $this->response()
                ->success(trans('main.success'))
                ->refresh();
        } catch (Exception $exception) {
            return $this->response()
                ->error(trans('main.fail') . ' : ' . $exception->getMessage());
        }
    }

    /**
     * 构造表单.
     */
    public function form()
    {
        $p_id = $this->payload['p_id'] ?? null;
        $this->text('name')->required();
        $this->divider();
        $this->text('description');
        $this->select('parent_id')
            ->options(Department::pluck('name', 'id'))
            ->default($p_id);
        $this->select('role_id')
            ->options(Role::pluck('name', 'id'));
    }

}

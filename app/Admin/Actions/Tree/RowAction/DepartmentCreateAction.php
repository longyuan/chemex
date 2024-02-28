<?php

namespace App\Admin\Actions\Tree\RowAction;

use App\Admin\Forms\CustomColumnUpdateForm;
use App\Admin\Forms\DepartmentCreateForm;
use Dcat\Admin\Tree\RowAction;
use Dcat\Admin\Widgets\Modal;

class DepartmentCreateAction extends RowAction
{
    public function __construct()
    {
        parent::__construct();
        $this->title = '<i class="feather icon-plus"></i> 子类' ;
    }

    public function render(): Modal
    {
        // 实例化表单类并传递自定义参数
        $form = DepartmentCreateForm::make()->payload([
            'p_id' => $this->getKey(),
        ]);

        return Modal::make()
            ->lg()
            ->title('添加子类')
            ->body($form)
            ->button($this->title);
    }
}

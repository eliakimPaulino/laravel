<?php

namespace App\Admin\Controllers;

use App\Models\CourseType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Tree;

class CourseTypeController extends AdminController
{

    public function index(Content $content) {
        $tree = new Tree(new CourseType());
        return $content->header('Course Types')->body($tree);
    }


    protected function detail($id)
    {

        $show = new Show(CourseType::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Category'));
        $show->field('description', __('Description'));
        $show->field('order', __('Order'));
        $show->field('created_at', __('Created at'));
        $show->field('upadated_at', __('Upadated at'));

        return $show;
    }

    protected function form()
    {
        $form = new Form(new CourseType());

        $form->select  ('parent_id', __('Parent category'))->options(CourseType::selectOptions());
        $form->text    ('title', __('Title'));
        $form->textarea('description', __('Description'));
        $form->number  ('order', __('Order'));


        return $form;
    }
}

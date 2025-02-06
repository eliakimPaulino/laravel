<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Illuminate\Support\Str;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

//  this grid is for the http://192.168.0.104:8000/admin/users page.

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Members';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
        $grid->column('token', __('Token'))->display(function ($token) {return Str::limit($token, 10, '...');});
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('email_verified_at', __('Email verified at'));
        $grid->column('avatar', __('Avatar'));
        $grid->column('type', __('Type'));
        $grid->column('open_id', __('Open id'));
        $grid->column('access_token', __('Access token'))->display(function ($token) {return Str::limit($token, 10, '...');});
        $grid->column('deleted_at', __('Deleted at'))->display(function ($token) {return Str::limit($token, 10, '...');});
        $grid->column('phone', __('Phone'));
        $grid->column('remember_token', __('Remember token'));
        $grid->column('created_at', __('Created at'))->display(function ($token) {return Str::limit($token, 10, '...');});
        $grid->column('updated_at', __('Updated at'))->display(function ($token) {return Str::limit($token, 10, '...');});

        $grid->disableActions();
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableFilter();


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('token', __('Token'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('avatar', __('Avatar'));
        $show->field('type', __('Type'));
        $show->field('open_id', __('Open id'));
        $show->field('access_token', __('Access token'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('phone', __('Phone'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('token', __('Token'));
        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->password('password', __('Password'));
        $form->image('avatar', __('Avatar'));
        $form->number('type', __('Type'));
        $form->text('open_id', __('Open id'));
        $form->text('access_token', __('Access token'));
        $form->mobile('phone', __('Phone'));
        $form->text('remember_token', __('Remember token'));

        return $form;
    }
}

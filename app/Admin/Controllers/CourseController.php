<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\CourseType;
use App\Models\Course;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Course';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Course());

        $grid->column('id', __('Id'));
        $grid->column('user_token', __('Teacher'))->display(function($token) {
            return User::where('token', '=', $token)->value('name');
        });
        $grid->column('name', __('Name'));
        // $grid->column('thumbnail', __('Thumbnail'))->image('/uploads', 100, 100);
        $grid->column('thumbnail', __('Thumbnail'))->display(function ($thumbnail) {
            return "<img src='/uploads/{$thumbnail}' alt='Thumbnail of {$this->name}' title='Thumbnail of {$this->name}' width='100' height='100'>";
        });
        $grid->column('description', __('Description'));
        $grid->column('type_id', __('Type id'));
        $grid->column('price', __('Price'));
        $grid->column('lesson_num', __('Lesson num'));
        $grid->column('video_length', __('Video length'));
        $grid->column('follow', __('Follow'));
        $grid->column('score', __('Score'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Course::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('thumbnail', __('Thumbnail'));
        $show->field('description', __('Description'));
        $show->field('price', __('Price'));
        $show->field('lesson_num', __('Lesson num'));
        $show->field('video_length', __('Video length'));
        $show->field('follow', __('Follow'));
        $show->field('score', __('Score'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }
    protected function form()
    {
        $form = new Form(new Course());
        $form->text('name', __('Name'));

        $result = CourseType::pluck('title', 'id');
        // select() helps to select one of the options that comes from result var
        $form->select('type_id', __('Category'))->options($result);
        $form->image('thumbnail', __('Thumbnail'))->uniqueName();
        // file is used for video and other format like pdf/doc.
        $form->file('video', __('Video'))->uniqueName();
        $form->text('description', __('Description'));
        // decimal method helps with retrieving float format from the database.
        $form->decimal('price', __('Price'));
        $form->number('lesson_num', __('Lesson number'));
        $form->number('video_length', __('Video Length'));

        $result = User::pluck('name', 'token');
        $form->select('user_token', __('Teacher'))->options($result);
        $form->display('created_at', __('Created at'));
        $form->display('updated_at', __('Upadated at'));

        $form->text('title', __('Title'));
        $form->textarea('description', __('Description'));


        return $form;
    }
}

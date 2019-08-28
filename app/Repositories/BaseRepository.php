<?php
/**
 * Created by PhpStorm.
 * User: flanche
 * Date: 2017/9/25
 * Time: 上午10:57
 */

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    protected $app;

    public $model;

    public $originalModel;

    protected $day;
    protected $beginDate;
    protected $endDate;

    public function __construct()
    {
        $this->app = app();
    }

    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }
    public function getModel()
    {
        return $this->model;
    }

    public function setDay($day)
    {
        $this->day = $day - 1;
        $this->beginDate = date('Y-m-d', strtotime("-{$this->day} day"));
        $this->endDate = date('Y-m-d 23:59:59', time());
    }
    /**
     * 创建项目
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * 更新项目
     * @param array $conditions
     * @param array $attributes
     * @return mixed
     */
    public function update(array $conditions, array $attributes)
    {
        return $this->model
            ->where($conditions)
            ->update($attributes);
    }

    /**
     * 删除
     * @param array $conditions
     * @return mixed
     */
    public function delete(array $conditions)
    {
        return $this->model
            ->where($conditions)
            ->delete();
    }
    /**
     * 通过任何条件获取单个model
     * @param array $conditions
     * @param array $columns
     * @return mixed
     */
    public function getSingleByAnyConditions(array $conditions, $columns = ['*'])
    {
        return $this->model->where($conditions)->first($columns);
    }
}
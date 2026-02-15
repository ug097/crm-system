<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectUser extends Pivot
{
    use SoftDeletes;

    /**
     * project_user テーブルは id を主キーに持つため、auto-increment を有効にする。
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * 中間テーブル名
     *
     * @var string
     */
    protected $table = 'project_user';

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }
}

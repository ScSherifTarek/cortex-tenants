<?php

declare(strict_types=1);

namespace Cortex\Tenants\DataTables\Adminarea;

use Rinvex\Tenants\Contracts\TenantContract;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Cortex\Tenants\Transformers\Adminarea\TenantTransformer;

class TenantsDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = TenantContract::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = TenantTransformer::class;

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = app($this->model)->query()->with(['owner']);

        return $this->applyScopes($query);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $link = config('cortex.foundation.route.locale_prefix')
            ? '"<a href=\""+routes.route(\'adminarea.tenants.edit\', {tenant: full.slug, locale: \''.$this->request->segment(1).'\'})+"\">"+data+"</a>"'
            : '"<a href=\""+routes.route(\'adminarea.tenants.edit\', {tenant: full.slug})+"\">"+data+"</a>"';

        return [
            'name' => ['title' => trans('cortex/tenants::common.name'), 'render' => $link.'+(full.is_active ? " <i class=\"text-success fa fa-check\"></i>" : " <i class=\"text-danger fa fa-close\"></i>")', 'responsivePriority' => 0],
            'email' => ['title' => trans('cortex/tenants::common.email')],
            'phone' => ['title' => trans('cortex/tenants::common.phone')],
            'owner' => ['title' => trans('cortex/tenants::common.owner'), 'name' => 'owner.username'],
            'country_code' => ['title' => trans('cortex/tenants::common.country')],
            'language_code' => ['title' => trans('cortex/tenants::common.language')],
            'created_at' => ['title' => trans('cortex/tenants::common.created_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
            'updated_at' => ['title' => trans('cortex/tenants::common.updated_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
        ];
    }
}

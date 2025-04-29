<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeCrud extends Command
{
    protected $signature = 'make:crud {name} {--only=}';
    protected $description = 'Create CRUD structure: Model, Controller, Repository, Service, Request';

    public function handle(): void
    {
        $inputName = str_replace('\\', '/', $this->argument('name'));
        $segments = collect(explode('/', $inputName))->map(fn($v) => Str::studly($v))->toArray();

        $className = array_pop($segments);
        $namespacePath = implode('/', $segments);
        $namespace = implode('\\', $segments);

        $only = $this->option('only');
        $targets = $only ? explode(',', $only) : ['model', 'controller', 'repo', 'repository', 'service', 'request'];

        foreach ($targets as $target) {
            $target = strtolower(trim($target));
            $target === 'repo' ? $target = 'repository' : '';
            if ($target === 'model') {
                $this->makeFile("Models/{$className}.php", $this->modelTemplate($className));
            } elseif ($target === 'controller') {
                $this->makeFile("Http/Controllers/{$namespacePath}/{$className}Controller.php", $this->controllerTemplate($namespace, $className));
            } elseif ($target === 'repository') {
                $this->makeFile("Repositories/{$className}Repository.php", $this->repositoryTemplate($className));
            } elseif ($target === 'service') {
                $this->makeFile("Services/{$className}Service.php", $this->serviceTemplate($className));
            } elseif ($target === 'request') {
                foreach ($this->requestTemplate($namespace, $className) as $path => $content) {
                    $this->makeFile($path, $content);
                }
            } else {
                $this->warn("Unsupported type: {$target}");
            }
        }

        $this->info('CRUD generation complete.');
    }

    private function makeFile(string $relativePath, string $content): void
    {
        $fullPath = app_path($relativePath);

        if (File::exists($fullPath)) {
            $this->warn("File already exists: {$fullPath}");
            return;
        }

        File::ensureDirectoryExists(dirname($fullPath));
        File::put($fullPath, $content);
        $this->info("Created: {$fullPath}");
    }

    private function modelTemplate(string $class): string
    {
        return <<<PHP
        <?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Model;

        class {$class} extends Model
        {
            protected \$fillable = [
                // Add your model attributes here
            ];
        }
        PHP;
    }

    private function controllerTemplate(string $namespace, string $class): string
    {
        $namespace = trim($namespace, '\\');
        $serviceVar = lcfirst($class) . 'Service';

        return <<<PHP
        <?php

        namespace App\Http\Controllers\\$namespace;

        use App\Http\Controllers\Controller;
        use Illuminate\Http\Request;
        use App\Services\\{$class}Service;
        use App\Http\Requests\\$namespace\\{$class}CreateRequest;
        use App\Http\Requests\\$namespace\\{$class}UpdateRequest;

        class {$class}Controller extends Controller
        {
            protected \${$serviceVar};

            public function __construct({$class}Service \${$serviceVar})
            {
                \$this->{$serviceVar} = \${$serviceVar};
            }

            public function index()
            {
                \$items = \$appends = \$compact = [];

                foreach (request()->all() as \$key => \$value) {
                    \$\$key = \$value;
                    if (isset(\$\$key)) {
                        \$appends = array_merge(\$appends, [\$key => \$value]);
                        \$compact = array_merge(\$compact, [\$key]);
                    }
                }

                if (!isset(\$list)) {
                    \$list = 50;
                    \$compact = array_merge(\$compact, ['list']);
                }

                \$items = \$this->{$serviceVar}->get(\$list);

                \$compact = array_merge(\$compact, ['appends', 'items']);
                return view('admin.index', compact(\$compact)); // 自行調整 view
            }

            public function create()
            {
                return view('admin.create'); // 自行調整 view
            }

            public function store({$class}CreateRequest \$request)
            {
                \$this->{$serviceVar}->create(\$request->validated());
                return redirect()->back();
            }

            public function show(string \$id)
            {
                \$item = \$this->{$serviceVar}->show(\$id);
                return view('admin.show', compact('item')); // 自行調整 view
            }

            public function edit(string \$id)
            {
                \$item = \$this->{$serviceVar}->show(\$id);
                return view('admin.edit', compact('item')); // 自行調整 view
            }

            public function update({$class}UpdateRequest \$request, string \$id)
            {
                \$this->{$serviceVar}->update(\$request->validated(), \$id);
                return redirect()->back();
            }

            public function destroy(string \$id)
            {
                \$this->{$serviceVar}->delete(\$id);
                return redirect()->back();
            }
        }
        PHP;
    }


    private function repositoryTemplate(string $class): string
    {
        return <<<PHP
        <?php

        namespace App\Repositories;

        use App\Models\\$class;

        class {$class}Repository
        {
            protected \$model;

            public function __construct({$class} \$model)
            {
                \$this->model = \$model;
            }

            /**
             * 取得資料（可條件搜尋、模糊搜尋、排序、分頁、關聯載入）
             *
             * @param  array       \$where     精確條件，例如 ['is_active' => 1]
             * @param  array       \$search    模糊搜尋，例如 ['name' => '股份']
             * @param  array       \$with      預載入關聯，例如 ['company', 'users.roles']
             * @param  array       \$orderBy   多欄排序，例如 [['created_at', 'desc'], ['id', 'asc']]
             * @param  int|null    \$perPage   每頁筆數，若為 null 則不分頁
             * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
             */
            public function get(
                array \$where = [],
                array \$search = [],
                array \$with = [],
                array \$orderBy = [['id', 'desc']],
                int \$perPage = null
            ) {
                \$query = \$this->model->newQuery();

                // 預載入關聯
                if (!empty(\$with)) {
                    \$query->with(\$with);
                }

                // 條件搜尋
                if (!empty(\$where)) {
                    \$query->where(\$where);
                }

                // 模糊搜尋（排除空值）
                foreach (\$search as \$field => \$keyword) {
                    if (\$keyword !== '') {
                        \$query->where(\$field, 'LIKE', '%' . addcslashes(\$keyword, '%_') . '%');
                    }
                }

                // 多組排序
                foreach (\$orderBy as \$order) {
                    if (!is_array(\$order) || count(\$order) !== 2) {
                        continue; // 跳過格式不正確的
                    }
                    [\$column, \$direction] = \$order;
                    \$direction = strtolower(\$direction) === 'desc' ? 'desc' : 'asc';
                    \$query->orderBy(\$column, \$direction);
                }

                return \$perPage ? \$query->paginate(\$perPage) : \$query->get();
            }


            public function first(\$id)
            {
                return \$this->model->find(\$id);
            }

            public function create(array \$data)
            {
                return \$this->model->create(\$data);
            }

            public function update(int \$id, array \$data)
            {
                return \$this->model->findOrFail(\$id)->update(\$data);
            }

            public function delete(int \$id)
            {
                return \$this->model->where('id', \$id)->delete();
            }
        }
        PHP;
    }

    private function serviceTemplate(string $class): string
    {
        $repositoryVar = lcfirst($class) . 'Repository';

        return <<<PHP
        <?php

        namespace App\Services;

        use App\Repositories\\{$class}Repository;

        class {$class}Service
        {
            protected \${$repositoryVar};

            public function __construct({$class}Repository \${$repositoryVar})
            {
                \$this->{$repositoryVar} = \${$repositoryVar};
            }

            /**
             * 取得資料（條件搜尋、模糊搜尋、關聯、排序、分頁）
             *
             * @param int \$perPage
             * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
             */
            public function get(\$perPage)
            {
                \$with = \$where = \$search = [];
                \$orderBy = [['id', 'desc']];

                foreach (request()->all() as \$key => \$value) {
                    \$\$key = \$value;
                }

                // 自訂搜尋條件
                if (request()->filled('keyword')) {
                    \$search = [
                        'name' => request('keyword')
                    ];
                }

                return \$this->{$repositoryVar}->get(\$where, \$search, \$with, \$orderBy, \$perPage);
            }

            public function show(\$id)
            {
                return \$this->{$repositoryVar}->first(\$id);
            }

            public function update(array \$data, \$id)
            {
                return \$this->{$repositoryVar}->update(\$id, \$data);
            }

            public function delete(\$id)
            {
                return \$this->{$repositoryVar}->delete(\$id);
            }
        }
        PHP;
    }

    private function requestTemplate(string $namespace, string $class): array
    {
        $namespace = trim($namespace, '\\');

        $createRequest = <<<PHP
        <?php

        namespace App\Http\Requests\\$namespace;

        use Illuminate\Foundation\Http\FormRequest;

        class {$class}CreateRequest extends FormRequest
        {
            public function authorize(): bool
            {
                return true;
            }

            public function rules(): array
            {
                return [
                    //
                ];
            }
        }
        PHP;

        $updateRequest = <<<PHP
        <?php

        namespace App\Http\Requests\\$namespace;

        use Illuminate\Foundation\Http\FormRequest;

        class {$class}UpdateRequest extends FormRequest
        {
            public function authorize(): bool
            {
                return true;
            }

            public function rules(): array
            {
                return [
                    //
                ];
            }
        }
        PHP;

        return [
            "Http/Requests/{$namespace}/{$class}CreateRequest.php" => $createRequest,
            "Http/Requests/{$namespace}/{$class}UpdateRequest.php" => $updateRequest,
        ];
    }
}
